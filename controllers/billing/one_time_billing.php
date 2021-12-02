<?php
$check_merchant = $DB->preparedQueryFetch("SELECT * FROM ". getenv('BILLING_TABLE') ." WHERE `shop_url`=:shop LIMIT 1", [
    ":shop" => $_SESSION['shop_name'].".myshopify.com"
]);

if(isset($_GET['charge_id']) && !empty($_GET['charge_id']) || !empty($check_merchant['fetch']['id'])){
    $cid = isset($_GET['charge_id']) ? $_GET['charge_id'] : $check_merchant['fetch']['charge_id'];

    $query = array(
        "query"=> '{
            node(id: "gid://shopify/AppPurchaseOneTime/'.$cid.'"){
                ... on AppPurchaseOneTime {
                    status
                    id
                }
            }
        }'
    );

    $check_charge = shopify_gql_call($_SESSION['access_token'], $_SESSION['shop_name'], $query);
    $check_charge = json_decode($check_charge['response'], true);

    if(!empty($check_charge['data']['node'])){
        if(isset($check_charge['data']['node']['status']) && $check_charge['data']['node']['status'] != 'ACTIVE'){
            echo "Sorry, but your Shopify store cannot use this app because parchuse is invalid. Try again."; 
            die();
        }
    }else{
        echo "Sorry, but your Shopify store cannot use this app because parchuse is invalid. Try again.";
        die();
    }

    $charge_id = $check_charge['data']['node']['id'];
    $charge_id = explode("/",$charge_id);
    $charge_id = $charge_id[array_key_last($charge_id)];
    $gid = $check_charge['data']['node']['id'];
    $status = $check_charge['data']['node']['status'];

    $DB->preparedQuery("INSERT INTO ". getenv('BILLING_TABLE') ."(`charge_id`, `shop_url`, `gid`, `status`) VALUES (:charge_id, :shop_url, :gid, :nstatus) ON DUPLICATE KEY UPDATE `status`=:ustatus", [
        ":charge_id" => $charge_id,
        ":shop_url" => $_SESSION['shop_name'].".myshopify.com",
        ":gid" => $gid,
        ":nstatus" => $status,
        ":ustatus" => $status
    ]);
}else{
    $query = array(
        "query"=> 'mutation {
            appPurchaseOneTimeCreate(
                name: "'.getenv("APPLICATION_NAME").' One-time Charge"
                price: { amount: '. getenv("APPLICATION_PRICE") .'  currencyCode: USD }
                test: '. getenv("TEST_BILLING") .'
                returnUrl: "https://'. $_SESSION['shop_name'] .'.myshopify.com/admin/apps/'. getenv('APPLICATION_HANDLE_URL') .'"
            ) {
                appPurchaseOneTime {
                    id
                }
                confirmationUrl
            }
        }'
    );
    $charge = shopify_gql_call($_SESSION['access_token'], $_SESSION['shop_name'], $query);
    $charge = json_decode($charge['response'], true);
    
    echo "<script type='text/javascript'> top.window.location='". $charge['data']['appPurchaseOneTimeCreate']['confirmationUrl'] ."'</script>";
    die();
}
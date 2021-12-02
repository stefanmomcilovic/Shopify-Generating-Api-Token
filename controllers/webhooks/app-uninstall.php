<?php
require realpath($_SERVER["DOCUMENT_ROOT"]) . "/controllers/webhooks/webhook-requirements.php";

if($verified == true) {
    if( $topic_header == 'app/uninstalled' || $topic_header == 'shop/update') {
      if( $topic_header == 'app/uninstalled' ) {

        $sql = "DELETE FROM ".getenv('MAIN_TABLE')." WHERE store_url=:shop_header LIMIT 1";

        if($DB->preparedQuery($sql, [':shop_header' => $shop_header])){
            $res = $shop_header . ' is successfully deleted from the database.';
        }
  
      } else {
        $res = $data;
      }
    }
} else {
    $res = 'The request is not from Shopify.';
}

$log = fopen("logs/".$shop_header."-log.log", 'w');
fwrite($log, $res);
fclose($log);
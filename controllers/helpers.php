<?php
// Requiring our database connection with functions
require realpath($_SERVER["DOCUMENT_ROOT"]) . "/controllers/config/config.php";

// Check if shop exists in our db and check if session is set and not empty
$session = false;

if(!empty($_GET['shop'])){
	$row = $DB->preparedQueryFetch("SELECT * FROM ".getenv('MAIN_TABLE')." WHERE store_url=:shop_url LIMIT 1", [":shop_url" => $_GET['shop']]);
	if($row['rowCount'] < 1) {
		header("Location:".getenv('APPLICATION_URL')."/controllers/installation/install.php?shop=".$_GET['shop']);
	}else{
        $_SESSION['shop_name'] = explode('.',$row['fetch']['store_url'])[0];
        $_SESSION['access_token']= $row['fetch']['access_token'];

        if(isset($_SESSION['access_token']) && isset($_SESSION['shop_name']) && !empty($_SESSION['shop_name']) && !empty($_SESSION['access_token'])){
            $session = true;
        }
    }
}

if(isset($_SESSION['access_token']) && isset($_SESSION['shop_name']) && !empty($_SESSION['shop_name']) && !empty($_SESSION['access_token'])){
    $session = true;
}

if(!$session){
    echo '
    <script type="text/javascript">
        alert("Sorry, You are Unauthorized, please try again!");
        window.close();
    </script>
    ';
}

// To make shopify api request
if(!function_exists('shopify_call')){
  function shopify_call($token, $shop, $api_endpoint, $query = array(), $method = 'GET', $request_headers = array()) {
  	// Build URL
  	$url = "https://" . $shop . ".myshopify.com" . $api_endpoint;
  	if (!is_null($query) && in_array($method, array('GET', 	'DELETE'))) $url = $url . "?" . http_build_query($query);

  	// Configure cURL
  	$curl = curl_init($url);
  	curl_setopt($curl, CURLOPT_HEADER, TRUE);
  	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
  	curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
  	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  	// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 3);
  	// curl_setopt($curl, CURLOPT_SSLVERSION, 3);
  	curl_setopt($curl, CURLOPT_USERAGENT, 'My New Shopify App v.1');
  	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
  	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
  	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

  	// Setup headers
  	$request_headers[] = "";
  	if (!is_null($token)) $request_headers[] = "X-Shopify-Access-Token: " . $token;
  	curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);

  	if ($method != 'GET' && in_array($method, array('POST', 'PUT'))) {
  		if (is_array($query)) $query = http_build_query($query);
  		curl_setopt ($curl, CURLOPT_POSTFIELDS, $query);
  	}

  	// Send request to Shopify and capture any errors
  	$response = curl_exec($curl);
  	$error_number = curl_errno($curl);
  	$error_message = curl_error($curl);

  	// Close cURL to be nice
  	curl_close($curl);

  	// Return an error is cURL has a problem
  	if ($error_number) {
  		return $error_message;
  	} else {

  		// No error, return Shopify's response by parsing out the body and the headers
  		$response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);

  		// Convert headers into an array
  		$headers = array();
  		$header_data = explode("\n",$response[0]);
  		$headers['status'] = $header_data[0]; // Does not contain a key, have to explicitly set
  		array_shift($header_data); // Remove status, we've already set it above
  		foreach($header_data as $part) {
  			$h = explode(":", $part, 2);
  			$headers[trim($h[0])] = trim($h[1]);
  		}

  		// Return headers and Shopify's response
  		return array('headers' => $headers, 'response' => $response[1]);

  	}

  }
}

// To make Shopify graphql calls
if(!function_exists('shopify_gql_call')){
  function shopify_gql_call($token, $shop, $query = array()) {
  	// Build URL
  	$url = "https://" . $shop . ".myshopify.com" . "/admin/api/".getenv('API_DATE')."/graphql.json";

  	// Configure cURL
  	$curl = curl_init($url);
  	curl_setopt($curl, CURLOPT_HEADER, TRUE);
  	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
  	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
  	curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
  	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
  	// curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 3);
  	// curl_setopt($curl, CURLOPT_SSLVERSION, 3);
  	curl_setopt($curl, CURLOPT_USERAGENT, 'My New Shopify App v.1');
  	curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
  	curl_setopt($curl, CURLOPT_TIMEOUT, 30);

  	// Setup headers
  	$request_headers[] = "";
  	$request_headers[] = "Content-Type: application/json";
  	if (!is_null($token)) $request_headers[] = "X-Shopify-Access-Token: " . $token;
  	curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);
  	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($query));
  	curl_setopt($curl, CURLOPT_POST, true);

  	// Send request to Shopify and capture any errors
  	$response = curl_exec($curl);
  	$error_number = curl_errno($curl);
  	$error_message = curl_error($curl);

  	// Close cURL to be nice
  	curl_close($curl);

  	// Return an error is cURL has a problem
  	if ($error_number) {
  		return $error_message;
  	} else {

  		// No error, return Shopify's response by parsing out the body and the headers
  		$response = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);

  		// Convert headers into an array
  		$headers = array();
  		$header_data = explode("\n",$response[0]);
  		$headers['status'] = $header_data[0]; // Does not contain a key, have to explicitly set
  		array_shift($header_data); // Remove status, we've already set it above
  		foreach($header_data as $part) {
  			$h = explode(":", $part, 2);
  			$headers[trim($h[0])] = trim($h[1]);
  		}

  		// Return headers and Shopify's response
  		return array('headers' => $headers, 'response' => $response[1]);

  	}

  }
}

// Check if user uninstall our application
if(isset($_SESSION["access_token"]) && isset($_SESSION["shop_name"])){
	$app_uninstall = array(
		'webhook' => array(
			'topic' => 'app/uninstalled',
			'address' => getenv('APPLICATION_URL').'/controllers/webhooks/app-uninstall.php?shop='.$_SESSION['shop_name'],
			'format' => 'json'
		)
	);
	
	$webhook = shopify_call($_SESSION['access_token'], $_SESSION['shop_name'], "/admin/api/".getenv('API_DATE')."/webhooks.json", $app_uninstall, 'POST');
	$webhook = json_decode($webhook['response'], JSON_PRETTY_PRINT);
}

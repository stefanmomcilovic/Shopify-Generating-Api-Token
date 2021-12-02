<?php
// Get our helper functions
require realpath($_SERVER["DOCUMENT_ROOT"]) . "/controllers/helpers.php";

// Set variables for our request
$api_key = getenv('API_KEY');
$shared_secret = getenv('SECRET_KEY');
$params = $_GET; // Retrieve all request parameters
$hmac = $_GET['hmac']; // Retrieve HMAC request parameter

$params = array_diff_key($params, array('hmac' => '')); // Remove hmac from params
ksort($params); // Sort params lexographically

$computed_hmac = hash_hmac('sha256', http_build_query($params), $shared_secret);

// Use hmac data to check that the response is from Shopify or not
if (hash_equals($hmac, $computed_hmac)) {

	// Set variables for our request
	$query = array(
		"client_id" => $api_key, // Your API key
		"client_secret" => $shared_secret, // Your app credentials (secret key)
		"code" => $params['code'] // Grab the access key from the URL
	);

	// Generate access token URL
	$access_token_url = "https://" . $params['shop'] . "/admin/oauth/access_token";

	// Configure curl client and execute request
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $access_token_url);
	curl_setopt($ch, CURLOPT_POST, count($query));
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
	$result = curl_exec($ch);
	curl_close($ch);

	// Store the access token
	$result = json_decode($result, true);
	$access_token = $result['access_token'];
	
	// If shop does not exist, create a new one and assign token
	if($DB->preparedQuery("INSERT INTO ". getenv('MAIN_TABLE') ."(store_url, access_token) VALUES (:store_url, :access_token)  ON DUPLICATE KEY UPDATE `access_token`=:uaccess_token", [
		":store_url" => $params['shop'],
		":access_token" => $access_token,
		":uaccess_token"=> $access_token
	])){
		header("Location: https://". $params['shop']. '/admin/apps');
	}else{
		die("Sorry error occurred, try to install application again or contact an application developers.");
	}
		
} else {
	// Someone is trying to be shady!
	die('This request is NOT from Shopify!');
}

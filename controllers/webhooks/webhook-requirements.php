<?php
require realpath($_SERVER["DOCUMENT_ROOT"]) . "/includes/helpers.php";

define('SHOPIFY_APP_SECRET', getenv('SECRET_KEY'));

function verify_webhook($data, $hmac_header){
  $calculated_hmac = base64_encode(hash_hmac('sha256', $data, SHOPIFY_APP_SECRET, true));
  return hash_equals($hmac_header, $calculated_hmac);
}

$res = '';
$hmac_header = $_SERVER['HTTP_X_SHOPIFY_HMAC_SHA256'];
$topic_header = $_SERVER['HTTP_X_SHOPIFY_TOPIC'];
$shop_header = $_SERVER['HTTP_X_SHOPIFY_SHOP_DOMAIN'];
$data = file_get_contents('php://input');
$decoded_data = json_decode($data, true);

$verified = verify_webhook($data, $hmac_header);

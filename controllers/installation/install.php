<?php
require realpath($_SERVER["DOCUMENT_ROOT"]) . "/controllers/config/config.php";

// Set variables for our request
$shop = $_GET['shop'];
$api_key = getenv('API_KEY');
$scopes = getenv("SCOPES");

$redirect_uri = getenv('APPLICATION_URL')."/controllers/installation/generate_token.php";

// Build install/approval URL to redirect to
$install_url = "https://" . $shop . "/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);

// Redirect
header("Location: " . $install_url);
die();

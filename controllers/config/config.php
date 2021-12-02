<?php
ob_start(); // Turn on output buffering
session_start(); // Session start
date_default_timezone_set("Europe/Belgrade"); // Set Default Time Zone For Website

/************************************
 *  SET UP ENVIRONMENT VARIABLES 
*************************************/

putenv("API_KEY="); // Shopify App API KEY
putenv("SECRET_KEY="); // Shopify App SECRET KEY
putenv("SCOPES=read_assigned_fulfillment_orders, write_assigned_fulfillment_orders, read_checkouts, write_checkouts, read_content, write_content, read_customers, write_customers, read_discounts, write_discounts, read_draft_orders, write_draft_orders, read_files, write_files, read_fulfillments, write_fulfillments, read_gift_cards, write_gift_cards, read_inventory, write_inventory, read_locales, write_locales, read_locations, read_marketing_events, write_marketing_events, read_merchant_managed_fulfillment_orders, write_merchant_managed_fulfillment_orders, read_orders, write_orders, read_price_rules, write_price_rules, read_products, write_products, read_product_listings, read_reports, write_reports, read_resource_feedbacks, write_resource_feedbacks, read_script_tags, write_script_tags, read_shipping, write_shipping, read_shopify_payments_disputes, read_shopify_payments_payouts, read_themes, write_themes, read_translations, write_translations, read_third_party_fulfillment_orders, write_third_party_fulfillment_orders, write_order_edits, unauthenticated_read_checkouts, unauthenticated_write_checkouts, unauthenticated_read_customers, unauthenticated_write_customers, unauthenticated_read_customer_tags, unauthenticated_read_content, unauthenticated_read_product_listings, unauthenticated_read_product_tags"); // Set Scopes for your application

putenv("APPLICATION_NAME=ShopifyPHPApp"); // We need application name so when we use Billing API to say our application name
putenv("APPLICATION_URL="); // Our Application URL PASTE APPLICATION URL WITHOUT / ON THE END
putenv("APPLICATION_HANDLE_URL="); // When payment succed on Billing API we rederect to our app home

putenv("MAIN_TABLE=shopify_main"); // Our Application Main Table Name
putenv("BILLING_TABLE=shopify_billings"); // Our Application Main Table Name

putenv("API_DATE=2021-10"); // Because shopify use dates for API calls and every 6 months is new one we set one and use it everyware when we call Shopify API
putenv("TEST_BILLING=true"); // We check if testing is true for billing api
putenv("APPLICATION_PRICE=29.99"); // To put application price as default currency we use USD

/******************************************
 *  END OF SET UP ENVIRONMENT VARIABLES 
*******************************************/

/************************************ 
 *     SET UP DATABASE VARIABLES
**************************************/
class DB{
    public $error_array = array();
    public $con;

    private $dbname = ""; // Database Name
    private $host = "localhost"; // Host
    private $username = "root"; // Database Login username
    private $password = ""; // Database Login Password

    function __construct(){
        // Connection with PDO
        try {
            $this->con = new PDO("mysql:dbname=" . $this->dbname . ";host=" . $this->host . ";", $this->username, $this->password);
            $this->con->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->con->setAttribute(PDO::ATTR_EMULATE_PREPARES, TRUE);
            return $this->con;
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    function query($sql){
        try {
            $query = $this->con->query($sql);
            $rowCount = $query->rowCount();

            return array("rowCount" => $rowCount, "Query" => $query);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function fetch($sql){
        try {
            $query = $this->con->query($sql);
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $rowCount = $query->rowCount();

            return array("rowCount" => $rowCount, "fetch" => $row);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function fetchAll($sql){
        try {
            $query = $this->con->query($sql);
            $rows = $query->fetchAll(PDO::FETCH_ASSOC);
            $rowCount = $query->rowCount();

            return array("rowCount" => $rowCount, "fetchAll" => $rows);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function preparedQuery($sql, $data = array(":named_parameter" => "Value of Paramater")){
        try {
            $query = $this->con->prepare($sql);
            foreach ($data as $key => $value) {
                $query->bindValue($key, $value);
            }
            $query->execute();
            $rowCount = $query->rowCount();

            return array("rowCount" => $rowCount, "Query" => $query);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function preparedQueryFetch($sql, $data = array(":named_parameter" => "Value of Paramater")){
        try {
            $query = $this->con->prepare($sql);
            foreach ($data as $key => $value) {
                $query->bindValue($key, $value);
            }
            $query->execute();
            $fetch = $query->fetch(PDO::FETCH_ASSOC);
            $rowCount = $query->rowCount();

            return array("rowCount" => $rowCount, "fetch" => $fetch);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function preparedQueryFetchAll($sql, $data = array(":named_parameter" => "Value of Paramater")){
        try {
            $query = $this->con->prepare($sql);
            foreach ($data as $key => $value) {
                $query->bindValue($key, $value);
            }
            $query->execute();
            $fetch = $query->fetchAll(PDO::FETCH_ASSOC);
            $rowCount = $query->rowCount();

            return array("rowCount" => $rowCount, "fetchAll" => $fetch);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    function dumpData($data){
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }
}
$DB = new DB();

function fatal_handler(){
    global $DB;
    $error = error_get_last();
    if ($error !== NULL) {
        $DB->dumpData($error);
    }
}

register_shutdown_function("fatal_handler");
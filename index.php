<?php
    /*===================================================================================
     *   FOR ANYTHING ELSE YOU DO, DO NOT REQUIRE/INCLUDE BEFORE  require realpath($_SERVER["DOCUMENT_ROOT"]) . "/controllers/helpers.php";
     *===================================================================================*/
    require realpath($_SERVER["DOCUMENT_ROOT"]) . "/controllers/helpers.php";
     /*===================================================================================
     *   IF WE WANT TO START BILLING OUR APP WE WILL REQUIRE ONE OF THE FILES IN BILLING FOLDER 
     *   IF WE WANT ONE TIME PAYMENT WE REQUIRE  require realpath($_SERVER["DOCUMENT_ROOT"]) . "/controllers/billing/one_time_billing.php";
     *   IF WE WANT MONTHLY SUBSCRIBTION WE WILL REQUIRE require require realpath($_SERVER["DOCUMENT_ROOT"]) . "/controllers/billing/recurring_billing.php"
     *   REQUIRE IT ON ONLY ON index.php
     *===================================================================================*/
    // realpath($_SERVER["DOCUMENT_ROOT"]) . "/controllers/billing/one_time_billing.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shopify Application with PHP</title>
    <!-- META TAGS -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 
        DO NOT REMOVE THIS JAVASCRIPT SCRIPT,
        BECAUSE IN SOME BROWSERS WON'T WORK IF WE ARE ON SHOPIFY APPS PAGE ON SHOPIFY STORE,
        THAT'S WHY WE REDIRECT THE APP TO OUR APP URL PAGE TO GET MORE FUNCTIONALITY AND DON'T GET SILLY ERRORS
    -->
    <script type="text/javascript" src="./public/assets/js/required_script.js"></script>
    <!-- INCLUDED JAVASCRIPT AND CSS -->

    <!-- OUR JAVASCRIPT AND CSS -->
</head>
<body>
    <h1>Hello, Welcome To Starting Template For Shopify PHP Application!</h1>
</body>
</html>

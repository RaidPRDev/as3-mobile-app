<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../dbconfig.php';
require_once('stripe_config.php');
include_once 'goaliemind/Customer.php';

$customerInfo = array();
//$customerInfo["email"] = "fania4life@gmail.com";
//$customerInfo["description"] = "GoalieMind Rafael Customer";
$customerInfo["email"] = "sania@gmail.com";
$customerInfo["description"] = "Sania Customer";

// $customerInfo["description"] =  array("userid" => $info["uid"],"paymentid" => $info["paymentid"]);

$customer = new Customer();
$response = $customer->createCustomer($customerInfo);

// header('Content-Type: application/json');
// $data = json_encode($response, JSON_PRETTY_PRINT);
// print_r(json_decode($data, true));

echo json_encode($response);
?>
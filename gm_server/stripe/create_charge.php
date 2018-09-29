<?php

require_once('/stripe_config.php');

include_once 'goaliemind/Customer.php';
include_once 'goaliemind/Token.php';
include_once 'goaliemind/Card.php';

$customer = new Customer();
$customerInfo = $customer->retrieveCustomer(array("id"=>"cus_8gh9XJJkrXLL0r"));

$cardInfo = array();
$cardInfo["name"] = "Hector Lavoe Perez";
$cardInfo["number"] = "4242424242424242";
$cardInfo["exp_month"] = 6;
$cardInfo["exp_year"] = 2017;
$cardInfo["cvc"] = "314";
$cardInfo["zip"] = "00969";

$token = new Token();
$tokenInfo = $token->createCardToken($cardInfo);

$response = $customer->updateCustomer(array("id"=>$customerInfo->id,"tokenID"=>$tokenInfo->id));

header('Content-Type: application/json');
$data = json_encode($response, JSON_PRETTY_PRINT);
print_r(json_decode($data, true));

?>
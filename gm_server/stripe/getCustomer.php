<?php

require_once('/stripe_config.php');

include_once 'goaliemind/Customer.php';

if (!isset($_GET['id']))
{
	echo "No customer id found!";
	exit;
}

$customerInfo = array();
$customer = new Customer();
$customerInfo["id"] = trim($_GET['id']);

if ($customerInfo["id"] == "all") $response = $customer->getAllCustomers();
else $response = $customer->retrieveCustomer($customerInfo);

if ($response["ERROR"])
{
	echo "HUH";
	echo json_encode($response, JSON_PRETTY_PRINT);
}

//header('Content-Type: application/json');
//$data = json_encode($response, JSON_PRETTY_PRINT);
//print_r(json_decode($data, true));
?>
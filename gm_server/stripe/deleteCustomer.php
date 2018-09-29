<?php

require_once('/stripe_config.php');

include_once 'goaliemind/Customer.php';

if (!isset($_GET['id']))
{
	echo "No customer id found!";
	exit;
}

$customerInfo = array();
$customerInfo["id"] = trim($_GET['id']);

$customer = new Customer();
$response = $customer->deleteCustomer($customerInfo);

echo json_encode($response);
?>
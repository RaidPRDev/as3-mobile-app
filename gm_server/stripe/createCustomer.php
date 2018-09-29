<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../dbconfig.php';
require_once('stripe_config.php');
include_once 'goaliemind/Customer.php';
include_once 'goaliemind/Transaction.php';

if (isset($_GET['createcustomer']))
{
	/*
		var urlvars:URLVariables = new URLVariables(); 
		urlvars.createcustomer = true;
		urlvars.cusemail = userData.cusemail;
		urlvars.cusdesc = userData.cusdesc;
		urlvars.uname = userData.uname;
		urlvars.upass = userData.upass;
		urlvars.umail = userData.umail;
		urlvars.projectid = projectInfo.projectid;
	*/
	$uname = trim($_GET['uname']);
	$upass = trim($_GET['upass']);
	$umail = trim($_GET['umail']);
	$cusemail = trim($_GET['cusemail']);
	$cusdesc = utf8_decode(urldecode(trim($_GET['cusdesc'])));
	$projectid = trim($_GET['projectid']);
		
	if($user->login($uname,$umail,$upass))
	{
		$userid = $_SESSION['user_session'];
		
		$customerInfo = array();
		//$customerInfo["email"] = "fania4life@gmail.com";
		//$customerInfo["description"] = "GoalieMind Rafael Customer";
		$customerInfo["email"] = $cusemail;
		$customerInfo["description"] = $cusdesc;

		// $customerInfo["description"] =  array("userid" => $info["uid"],"paymentid" => $info["paymentid"]);

		$customer = new Customer();
		$response[] = $customer->createCustomer($customerInfo);

		$transaction = new Transaction($DB_con);
		$transactionInfo = array();
		$transactionInfo['userid'] = $userid;
		$transactionInfo['projectid'] = $projectid;
		$transactionInfo['type'] = 'create_customer';
		$transactionInfo['desc'] = 'Created the new stripe customer for the project.';
		$response[] = $transaction->createTransactionLog($transactionInfo);

		// header('Content-Type: application/json');
		// $data = json_encode($response, JSON_PRETTY_PRINT);
		// print_r(json_decode($data, true));

		echo json_encode($response);
	}
	else
	{
		$error[] = "Wrong Details!";
	}	
} 
?>
<?php
require_once '../dbconfig.php';
include_once 'class.payment.php';
require_once '../class.setting.php';

if(isset($_GET['login']))
{
	$uid = trim($_GET['uid']);
	$uname = trim($_GET['uname']);
	$umail = trim($_GET['email']);
	$upass = trim($_GET['pw']);
	$customerid = trim($_GET['customerid']);
	$paymentid = trim($_GET['paymentid']);
	$tokenid = trim($_GET['tokenid']);
	$cardid = trim($_GET['cardid']);
	$reqamount = trim($_GET['amount']);
	$currency = "usd";
	$projectid = trim($_GET['projectid']); //
	
	// Get a unique code use: uniqid();

	// Stripe works with cents.  Convert dollars to cents.
	//$amount = 24 * 100;  
	$amount = $reqamount * 100;  
	
	if($user->login($uname,$umail,$upass))
	{
		//$uid = $_SESSION['user_session'];
		$payment = new PAYMENT($DB_con);
		
		$paymentInfo = array();
		$paymentInfo["uid"] = $uid;
		$paymentInfo["customerid"] = $customerid;
		$paymentInfo["paymentid"] = $paymentid;
		$paymentInfo["tokenid"] = $tokenid;
		$paymentInfo["cardid"] = $cardid;
		$paymentInfo["currency"] = $currency;
		$paymentInfo["amount"] = $amount;
		$paymentInfo["email"] = $umail;
		
		// Try to retrieve new customer
		$customer = $payment->processPayment($paymentInfo, $projectid);
		
		$response["OK"] = "Success";
		echo json_encode($customer);
	}
	else
	{
		if (!$error) $error[] = "Wrong Details!";
		$response["ERROR"] = $error;
		echo json_encode($response);
	}	
}

?>
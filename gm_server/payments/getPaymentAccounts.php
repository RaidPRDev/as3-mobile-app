<?php

require_once '../dbconfig.php';

include_once 'class.payment.php';

if(isset($_POST['login']))
{
	$uid = trim($_POST['uid']);
	$uname = trim($_POST['uname']);
	$umail = trim($_POST['email']);
	$upass = trim($_POST['pw']);
	$customerid = trim($_POST['custid']);
	
	if($user->login($uname,$umail,$upass))
	{
		$response["OK"] = "User authenticated!";
		
		$payment = new PAYMENT($DB_con);
		
		$paymentsInfo = $payment->getPaymentList($uid);	
				
		$result = count($paymentsInfo);
				
		if ($result > 0)
		{
			$getInfo = json_encode($paymentsInfo);
			$response["OK"] = "User authenticated!";
			$response["paymentsInfo"] = $getInfo;
			
			echo json_encode($response);
		}
		else
		{
			if ($result == 0)
			{
				$response["OK"] = "User authenticated!";
			}
			else
			{
				// Error when retrieving card to current customer
				$error[] = "Something went wrong retrieving card info!";	
				$response["ERROR"] = $error;
			}
			
			echo json_encode($response);
		}
	}
	else
	{
		if (!$error) $error[] = "Wrong Details!";
		$response["ERROR"] = $error;
		echo json_encode($response);
	}	
}

?>
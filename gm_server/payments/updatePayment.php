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
	
	$accountType = trim($_POST['aType']);	// 2 == CREDIT CARD
	$cardName = trim($_POST['cardName']);
	$cardId = trim($_POST['cardid']);
	$cardExpMonth = trim($_POST['cardExpMonth']);
	$cardExpYear = trim($_POST['cardExpYear']);
	$cardCvc = trim($_POST['cardCvc']);
	$cardZip = trim($_POST['cardZip']);
	$dcard = trim($_POST['isDefault']);
	//
	$projectid = 1;
		
	$isDefaultCard = ($dcard == "true") ? true : false;
		
	if($user->login($uname,$umail,$upass))
	{
		$payment = new PAYMENT($DB_con);
		
		// Try to retrieve new customer
		$customer = $payment->retrieveCustomer($customerid);
		
		if ($customer["ERROR"])
		{
			// Error when trying to load a customer, stop processing!
			$error[] = $customer["ERROR"];
			$response["ERROR"] = $error;
			echo json_encode($response);
			exit;
		}
		
		$updateCardInfo['userid'] = $uid;
		$updateCardInfo['customerid'] = $customer->id;
		$updateCardInfo['typeid'] = $accountType;
		$updateCardInfo['cardid'] = $cardId;
		$updateCardInfo['name'] = $cardName;
		$updateCardInfo['exp_month'] = $cardExpMonth;
		$updateCardInfo['exp_year'] = $cardExpYear;
		$updateCardInfo['cvc'] = $cardCvc;
		$updateCardInfo['zip'] = $cardZip;	
		$updateCardInfo['isDefaultCard'] = $isDefaultCard;	
		
		// Update card
		$updateCustomerCard = $payment->updateCustomerCard($updateCardInfo, $projectid);	
		
		if ($updateCustomerCard["result"] == true)
		{
			// Now that we saved to Stripe, we will need to save/update to our server
			// Try to find the card in our current database
			$userPaymentCards = $payment->findUserPaymentCardByCardId($uid, $cardId);
		
			$result = count($userPaymentCards["cards"]);
			
			if ($result > 0)
			{
				// We found a card that matches, we will now update the server with new info
				$updateCardInfo['paymentid'] = $userPaymentCards["cards"][0]["id"];
				$updateCardInfo['number'] = $userPaymentCards["cards"][0]["number"];
				
				$updateUserPaymentCard = $payment->updateUserPaymentCard($updateCardInfo);
				
				if ($updateUserPaymentCard["result"] == true)
				{
					// User PaymentAccounts has been updated, now update default card and payment id to User Account
					$updateDefaultPayment = $user->updateUserPayment($uid, $customer->id, $updateCardInfo["paymentid"]);
					
					if ($updateDefaultPayment["result"] == true)
					{
						$response["OK"] = "Your card has been updated.";
						$response["paymentid"] = $updateCardInfo["paymentid"];
					}
					else $response["ERROR"] = $updateDefaultPayment["ERROR"];
				
				} 
				else $response["ERROR"] = "An error has occurred when updating your card. Please try again.";
			}
			else
			{
				$response["ERROR"] = "An error has occurred when updating your card. Please try again.";
			}

			echo json_encode($response);
		}
		else
		{
			// Error when adding card to current customer
			$error[] = $updateCustomer["ERROR"];	
			$response["ERROR"] = $error;
			echo json_encode($response);
		}
	}
	else
	{
		// Error when authenticating user
		if (!$error) $error[] = "Wrong Details!";
		$response["ERROR"] = $error;
		echo json_encode($response);
	}	
}

?>
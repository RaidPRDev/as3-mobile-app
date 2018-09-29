<?php
require_once '../dbconfig.php';

include_once '../payments/class.payment.php';

if(isset($_POST['deletestripecard']) && $_POST['deletestripecard'] == "true")
{
	$uname = trim($_POST['uname']);
	$umail = trim($_POST['email']);
	$upass = trim($_POST['pw']);
	$customerid = trim($_POST['customerid']);
	$cardid = trim($_POST['cardid']);
	$paymentid = trim($_POST['paymentid']);
	$projectid = 0; //
		
	if($user->login($uname,$umail,$upass))
	{
		$uid = $_SESSION['user_session'];
		
		$payment = new PAYMENT($DB_con);
		
		// Try to retrieve new customer
		$customer = $payment->retrieveCustomer($customerid);
		
		if ($customer["ERROR"])
		{
			// CustomerID does not exist
			// Error when accessing customer
			$error[] = $customer["ERROR"];	
			$response["ERROR"] = $error;
			echo json_encode($response);
			exit;
		}
		
		$cardInfo['customerid'] = $customerid;
		$cardInfo['cardid'] = $cardid;
		$cardInfo['paymentid'] = $paymentid;
		$deleteCard = $payment->deleteCustomerCard($cardInfo, $uid, $projectid);
		
		if ($deleteCard["result"] == true)
		{
			// Card has been removed from Stripe and Server
			// When removing a card, Stripe will automatically set an 'available' card as default
			$customer = $payment->retrieveCustomer($customerid);
			$nextAvailableCardId = null;
			$nextAvailablePaymentId = null;
			if ($customer->default_source != null)
			{
				// default_source is not null, which means we have a card available
				// Get new CardiD from default_source, we will use it after we remove card from our server
				$nextAvailableCardId = $customer->default_source;
			}
			
			// Lets check if we have another cardid available from Stripe	
			if ($nextAvailableCardId != null)
			{
				// We have a cardid available, lets get its paymentid and save to User
				$userPaymentCards = $payment->findUserPaymentCardByCardId($uid, $nextAvailableCardId);
				if ($userPaymentCards["result"] == true)
				{	
					$result = count($userPaymentCards["cards"]);
	
					if ($result > 0)
					{
						// We found a matche, now we have the User.paymentid
						// Now update User profile's default paymentid
						$nextAvailablePaymentId = $userPaymentCards["cards"][0]["id"];
					}
				}
			}
			else
			{
				// We do not have any available cards, lets now check if User has a PayPal account added
				
				$userPayPalInfo = $payment->findUserPayPalAccount($uid);
				if ($userPayPalInfo["result"] == true)
				{	
					$result = count($userPayPalInfo["paypal"]);
					if ($result > 0)
					{
						// We found a PayPal Account, get paymentid	
						$nextAvailablePaymentId = $userPayPalInfo["paypal"][0]["id"];
					}
				}
			}
		
		
			// Lets check if we have another available paymentid
			if ($nextAvailablePaymentId != null)
			{
				// Its available, lets update User with new paymentid as Default
				$updateUserDefaultPayment = $user->updateUserPayment($uid, $customer->id, $nextAvailablePaymentId);
				if ($updateUserDefaultPayment["result"] == true)
				{
					$response["OK"] = "Your card has been removed.";
					$response["paymentid"] = $nextAvailablePaymentId;
					echo json_encode($response);
				}
				else 
				{
					// Error on updating available paymentid
					$response["ERROR"] = $updateDefaultPayment["ERROR"];
					echo json_encode($response);
				}
			}
			else
			{
				// paymentid is not available, which means there are no other payment methods available
				// so we will just update the application
				$response["OK"] = "Your card has been removed.";
				$response["paymentid"] = 0;
				$response["updatedPaymentInfo"] = $user->updateUserPayment($uid, $customer->id, 0);
				$response["userInfo"] = $user->getUserInfo($uid)["userInfo"];
				
				echo json_encode($response);
			}
		}
		else
		{
			// Error on removing card
			$response["ERROR"] = $deleteCard["ERROR"];
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
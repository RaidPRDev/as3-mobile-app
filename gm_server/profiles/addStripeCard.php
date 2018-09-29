<?php
require_once '../dbconfig.php';

include_once '../payments/class.payment.php';
if(isset($_POST['addstripecard']) && $_POST['addstripecard']=="true")
{
	//$uid = trim($_POST['uid']);
	$uname = trim($_POST['uname']);
	$umail = trim($_POST['email']);
	$upass = trim($_POST['pw']);
	//$accountType = trim($_POST['accountType']);
	
	//$customerid = trim($_POST['customerid']);
	$cardName = utf8_decode(urldecode(trim($_POST['cardName'])));
	$cardNum = trim($_POST['cardNum']);
	$cardExpMonth = trim($_POST['cardExpMonth']);
	$cardExpYear = trim($_POST['cardExpYear']);
	$cardCvc = trim($_POST['cardCvc']);
	$cardZip = trim($_POST['cardZip']);
	$dcard = trim($_POST['isDefault']);
	$projectid = 0; //
		
	$isDefaultCard = ($dcard == "true") ? true : false;
		
	if($user->login($uname,$umail,$upass))
	{
		$uid = $_SESSION['user_session'];
		$customerid = $_SESSION['customerid'];
		$accountType = $_SESSION['accountType'];

		$payment = new PAYMENT($DB_con);
		
		// Try to retrieve stripe customer
		$customer = $payment->retrieveCustomer($customerid);
		
		if ($customer["ERROR"])
		{
			// CustomerID does not exist, we will need to create one in order to add a Card
			$customer = $payment->createCustomer($uid, $umail, $projectid);
			
			if ($customer["ERROR"])
			{
				// Error when creating a new customer
				$error[] = "Something went wrong adding new card!";
				$response["ERROR"] = $error;
				echo json_encode($response);
				exit;
			}
		}
		
		// Create Card Info Object from Application Data
		$cardInfo = array();
		$cardInfo["name"] = $cardName;
		$cardInfo["number"] = $cardNum;
		$cardInfo["exp_month"] = $cardExpMonth;
		$cardInfo["exp_year"] = $cardExpYear;
		$cardInfo["cvc"] = $cardCvc;
		$cardInfo["zip"] = $cardZip;

		// Create Token for Card Info
		$token = new Token();
		$tokenInfo = $token->createCardToken($cardInfo);

		// Check if Card Token created successfully
		if (!$tokenInfo["ERROR"])	
		{
			// Loging the created token in db start
			$transaction = new Transaction($DB_con);
			$transactionInfo = array();
			$transactionInfo['userid'] = $uid;
			$transactionInfo['projectid'] = $projectid;
			$transactionInfo['type'] = 'create_token';
			$transactionInfo['desc'] = 'Created Token for payment Card Info.';
			$transaction->createTransactionLog($transactionInfo);
			// Loging the created token in db end
		
			$cardTokenInfo = $tokenInfo->card;
				
			$updateCardInfo['userid'] = $uid;
			$updateCardInfo['customerid'] = $customer->id;
			$updateCardInfo['typeid'] = $accountType;
			$updateCardInfo['tokenid'] = $tokenInfo->id;
			$updateCardInfo['cardid'] = $cardTokenInfo->id;
			$updateCardInfo['fingerprint'] = $cardTokenInfo->fingerprint;
			$updateCardInfo['name'] = $cardInfo["name"];
			$updateCardInfo['number'] = $cardTokenInfo->last4;
			$updateCardInfo['cardtype'] = $cardTokenInfo->brand;
			$updateCardInfo['exp_month'] = $cardTokenInfo->exp_month;
			$updateCardInfo['exp_year'] = $cardTokenInfo->exp_year;
			$updateCardInfo['cvc'] = $cardCvc;
			$updateCardInfo['zip'] = $cardZip;	
			$updateCardInfo['isDefaultCard'] = $isDefaultCard;	
			
			// Before we update the Stripe and Goalie Server
			// We need to make sure this card is not a duplicate
			// We do this by using the fingerprint data
			
			$findStripeCard = $payment->findStripeCardByFingerPrint($customer->id, $cardTokenInfo->fingerprint);
			
			if ($findStripeCard["result"] == true)
			{
				// Replace new Card ID with current one found
				$updateCardInfo['cardid'] = $findStripeCard["cardid"];
				
				// Update card
				$updateCustomer = $payment->updateCustomerCard($updateCardInfo, $projectid);	
			}
			else
			{
				// Its a brand new card
				$updateCustomer = $payment->addCustomerCard($updateCardInfo, $projectid);		
			}
			
			if (!$updateCustomer["ERROR"])
			{
				// Now that we saved to Stripe, we will need to save/update to our server
				// Try to find the card in our current database
				$userPaymentCards = $payment->findUserPaymentCardByFingerPrint($uid, $cardTokenInfo->fingerprint);
			
				$result = count($userPaymentCards["cards"]);
				
				if ($result > 0)
				{
					// We found a card that matches, we will now update the server with new info
					$updateCardInfo['paymentid'] = $userPaymentCards["cards"][0]["id"];
					
					$updateUserPaymentCard = $payment->updateUserPaymentCard($updateCardInfo);
					
					if ($updateUserPaymentCard["result"] == true)
					{
						$response["OK"] = "Your card has been updated.";
					} 
					else $response["ERROR"] = "An error has occurred when updating your card. Please try again.";
				}
				else
				{
					// No cards found, we will make a add one and save
					$updateUserPaymentCard = $payment->addUserPaymentCard($updateCardInfo);	
					
					if ($updateUserPaymentCard["result"] == true)
					{
						$response["OK"] = "Your card has been added.";
					}
					else $response["ERROR"] = $updateUserPaymentCard["ERROR"];
				}

				//Update the default card
				if (!$updateUserPaymentCard["ERROR"] && $isDefaultCard == true)
				{
					$updateDefaultPayment = $user->updateUserPayment($uid, $customer->id, $updateUserPaymentCard["paymentid"]);
					if ($updateDefaultPayment["result"] == true)
					{
						$response["customerid"] = $customer->id;
						$response["paymentid"] = $updateUserPaymentCard["paymentid"];
					}
					else $response["ERROR"] = "An error has occurred when updating your default card.";
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
			// Error when creating Card/Token
			$error[] = $tokenInfo["ERROR"];	
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

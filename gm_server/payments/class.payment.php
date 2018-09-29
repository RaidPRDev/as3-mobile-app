<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
// echo $_SERVER['DOCUMENT_ROOT'];

include_once $ROOT_PATH . '/stripe/stripe_config.php';
include_once $ROOT_PATH . '/stripe/goaliemind/Customer.php';
include_once $ROOT_PATH . '/stripe/goaliemind/Token.php';
include_once $ROOT_PATH . '/stripe/goaliemind/Card.php';
include_once $ROOT_PATH . '/stripe/goaliemind/Transaction.php';

/*require_once('../stripe/stripe_config.php');

include_once '../stripe/goaliemind/Customer.php';
include_once '../stripe/goaliemind/Token.php';
include_once '../stripe/goaliemind/Card.php';*/

class PAYMENT
{
	private $db;
		
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	//////////////////////////////////////
	// PAYMENT CREATION
	//////////////////////////////////////
	
	// $payment = new PAYMENT($DB_con);
	
	public function createCustomer($userid, $useremail, $projectid)
	{
		$customerInfo = array();
		$customerInfo["email"] = $useremail;
		$customerInfo["description"] = "GoalieMind USERID: " . $userid;
		
		$customer = new Customer();

		$transaction = new Transaction($this->db);
		$transactionInfo = array();
		$transactionInfo['userid'] = $userid;
		$transactionInfo['projectid'] = $projectid;
		$transactionInfo['type'] = 'create_customer';
		$transactionInfo['desc'] = 'Created the new stripe customer for adding the payment.';
		$transaction->createTransactionLog($transactionInfo);
		
		return $customer->createCustomer($customerInfo);
	}

	public function retrieveCustomer($customerId)
	{
		$customer = new Customer();

		$response = $customer->retrieveCustomer(array("id"=>$customerId));

		return $response;
	}

	public function updateCustomerCard($info, $projectid)
	{
		$customer = new Customer();

		$response = $customer->updateCustomerCard($info);

		$transaction = new Transaction($this->db);
		$transactionInfo = array();
		$transactionInfo['userid'] = $info['userid'];
		$transactionInfo['projectid'] = $projectid;
		$transactionInfo['type'] = 'update_customer_card';
		$transactionInfo['desc'] = 'Updated the customer payment card.';
		$transaction->createTransactionLog($transactionInfo);

		return $response;
	}
	
	public function addCustomerCard($info, $projectid)
	{
		$customer = new Customer();

		$response = $customer->addCustomerCard($info);

		$transaction = new Transaction($this->db);
		$transactionInfo = array();
		$transactionInfo['userid'] = $info['userid'];
		$transactionInfo['projectid'] = $projectid;
		$transactionInfo['type'] = 'create_customer_card';
		$transactionInfo['desc'] = 'Created the customer payment card.';
		$transaction->createTransactionLog($transactionInfo);

		return $response;
	}
	
	public function deleteCustomerCard($info, $userid, $projectid)
	{
		$customer = new Customer();
		$response = $customer->deleteCustomerCard($info);
		if ($response["result"] == true)
		{
			$paymentInfo["paymentid"] = $info["paymentid"];
			$response = $this->removeUserPaymentCard($paymentInfo);

			$transaction = new Transaction($this->db);
			$transactionInfo = array();
			$transactionInfo['userid'] = $userid;
			$transactionInfo['projectid'] = $projectid;
			$transactionInfo['type'] = 'delete_customer_card';
			$transactionInfo['desc'] = 'Deleted the customer payment card.';
			$transaction->createTransactionLog($transactionInfo);
		}

		return $response;
	}
	
	public function findStripeCardByFingerPrint($customerId, $fingerprint)
	{
		$customer = new Customer();
		$customerData = $customer->retrieveCustomer(array("id"=>$customerId));
		$getCardList = $customerData->sources->data;
		$foundCard = false;
		$cardid = "";
		
		foreach ($getCardList as $card) 
		{
  			if ($fingerprint == $card->fingerprint)
			{
				$foundCard = true;
				$cardid = $card->id;
				
				break;	
			}
		}
		
		if ($foundCard == true)
		{
			$response["result"] = true;
			$response["cardid"] = $cardid;
		}
		else 
		{
			$response["result"] = false;
		}
		
		return $response;
	}
	
	public function findUserPaymentCardByCardId($userid, $cardid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$fetchCards = $this->db->prepare("SELECT 
					payment_accounts.id, 
					payment_accounts.userid, 
					payment_accounts.typeid, 
					payment_accounts.tokenid, 
					payment_accounts.cardid, 
					payment_accounts.fingerprint,
					payment_accounts.name, 
					payment_accounts.number 
				FROM payment_accounts 
				WHERE payment_accounts.userid = :userid 
				AND payment_accounts.cardid = :cardid 
				ORDER BY payment_accounts.id DESC");
				
			$fetchCards->bindValue(':userid', $userid, PDO::PARAM_INT);
			$fetchCards->bindValue(':cardid', $cardid, PDO::PARAM_STR);
			
			if ($fetchCards->execute())
			{
				$response["result"] = true;
				$response["cards"] = $fetchCards->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchCards->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}	
	
	public function findUserPaymentCardByFingerPrint($userid, $fingerprint)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$fetchCards = $this->db->prepare("SELECT 
					payment_accounts.id, 
					payment_accounts.userid, 
					payment_accounts.typeid, 
					payment_accounts.tokenid, 
					payment_accounts.fingerprint
				FROM payment_accounts 
				WHERE payment_accounts.userid = :userid 
				AND payment_accounts.fingerprint = :fingerprint 
				ORDER BY payment_accounts.id DESC");
				
			$fetchCards->bindValue(':userid', $userid, PDO::PARAM_INT);
			$fetchCards->bindValue(':fingerprint', $fingerprint, PDO::PARAM_STR);
			
			if ($fetchCards->execute())
			{
				$response["result"] = true;
				$response["cards"] = $fetchCards->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchCards->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}	
	
	public function findUserPayPalAccount($userid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$fetchCards = $this->db->prepare("SELECT 
					payment_accounts.id, 
					payment_accounts.userid, 
					payment_accounts.typeid, 
					payment_accounts.tokenid, 
					payment_accounts.cardid,
					payment_accounts.fingerprint,
					payment_accounts.name,
					payment_accounts.number,
					payment_accounts.cardtype,
					payment_accounts.exp_month,
					payment_accounts.exp_year,
					payment_accounts.cvc,
					payment_accounts.zip,
					payment_accounts.pp_email,
					payment_accounts.pp_password
				FROM payment_accounts 
				WHERE payment_accounts.userid = :userid 
				AND payment_accounts.typeid = :typeid
				ORDER BY payment_accounts.id DESC");
				
			$fetchCards->bindValue(':userid', $userid, PDO::PARAM_INT);
			$fetchCards->bindValue(':typeid', 1, PDO::PARAM_INT);
			
			if ($fetchCards->execute())
			{
				$response["result"] = true;
				$response["paypal"] = $fetchCards->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchCards->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}	
	}
	
	public function getPaymentList($userid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$fetchCards = $this->db->prepare("SELECT 
					payment_accounts.id, 
					payment_accounts.userid, 
					payment_accounts.typeid, 
					payment_accounts.tokenid, 
					payment_accounts.cardid,
					payment_accounts.fingerprint,
					payment_accounts.name,
					payment_accounts.number,
					payment_accounts.cardtype,
					payment_accounts.exp_month,
					payment_accounts.exp_year,
					payment_accounts.cvc,
					payment_accounts.zip
				FROM payment_accounts 
				WHERE payment_accounts.userid = :userid 
				ORDER BY payment_accounts.id DESC");
				
			$fetchCards->bindValue(':userid', $userid, PDO::PARAM_INT);
			$fetchCards->execute() or die(print_r($fetchCards->errorInfo()));
			
			return $fetchCards->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}	
	
	public function addUserPaymentCard($info)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("INSERT INTO payment_accounts(userid,typeid,tokenid,cardid,fingerprint,name,number,cardtype,exp_month,exp_year,cvc,zip) 
							VALUES(:uid,:typeid,:tkid,:cardid,:fp,:name,:number,:cardtype,:mm,:yy,:cvc,:zip)");

			$stmt->bindValue(':uid', $info["userid"], PDO::PARAM_INT);
			$stmt->bindValue(':typeid', $info["typeid"], PDO::PARAM_INT);
			$stmt->bindValue(':tkid', $info["tokenid"], PDO::PARAM_STR);
			$stmt->bindValue(':cardid', $info["cardid"], PDO::PARAM_STR);
			$stmt->bindValue(':fp', $info["fingerprint"], PDO::PARAM_STR);
			$stmt->bindValue(':name', $info["name"], PDO::PARAM_STR);
			$stmt->bindValue(':number', $info["number"], PDO::PARAM_STR);
			$stmt->bindValue(':cardtype', $info["cardtype"], PDO::PARAM_STR);
			$stmt->bindValue(':mm', $info["exp_month"], PDO::PARAM_STR);
			$stmt->bindValue(':yy', $info["exp_year"], PDO::PARAM_STR);
			$stmt->bindValue(':cvc', $info["cvc"], PDO::PARAM_STR);
			$stmt->bindValue(':zip', $info["zip"], PDO::PARAM_STR);
			
			if ($stmt->execute())
			{
				$response["result"] = true;
				$response["paymentid"] = $this->db->lastInsertId();
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $stmt->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}	
		
	public function updateUserPaymentCard($info)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("UPDATE payment_accounts SET 
					payment_accounts.typeid = :typeid, 
					payment_accounts.cardid = :cardid, 
					payment_accounts.name = :name, 
					payment_accounts.exp_month = :exp_month,
					payment_accounts.exp_year = :exp_year, 
					payment_accounts.cvc = :cvc,
					payment_accounts.zip = :zip
				WHERE payment_accounts.id = :id");
				
			$stmt->bindValue(':typeid', $info["typeid"], PDO::PARAM_INT);
			$stmt->bindValue(':cardid', $info["cardid"], PDO::PARAM_STR);
			$stmt->bindValue(':name', $info["name"], PDO::PARAM_STR);
			$stmt->bindValue(':exp_month', $info["exp_month"], PDO::PARAM_STR);
			$stmt->bindValue(':exp_year', $info["exp_year"], PDO::PARAM_STR);
			$stmt->bindValue(':cvc', $info["cvc"], PDO::PARAM_STR);
			$stmt->bindValue(':zip', $info["zip"], PDO::PARAM_STR);
			$stmt->bindValue(':id', $info["paymentid"], PDO::PARAM_INT);
			
			if ($stmt->execute())
			{
				$response["result"] = true;
				$response["paymentid"] = $info["paymentid"];
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $stmt->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}	
	
	public function removeUserPaymentCard($info)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("DELETE FROM payment_accounts   
				WHERE payment_accounts.id = :id");
			
			$stmt->bindValue(':id', $info["paymentid"], PDO::PARAM_INT);
			
			if ($stmt->execute())
			{
				$response["result"] = true;
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $stmt->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}	
	
	public function processPayment($info, $projectid, $fundamount)
	{
		$card = new Card();
		
		//Charge Card
		$response = $card->chargeCard($info);

		//Log Charge Card in DB
		if(!isset($response["error"]))
		{
			$transaction = new Transaction($this->db);
			$transactionInfo = array();
			$transactionInfo['userid'] = $info['uid'];
			$transactionInfo['projectid'] = $projectid;
			$transactionInfo['type'] = 'charge_card';
			$transactionInfo['desc'] = 'Charged card and captured it for processing of payment';
			$transactionInfo['amount'] = $fundamount;
			$transaction->createTransactionLog($transactionInfo);
		}
		
		return $response;
	}

	// Add User Process Payment in DB start
	public function addUserProcessPaymentInDB($paymentInfoDB)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("INSERT INTO user_payments(userid,paymentid,transactionid,chargeid,amount,currency) 
							VALUES(:userid,:paymentid,:transactionid,:chargeid,:amount,:currency)");

			$stmt->bindValue(':userid', $paymentInfoDB["userid"], PDO::PARAM_INT);
			$stmt->bindValue(':paymentid', $paymentInfoDB["paymentid"], PDO::PARAM_INT);
			$stmt->bindValue(':transactionid', $paymentInfoDB["transactionid"], PDO::PARAM_STR);
			$stmt->bindValue(':chargeid', $paymentInfoDB["chargeid"], PDO::PARAM_STR);
			$stmt->bindValue(':amount', $paymentInfoDB["amount"], PDO::PARAM_STR);
			$stmt->bindValue(':currency', $paymentInfoDB["currency"], PDO::PARAM_STR);

			if ($stmt->execute())
			{
				$response["user_paymentid"] = $this->db->lastInsertId();
			}
			else 
			{
				$response["ERROR"] = $stmt->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}			
	}
	// Add User Process Payment in DB end
}
?>
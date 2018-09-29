<?php
class WALLET
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	public function updateUserWallet($userid, $amount)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$available_bal = $this->retrieveUserWallet($userid);
			$new_bal = $available_bal+$amount;
			
			$stmt = $this->db->prepare("UPDATE users SET wallet = :wallet WHERE id = :userid");

			$stmt->bindparam(":wallet", $new_bal, PDO::PARAM_STR);
			$stmt->bindparam(":userid", $userid, PDO::PARAM_INT);

			$stmt->execute() or die(print_r($stmt->errorInfo()));

			if($stmt->rowCount() > 0)
			{
				return 1;   //true
			}
			else
			{
				return 0;  //false
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}

	public function deductFromUserWallet($userid, $amount)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("UPDATE users SET wallet = wallet - :amount WHERE id = :userid");

			$stmt->bindValue(":amount", $amount, PDO::PARAM_STR);
			$stmt->bindValue(":userid", $userid, PDO::PARAM_INT);

			$stmt->execute() or die(print_r($stmt->errorInfo()));

			if($stmt->rowCount() > 0)
			{
				$response["result"] = true;
			}
			else
			{
				$response["result"] = false;
			}

			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response; 
		}	
	}

	public function deductFromMultipleUserWallet($userids_arr, $amount)
	{
		try
		{
			$userids = implode(",",$userids_arr);

			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("UPDATE users 
					SET wallet = wallet - :amount 
					WHERE FIND_IN_SET(id, :userids)");

			$stmt->bindValue(":amount", $amount, PDO::PARAM_STR);
			$stmt->bindValue(":userids", $userids, PDO::PARAM_INT);

			if($stmt->execute())
			{
				$response["result"] = true;   //true
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

	public function retrieveUserWallet($userid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("SELECT wallet FROM users where id=:userid");
			$stmt->bindparam(":userid", $userid, PDO::PARAM_INT);

			$stmt->execute() or die(print_r($stmt->errorInfo()));
			
			$response = $stmt->fetchAll(PDO::FETCH_COLUMN , 0);

			return $response[0];
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}

	public function checkUserWallet($userid, $amount)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("SELECT wallet FROM users WHERE id=:userid AND wallet>=:amount");
			$stmt->bindValue(":userid", $userid, PDO::PARAM_INT);
			$stmt->bindValue(":amount", $amount, PDO::PARAM_STR);

			$stmt->execute() or die(print_r($stmt->errorInfo()));
			if($stmt->rowCount() > 0)
			{
				$response["result"] = true;
			}
			else
			{
				$response["result"] = false;
			}

			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}
	}

	public function transferMoney($uid, $amount, $transfer_uid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("SELECT wallet FROM users WHERE id=:userid AND wallet>=:amount");
			$stmt->bindparam(":userid", $userid, PDO::PARAM_INT);
			$stmt->bindparam(":amount", $amount, PDO::PARAM_STR);

			$stmt->execute() or die(print_r($stmt->errorInfo()));
			if($stmt->rowCount() > 0)
			{
				return 1;   //true
			}
			else
			{
				return 0;  //false
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	//Begin Save Wallet Deduction Transaction
	public function saveWalletDeduction($amount, $sender_id, $receiver_id, $deduction_type)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("INSERT INTO wallet_deduction(amount, sender_id, reciever_id, deduction_type) 
 							VALUES(:amount, :sender_id, :receiver_id, :deduction_type)");


			$stmt->bindValue(":amount", $amount);
			$stmt->bindparam(":sender_id", $sender_id);
			$stmt->bindparam(":receiver_id", $receiver_id);
			$stmt->bindparam(":deduction_type", $deduction_type);

			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	//End Save Wallet Deduction Transaction
}
?>

<?php
/*
	@reference https://stripe.com/docs/api/php
*/
class Transaction
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}

	public function createTransactionLog($transactionInfo)
	{
		try
		{
			$userid = $transactionInfo['userid'];
			if(isset($transactionInfo['projectid']))
			{
				$projectid = $transactionInfo['projectid'];
			}
			else
			{
				$projectid = 0;
			}
			$type = $transactionInfo['type'];
			$desc = $transactionInfo['desc'];

			if(isset($transactionInfo['amount']))
			{
				$amount = $transactionInfo['amount'];
			}
			else
			{
				$amount = 0;
			}

			if(isset($transactionInfo['object_userid']))
			{
				$object_userid = $transactionInfo['object_userid'];
			}
			else
			{
				$object_userid = 0;
			}

			$stmt = $this->db->prepare("INSERT INTO transaction(user_id,project_id,type,description,amount,object_userid) 
							VALUES(:user_id, :project_id, :type, :description, :amount, :object_userid)");
												  
			$stmt->bindparam(":user_id", $userid);
			$stmt->bindparam(":project_id", $projectid);
			$stmt->bindparam(":type", $type);										  
			$stmt->bindparam(":description", $desc);										  
			$stmt->bindparam(":amount", $amount);										  
			$stmt->bindparam(":object_userid", $object_userid);										  
			
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(Exception $e)
		{
			$response["result"] = false;
			$response["ERROR"] = $e->getMessage();
		}

		return $response;
	}

	public function createMultiTransactionLog($transactionInfo)
	{
		try
		{
			if(isset($transactionInfo['projectid']))
			{
				$projectid = $transactionInfo['projectid'];
			}
			else
			{
				$projectid = 0;
			}
			$type = $transactionInfo['type'];
			$desc = $transactionInfo['desc'];

			if(isset($transactionInfo['amount']))
			{
				$amount = $transactionInfo['amount'];
			}
			else
			{
				$amount = 0;
			}

			if(isset($transactionInfo['object_userid']))
			{
				$object_userid = $transactionInfo['object_userid'];
			}
			else
			{
				$object_userid = 0;
			}

			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work
			$insertData = array(); 
			foreach ($transactionInfo['userid'] as $row) $insertData[] = [$row, $projectid, $type, $desc, $amount, $object_userid];
					
			$stmtAdd = $this->multiPrepare('INSERT INTO transaction(user_id,project_id,type,description,amount,object_userid)', $insertData);
					
			$bindArray = array();
			array_walk_recursive($insertData, function($item) use (&$bindArray) { $bindArray[] = $item; });
					
			if ($stmtAdd->execute($bindArray))
			{
				$response["result"] = true;
			}
			else
			{
				$response["result"] = false;
			}				
		}
		catch(Exception $e)
		{
			$response["result"] = false;
			$response["ERROR"] = $e->getMessage();
		}

		return $response;
	}

	// Reference: https://www.daniweb.com/programming/web-development/code/495371/insert-multiple-records-with-pdo-prepared-statement
	public function multiPrepare($sql, $data)
    {
        $rows = count($data);
        $cols = count($data[0]);
        $rowString = '(' . rtrim(str_repeat('?,', $cols), ',') . '),';
        $valString = rtrim(str_repeat($rowString, $rows), ',');
        return $this->db->prepare($sql . ' VALUES ' . $valString);
    }
} 

?>
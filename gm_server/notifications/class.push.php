<?php
class PUSHNOTIFY
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	public function updatePush($uid, $oneSignalUserId, $pushToken)
	{
		$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 
		
		// Check if UID already exists
		
		$pushId = $this->getPushId($uid);
		if ($pushId > 0)
		{
			// Push ID exists, lets update push data to it
			$stmt = $this->db->prepare("UPDATE user_pushids SET oneSignalUserId=:oneSignalUserId, pushToken=:pushToken WHERE id=:pushId");
			$stmt->bindparam(":oneSignalUserId", $oneSignalUserId, PDO::PARAM_STR);
			$stmt->bindparam(":pushToken", $pushToken, PDO::PARAM_STR);
			$stmt->bindparam(":pushId", $pushId, PDO::PARAM_INT);
			
			if ($stmt->execute())
			{
				$response['result'] = true;
				$response['pushId'] = $pushId;
			}
			else
			{
				$response['result'] = false;
			}
		}
		else
		{
			// Push ID does not exists, lets create a new field
			$stmt = $this->db->prepare("INSERT INTO user_pushids(userid,oneSignalUserId,pushToken) VALUES(:userid, :oneSignalUserId, :pushToken)");
			$stmt->bindparam(":userid", $uid, PDO::PARAM_INT);
			$stmt->bindparam(":oneSignalUserId", $oneSignalUserId, PDO::PARAM_STR);
			$stmt->bindparam(":pushToken", $pushToken, PDO::PARAM_STR);
			
			if ($stmt->execute())
			{
				$response['result'] = true;
				$response['pushId'] = $this->db->lastInsertId();
			}
			else
			{
				$response['result'] = false;
			}
		}
		
		return $response;
	}
	
	private function getPushId($uid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 
			$stmt = $this->db->prepare("SELECT * FROM user_pushids WHERE userid = :uid");
			$stmt->bindparam(":uid", $uid, PDO::PARAM_INT);
			
			if ($stmt->execute())
			{
				
				$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
				if (count($results) > 0) $response = $results[0]['id'];
				else $response = -1;
			}
			else 
			{
				$response = -1;
			}
		}
		catch(PDOException $e)
		{
			$response = $e->getMessage();
		}	
		
		return $response;
	}
	
	public function removePush($uid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("DELETE FROM user_pushids WHERE userid = :uid");
			
			$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
			
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
}
?>
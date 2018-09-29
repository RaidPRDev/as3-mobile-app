<?php
class USER_SETTINGS
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	public function getUserSettingsList($userid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$fetchSettings = $this->db->prepare("SELECT * FROM user_settings WHERE userid = :userid");
				
			$fetchSettings->bindValue(':userid', $userid, PDO::PARAM_INT);
				
			if ($fetchSettings->execute())
			{
				$response["user_settings"] = $fetchSettings->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchSettings->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["result"] = false;
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}

	public function addDefaultUserSettings($userid)
	{
		$info = [];
		$info["userid"] = $userid;
		$info["enableFunding"] = 1;
		$info["enableNotify"] = 1;
		$info["enablePushNotify"] = 0;
		$info["enableAddFriend"] = 1;
		$info["maxFund"] = -1;
		
		try
		{
			$stmt = $this->db->prepare("INSERT INTO user_settings(userid,enableFunding,enableNotify,enablePushNotify,enableAddFriend,maxFund) 
							VALUES(:userid, :enableFunding, :enableNotify, :enablePushNotify, :enableAddFriend, :maxFund)");
			
			$stmt->bindparam(":userid", $info['userid'], PDO::PARAM_INT);
			$stmt->bindparam(":enableFunding", $info['enableFunding'], PDO::PARAM_INT);
			$stmt->bindparam(":enableNotify", $info['enableNotify'], PDO::PARAM_INT);
			$stmt->bindparam(":enablePushNotify", $info['enablePushNotify'], PDO::PARAM_INT);
			$stmt->bindparam(":enableAddFriend", $info['enableAddFriend'], PDO::PARAM_INT);
			$stmt->bindparam(":maxFund", $info['maxFund'], PDO::PARAM_INT);
			
			if ($stmt->execute())
			{
				$response["result"] = true;
				$response['user_settings_id'] = $this->db->lastInsertId();
				$response['enableFunding'] = $info['enableFunding'];
				$response['enableNotify'] = $info['enableNotify'];
				$response['enablePushNotify'] = $info['enablePushNotify'];
				$response['enableAddFriend'] = $info['enableAddFriend'];
				$response['maxFund'] = $info['maxFund'];
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
	
	public function updateUserSettings($info)
	{
		try
		{
			$stmt = $this->db->prepare("UPDATE user_settings SET 
				enableFunding=:enableFunding, 
				enableNotify=:enableNotify, 
				enablePushNotify=:enablePushNotify, 
				enableAddFriend=:enableAddFriend, 
				maxFund=:maxFund  
				WHERE userid=:userid");
												  
			$stmt->bindparam(":enableFunding", $info['enableFunding']);
			$stmt->bindparam(":enableNotify", $info['enableNotify']);
			$stmt->bindparam(":enablePushNotify", $info['enablePushNotify']);
			$stmt->bindparam(":enableAddFriend", $info['enableAddFriend']);
			$stmt->bindparam(":maxFund", $info['maxFund']);
			$stmt->bindparam(":userid", $info['userid']);
				
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
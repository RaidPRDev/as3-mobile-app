<?php
class FRIENDS
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	//////////////////////////////////////
	// FRIENDS CREATION
	//////////////////////////////////////
	
	public function getFriendsList($userid, $skip = 0, $max = 5)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$fetchFriends = $this->db->prepare("SELECT 
					friends.id, 
					friends.friendid AS userid,
					friends.authorizeid,
					friends.isAuthorized,
					users.username,
					users.accountType,
					users.parentid,
					users.gender,
					user_avatar.thumb
				FROM friends 
				INNER JOIN users ON friends.friendid = users.id
				INNER JOIN user_avatar ON friends.friendid = user_avatar.userid
				WHERE friends.userid = :userid 
				ORDER BY users.username ASC LIMIT :skip, :max");
				
			$fetchFriends->bindValue(':userid', $userid, PDO::PARAM_INT);

			if(isset($skip)) $fetchFriends->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			else $fetchFriends->bindValue(':skip', 0, PDO::PARAM_INT);  
	
			$fetchFriends->bindValue(':max', $max, PDO::PARAM_INT);
						
			if ($fetchFriends->execute())
			{
				$response["result"] = true;
				$response["friends"] = $fetchFriends->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchFriends->errorInfo();
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
	
	public function fetchUserTotalFriendsByUserId($userid, $skip = 0, $max = 5)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$fetchFriends = $this->db->prepare("SELECT 
					friends.id, 
					friends.friendid AS userid
				FROM friends 
				WHERE friends.userid = :userid");
				
			$fetchFriends->bindValue(':userid', $userid, PDO::PARAM_INT);

			if ($fetchFriends->execute())
			{
				$response["result"] = true;
				$response["friendsTotal"] = count($fetchFriends->fetchAll(PDO::FETCH_ASSOC));;
				
				// $len = count($fetchFriends->fetchAll(PDO::FETCH_ASSOC));
				// echo "COUNT: " . $len;
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchFriends->errorInfo();
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

	public function getFriendsUserIDList($userid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$fetchFriendsUserID = $this->db->prepare("SELECT 
					users.id, 
					users.username
				FROM friends 
				INNER JOIN users ON friends.friendid = users.id
				WHERE friends.userid = :userid 
				ORDER BY users.username ASC");
				
			$fetchFriendsUserID->bindValue(':userid', $userid, PDO::PARAM_INT);
				
			if ($fetchFriendsUserID->execute())
			{
				$response["result"] = true;
				$response["friends"] = $fetchFriendsUserID->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchFriendsUserID->errorInfo();
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
	
	public function addFriend($uid, $friendUserId, $friendUserName, $friendUserIdEmail, $isAuthorized)
	{
		try
		{
			$stmt = $this->db->prepare("INSERT INTO friends(userid,friendid,authorizeid,isAuthorized) 
							VALUES(:userid, :friendid, :authorizeid, :isAuthorized)");
			
			$timeAdded = round(microtime(true) * 1000);
			$authorizeid = MD5($timeAdded);
			$isAuthorized = $isAuthorized;
			
			$stmt->bindparam(":userid", $uid, PDO::PARAM_INT);
			$stmt->bindparam(":friendid", $friendUserId, PDO::PARAM_INT);
			$stmt->bindparam(":authorizeid", $authorizeid, PDO::PARAM_STR);
			$stmt->bindparam(":isAuthorized", $isAuthorized, PDO::PARAM_INT);
			
			if ($stmt->execute())
			{
				$response["result"] = true;
				$response['newfriendid'] = $this->db->lastInsertId();
				$response['friendUserId'] = $friendUserId;
				$response['friendUserName'] = $friendUserName;
				$response['friendUserIdEmail'] = $friendUserIdEmail;
				$response['authorizeid'] = $authorizeid;
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
	
	public function isFriendAdded($uid, $friendid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$checkValidation = $this->db->prepare("SELECT 
					friends.userid,
					friends.friendid,
					friends.isAuthorized
				FROM friends 
				WHERE userid = :userid AND friendid = :friendid");
				
			$checkValidation->bindValue(':userid', $uid);
			$checkValidation->bindValue(':friendid', $friendid);

			if ($checkValidation->execute())
			{
				$result = $checkValidation->fetchAll(PDO::FETCH_ASSOC);
				
				if (count($result) > 0) 
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else 
			{
				return false;
			}
		}
		catch(PDOException $e)
		{
			return false;
		}	
	}
	
	public function hasBeenValidated($validateId)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$checkValidation = $this->db->prepare("SELECT 
					friends.userid,
					friends.friendid,
					friends.isAuthorized
				FROM friends 
				WHERE authorizeid = :authorizeid");
				
			$checkValidation->bindValue(':authorizeid', $validateId);

			if ($checkValidation->execute())
			{
				$result = $checkValidation->fetchAll(PDO::FETCH_ASSOC);
				
				if (count($result) > 0) 
				{
					if ($result[0]["isAuthorized"] == 1)
					{
						$response["result"] = true;
						$response["isAuthorized"] = $result[0]["isAuthorized"];
						$response["userid"] = $result[0]["userid"];
						$response["friendid"] = $result[0]["friendid"];
					}
					else
					{
						$response["result"] = false;
						$response["isAuthorized"] = $result[0]["isAuthorized"];
						$response["userid"] = $result[0]["userid"];
						$response["friendid"] = $result[0]["friendid"];
					}
				}
				else
				{
					$response["result"] = false;
					$response["ERROR"] = "No users found.";
				}
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $checkValidation->errorInfo();
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
	
	public function validateFriend($isAuthorized, $authorizeid)
	{
		try
		{
			$stmt = $this->db->prepare("UPDATE friends SET isAuthorized=:isAuthorized WHERE authorizeid=:authorizeid");
												  
			$stmt->bindparam(":isAuthorized", $isAuthorized);
			$stmt->bindparam(":authorizeid", $authorizeid);
				
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
	
	public function getFriendValidationInfo($uid, $friendid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$checkValidation = $this->db->prepare("SELECT * FROM friends 
				WHERE userid = :userid AND friendid = :friendid");
				
			$checkValidation->bindValue(':userid', $uid);
			$checkValidation->bindValue(':friendid', $friendid);

			if ($checkValidation->execute())
			{
				return $checkValidation->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				return null;
			}
		}
		catch(PDOException $e)
		{
			return null;
		}	
	}
	
	public function getFriendInfoRequest($validateId)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$checkValidation = $this->db->prepare("SELECT 
					friends.userid,
					friends.friendid,
					friends.authorizeid,
					friends.isAuthorized
				FROM friends 
				WHERE authorizeid = :authorizeid");
				
			$checkValidation->bindValue(':authorizeid', $validateId);

			if ($checkValidation->execute())
			{
				$result = $checkValidation->fetchAll(PDO::FETCH_ASSOC);
				
				$response["result"] = true;
				$response["isAuthorized"] = $result[0]["isAuthorized"];
				$response["userid"] = $result[0]["userid"];
				$response["friendid"] = $result[0]["friendid"];
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $checkValidation->errorInfo();
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

	public function removeFriend($userId, $friendUserId)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("DELETE FROM friends   
				WHERE friends.userid = :userId AND friends.friendid = :friendUserId");
			
			$stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindValue(':friendUserId', $friendUserId, PDO::PARAM_INT);
			
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
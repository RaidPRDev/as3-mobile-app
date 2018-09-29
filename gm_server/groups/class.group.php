<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
class GROUP
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	//////////////////////////////////////
	// GROUP CREATION
	//////////////////////////////////////
	
	public function createGroup($userid, $name, $description)
	{
		try
		{
			//Set Unique name for group
			$uniqueGroupId = $this->createUniqueGroupId($userid, $name);
			if(isset($uniqueGroupId["ERROR"]))
			{
				die($uniqueGroupId["ERROR"]);
			}

			$uniqueName = $uniqueGroupId["uniqueGroupId"];

			$stmt = $this->db->prepare("INSERT INTO groups(userid,name,description) 
							VALUES(:userid, :uniqueName, :description)");
												  
			$stmt->bindparam(":userid", $userid);
			$stmt->bindparam(":uniqueName", $uniqueName);
			$stmt->bindparam(":description", $description);							  
				
			if ($stmt->execute())
			{
				$response["result"] = true;
				$_SESSION['newgroupid'] = $this->db->lastInsertId();
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
	
	public function createGroupWithUsers($userid, $name, $description, $groupUsers)
	{
		try
		{
			//Set Unique name for group
			$uniqueGroupId = $this->createUniqueGroupId($userid, $name);
			if(isset($uniqueGroupId["ERROR"]))
			{
				die($uniqueGroupId["ERROR"]);
			}

			$uniqueName = $uniqueGroupId["uniqueGroupId"];

			$stmt = $this->db->prepare("INSERT INTO groups(userid,name,description,uniqueName) 
							VALUES(:userid, :name, :description, :uniqueName)");
												  
			$stmt->bindparam(":userid", $userid);
			$stmt->bindparam(":name", $name);
			$stmt->bindparam(":description", $description);							  
			$stmt->bindparam(":uniqueName", $uniqueName);							  
				
			if ($stmt->execute())
			{
				// Check to see if we need to add users to this group
				if(!is_null($groupUsers))
				{
					$groupid = $this->db->lastInsertId();
					
					// not sure if we need these validated fields any longer
					// any user added to a group is a friend anyway, and so it should be already validated
					// For now will auto populate these fields
					$validateid = "0c89a65a91348b4f2b57b14354e61a6d";
					$validated = 0;
					$insertData = array(); 
					foreach ($groupUsers as $row) $insertData[] = [$groupid, $row['userid'], $validateid, $validated];
					
					$stmtAddUsers = $this->multiPrepare('INSERT INTO group_user (groupid,userid,validateid,validated)', $insertData);
					
					$bindArray = array();
					array_walk_recursive($insertData, function($item) use (&$bindArray) { $bindArray[] = $item; });
					
					if ($stmtAddUsers->execute($bindArray))
					{
						$response["result"] = true;
					}
				}
				else	
				{
					$response["result"] = true;
				}
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
	
	public function updateGroupAndAddUsers($groupid, $name, $description, $groupUsers)
	{
		try
		{
			$stmt = $this->db->prepare("UPDATE groups SET 
					groups.name = :name, 
					groups.description = :description
				WHERE groups.id = :groupid");
			
			$stmt->bindparam(":name", $name);
			$stmt->bindparam(":description", $description);		
			$stmt->bindparam(":groupid", $groupid);			
				
			if ($stmt->execute())
			{
				// echo "GROUPID: " . $groupid . "<br><br>";
				
				// Check to see if we need to add users to this group
				if(!is_null($groupUsers))
				{
					// not sure if we need these validated fields any longer
					// any user added to a group is a friend anyway, and so it should be already validated
					// For now will auto populate these fields
					$validateid = "0c89a65a91348b4f2b57b14354e61a6d";
					$validated = 0;
					$insertData = array(); 
					foreach ($groupUsers as $row) 
					{
						echo "GroupID: " . $groupid . " | User Added ID: " . $row['userid'];
						$insertData[] = [$groupid, $row['userid'], $validateid, $validated];
					}
					
					$stmtAddUsers = $this->multiPrepare('INSERT INTO group_user (groupid,userid,validateid,validated)', $insertData);
					
					$bindArray = array();
					array_walk_recursive($insertData, function($item) use (&$bindArray) { $bindArray[] = $item; });
					
					if ($stmtAddUsers->execute($bindArray))
					{
						$response["result"] = true;
					}
				}
				else	
				{
					$response["result"] = true;
				}
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
	
	// Reference: https://www.daniweb.com/programming/web-development/code/495371/insert-multiple-records-with-pdo-prepared-statement
	public function multiPrepare($sql, $data)
    {
        $rows = count($data);
        $cols = count($data[0]);
        $rowString = '(' . rtrim(str_repeat('?,', $cols), ',') . '),';
        $valString = rtrim(str_repeat($rowString, $rows), ',');
        return $this->db->prepare($sql . ' VALUES ' . $valString);
    }

	//Create Unique Group Id start
	public function createUniqueGroupId($userid, $name)
	{
		try
		{
			$name = str_replace(' ', '_', $name);
			$flag = true;
			while($flag)
			{
				$randomCode = strtoupper(substr(md5($userid.time()),0,6));
				$uniqueGroupId = $name.'_'.$randomCode;
				$stmt = $this->db->prepare("SELECT name FROM groups WHERE name = :uniqueGroupId");
				$stmt->bindparam(":uniqueGroupId", $uniqueGroupId, PDO::PARAM_STR);
				$stmt->execute() or die(print_r($stmt->errorInfo()));
				if($stmt->rowCount() == 0)
				{
					break;
				}
			}
			$response["uniqueGroupId"] = $uniqueGroupId;
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}	
	//Create Unique Group Id end	
	
	public function getGroupList($userid, $skip = 0, $max = 5)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$fetchGroups = $this->db->prepare("SELECT 
					groups.id, 
					groups.userid AS ownerid, 
					groups.name, 
					groups.description
				FROM groups 
				WHERE userid = :userid 
				ORDER BY groups.name ASC LIMIT :skip, :max");
				
			$fetchGroups->bindValue(':userid', $userid, PDO::PARAM_INT);

			if(isset($skip)) $fetchGroups->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			else $fetchGroups->bindValue(':skip', 0, PDO::PARAM_INT);  
	
			$fetchGroups->bindValue(':max', $max, PDO::PARAM_INT);
						
			if ($fetchGroups->execute())
			{
				$response["result"] = true;
				$response["groups"] = $fetchGroups->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchGroups->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}	
	
	//Joined Groups start
	public function getJoinedGroupList($userid, $skip = 0, $max = 5)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$fetchGroups = $this->db->prepare("SELECT 
					groups.id, 
					groups.userid AS ownerid, 
					groups.name, 
					groups.description
				FROM groups 
				INNER JOIN group_user
				ON groups.id = group_user.groupid
				WHERE group_user.userid = :userid 
				ORDER BY groups.name ASC LIMIT :skip, :max");
				
			$fetchGroups->bindValue(':userid', $userid, PDO::PARAM_INT);

			if(isset($skip)) $fetchGroups->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			else $fetchGroups->bindValue(':skip', 0, PDO::PARAM_INT);  
	
			$fetchGroups->bindValue(':max', $max, PDO::PARAM_INT);
						
			if ($fetchGroups->execute())
			{
				$response["result"] = true;
				$response["groups"] = $fetchGroups->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchGroups->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}
	//Joined Groups end

	//Not Joined Groups start
	public function getAllGroupList($userid, $skip = 0, $max = 5)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$fetchGroups = $this->db->prepare("SELECT 
					groups.id, 
					groups.userid AS ownerid, 
					groups.name, 
					groups.description
				FROM groups 
				LEFT JOIN group_user ON groups.id = group_user.groupid
				WHERE (group_user.userid IS NULL
				OR group_user.userid != :userid)
				AND groups.userid != :ownerid 
				GROUP BY groups.id
				ORDER BY groups.name ASC LIMIT :skip, :max");
				
			$fetchGroups->bindValue(':userid', $userid, PDO::PARAM_INT);
			$fetchGroups->bindValue(':ownerid', $userid, PDO::PARAM_INT);

			if(isset($skip)) $fetchGroups->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			else $fetchGroups->bindValue(':skip', 0, PDO::PARAM_INT);  
	
			$fetchGroups->bindValue(':max', $max, PDO::PARAM_INT);
						
			if ($fetchGroups->execute())
			{
				$response["result"] = true;
				$response["groups"] = $fetchGroups->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchGroups->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}
	//Not Joined Groups end
	public function getGroupExcludedIdList($userid, $exludeIds, $skip = 0, $max = 5)
	{
		$stmtExlude = "";
		$maxLength = sizeof($exludeIds);
		
		// Create query for exluding given groupIds 
		for ($x = 0; $x < $maxLength; $x++) 
		{
			$stmtExlude .= " AND groups.id <> '" . $exludeIds[$x]["id"] . "'";
		}
		
		// echo "$stmtExlude" . "<br>";
		
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$sqlQuery = "SELECT groups.id, groups.userid, groups.name, groups.description ";
			$sqlQuery .= "FROM groups WHERE userid = :userid ";
			$sqlQuery .= $stmtExlude . " ";
			$sqlQuery .= "ORDER BY groups.name ASC LIMIT :skip, :max";

			$stmt = $this->db->prepare($sqlQuery);
				
			$stmt->bindValue(':userid', $userid, PDO::PARAM_INT);

			if(isset($skip)) $stmt->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			else $stmt->bindValue(':skip', 0, PDO::PARAM_INT);  
	
			$stmt->bindValue(':max', $max, PDO::PARAM_INT);
						
			if ($stmt->execute())
			{
				$response["result"] = true;
				$response["groups"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
	
	public function updateGroupWithUsers($info)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("UPDATE groups SET 
					groups.name = :name, 
					groups.description = :description
				WHERE groups.id = :id");
				
			$stmt->bindValue(':name', $info["groupName"], PDO::PARAM_INT);
			$stmt->bindValue(':description', $info["groupDescription"], PDO::PARAM_STR);
			$stmt->bindValue(':id', $info["groupId"], PDO::PARAM_INT);
			
			if ($stmt->execute())
			{
				$groupid = $info["groupId"];
				//$response["result"] = true;
				// Check to see if we need to add users to this group
				if(!is_null($info["addGroupUsers"]))
				{
					// not sure if we need these validated fields any longer
					// any user added to a group is a friend anyway, and so it should be already validated
					// For now will auto populate these fields
					$validateid = "0c89a65a91348b4f2b57b14354e61a6d";
					$validated = 0;
					$insertData = array(); 
					foreach ($info["addGroupUsers"] as $row) $insertData[] = [$groupid, $row['userid'], $validateid, $validated];
					
					$stmtAddUsers = $this->multiPrepare('INSERT INTO group_user (groupid,userid,validateid,validated)', $insertData);
					
					$bindArray = array();
					array_walk_recursive($insertData, function($item) use (&$bindArray) { $bindArray[] = $item; });
					
					if ($stmtAddUsers->execute($bindArray))
					{
						$response["result"] = true;
					}
				}
				else	
				{
					$response["result"] = true;
				}

				// Check to see if we need to remove users of this group
				if(!is_null($info["removeGroupUsers"]))
				{
					$error = 0;
					foreach($info["removeGroupUsers"] as $groupuserid)
					{
						$removeGroupInfo['groupUserId'] = $groupuserid;
						$removeGroupUser = $this->removeGroupUser($removeGroupInfo);
						if ($removeGroupUser["result"] == false)
						{
							$error++;
						}
					}
					if ($error == 0)
					{
						$response["result"] = true;
					}
					else
					{
						$response["result"] = false;
					}
				}
				else	
				{
					$response["result"] = true;
				}
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
	
	public function updateGroup($info)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("UPDATE groups SET 
					groups.name = :name, 
					groups.description = :description
				WHERE groups.id = :id");
				
			$stmt->bindValue(':name', $info["groupName"], PDO::PARAM_INT);
			$stmt->bindValue(':description', $info["groupDescription"], PDO::PARAM_STR);
			$stmt->bindValue(':id', $info["groupId"], PDO::PARAM_INT);
			
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
	
	public function removeGroup($info)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("DELETE FROM groups   
				WHERE groups.id = :id");
			
			$stmt->bindValue(':id', $info["groupId"], PDO::PARAM_INT);
			
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
	
	public function removeGroupWithUsers($info)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("DELETE FROM groups   
				WHERE groups.id = :id");
			
			$stmt->bindValue(':id', $info["groupId"], PDO::PARAM_INT);
			
			if ($stmt->execute())
			{
				$response["result"] = true;
				
				// Remove Group's users as well
				$fetchGroupUsers = $this->getGroupUserList($info["groupId"], 0, 1000);
				
				// Check to see if we need to remove users to this group
				if (count($fetchGroupUsers["groupUsers"]) > 0)
				{
					$deleteData = array(); 
					foreach ($fetchGroupUsers["groupUsers"] as $row) $deleteData[] = [$row['id']];
					
					$stmtGroupUsers = $this->multiPrepareDelete('DELETE FROM group_user WHERE id IN ', $deleteData);
					
					$bindArray = array();
					array_walk_recursive($deleteData, function($item) use (&$bindArray) { $bindArray[] = $item; });
					
					if ($stmtGroupUsers->execute($bindArray))
					{
						$response["result"] = true;
					}
				}
				else	
				{
					$response["result"] = true;
				}
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
	
	public function removeUsersInGroup($info)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			// Remove Group's users as well
			$fetchGroupUsers = $this->getGroupUserList($info["groupId"], 0, 1000);
			
			// Check to see if we need to remove users to this group
			if (count($fetchGroupUsers["groupUsers"]) > 0)
			{
				$deleteData = array(); 
				foreach ($fetchGroupUsers["groupUsers"] as $row) $deleteData[] = [$row['id']];
				
				$stmtGroupUsers = $this->multiPrepareDelete('DELETE FROM group_user WHERE id IN ', $deleteData);
				
				$bindArray = array();
				array_walk_recursive($deleteData, function($item) use (&$bindArray) { $bindArray[] = $item; });
				
				if ($stmtGroupUsers->execute($bindArray))
				{
					$response["result"] = true;
				}
			}
			else	
			{
				$response["result"] = true;
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}
	
	public function multiPrepareDelete($sql, $data)
    {
        $rows = count($data);
        $cols = count($data[0]);
        $rowString = rtrim(str_repeat('?,', $cols), ',') . ',';
        $valString = rtrim(str_repeat($rowString, $rows), ',');
        return $this->db->prepare($sql . ' ( ' . $valString . ' ) ');
    }
	
	/////////////////////////////////////////////////////////////////////
	// Group Item/Users
	////////////////////////////////////////////////////////////////////

	public function addUserToGroup($groupid, $userid, $validateid)
	{
		try
		{
			$stmt = $this->db->prepare("INSERT INTO group_user(groupid,userid,validateid,validated) 
							VALUES(:groupid, :userid, :validateid, :validated)");
			
			$stmt->bindparam(":groupid", $groupid, PDO::PARAM_INT);
			$stmt->bindparam(":userid", $userid, PDO::PARAM_INT);
			$stmt->bindparam(":validateid", $validateid, PDO::PARAM_STR);
			
			$validated = 0;
			$stmt->bindparam(":validated", $validated, PDO::PARAM_INT);					  
				
			if ($stmt->execute())
			{
				$response["result"] = true;
				$response['newgroupuserid'] = $this->db->lastInsertId();
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
	
	public function removeGroupUser($info)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("DELETE FROM group_user   
				WHERE group_user.id = :id");
			
			$stmt->bindValue(':id', $info["groupUserId"], PDO::PARAM_INT);
			
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
	
	public function getGroupUserList($groupid, $skip = 0, $max = 5)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$fetchGroups = $this->db->prepare("SELECT 
					group_user.id, 
					group_user.groupid, 
					users.id AS userid, 
					users.username, 
					users.useremail,
					users.accountType,
					users.parentid, 
					users.gender,
					users.birthDate,
					user_avatar.thumb
				FROM group_user 
				INNER JOIN users 
				ON group_user.userid = users.id 
				INNER JOIN user_avatar 
				ON group_user.userid = user_avatar.userid 
				WHERE group_user.groupid = :groupid 
				ORDER BY users.username ASC LIMIT :skip, :max");	
				
			$fetchGroups->bindValue(':groupid', $groupid, PDO::PARAM_INT);

			if(isset($skip)) $fetchGroups->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			else $fetchGroups->bindValue(':skip', 0, PDO::PARAM_INT);  
	
			$fetchGroups->bindValue(':max', $max, PDO::PARAM_INT);
						
			if ($fetchGroups->execute())
			{
				$response["result"] = true;
				$response["groupUsers"] = $fetchGroups->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchGroups->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}

	//Search Group Start
	public function searchGroup($userid, $searchTerm, $skip = 0, $max = 5)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			if(!is_null($searchTerm))
			{
				$searchTerm = str_replace(' ', '_', $searchTerm);
				$searchTerm = "%".$searchTerm."%";
				$fetchGroups = $this->db->prepare("SELECT 
					groups.id, 
					users.username AS 'owner', 
					user_avatar.thumb AS 'ownerthumb',
					groups.name, 
					groups.description,
					groups.uniqueName,
					IF(group_user.id IS NULL,'no','yes') AS 'joined' 
				FROM groups 
				LEFT JOIN users
				ON groups.userid = users.id
				LEFT JOIN user_avatar
				ON groups.userid = user_avatar.userid
				LEFT JOIN group_user
				ON (groups.id = group_user.groupid AND group_user.userid = :userid)
				WHERE groups.name LIKE :searchTerm
				ORDER BY groups.name ASC LIMIT :skip, :max");
				
				$fetchGroups->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
			}
			else
			{
				$fetchGroups = $this->db->prepare("SELECT 
					groups.id, 
					users.username AS 'owner', 
					user_avatar.thumb AS 'ownerthumb', 
					groups.name, 
					groups.description,
					groups.uniqueName,
					IF(group_user.id IS NULL,'no','yes') AS 'joined' 
				FROM groups 
				LEFT JOIN users
				ON groups.userid = users.id
				LEFT JOIN user_avatar
				ON groups.userid = user_avatar.userid
				LEFT JOIN group_user
				ON (groups.id = group_user.groupid AND group_user.userid = :userid)
				ORDER BY groups.name ASC LIMIT :skip, :max");
			}

			$fetchGroups->bindValue(':userid', $userid, PDO::PARAM_INT);

			if(isset($skip)) $fetchGroups->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			else $fetchGroups->bindValue(':skip', 0, PDO::PARAM_INT);  
	
			$fetchGroups->bindValue(':max', $max, PDO::PARAM_INT);
						
			if ($fetchGroups->execute())
			{
				$response["result"] = true;
				$response["groups"] = $fetchGroups->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchGroups->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}
	//Search Group End	

	//Group Details start
	public function getGroupDetails($groupid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$fetchGroups = $this->db->prepare("SELECT * FROM groups WHERE id = :groupid");
				
			$fetchGroups->bindValue(':groupid', $groupid, PDO::PARAM_INT);
						
			if ($fetchGroups->execute())
			{
				$response["result"] = true;
				$response["groupDetails"] = $fetchGroups->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchGroups->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}
	//Group Details end

	//checkForGroupOwner start
	public function checkForGroupOwner($groupid, $groupOwnerid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$fetchGroups = $this->db->prepare("SELECT id FROM groups WHERE id = :groupid AND userid = :groupOwnerid");
				
			$fetchGroups->bindValue(':groupid', $groupid, PDO::PARAM_INT);
			$fetchGroups->bindValue(':groupOwnerid', $groupOwnerid, PDO::PARAM_INT);
						
			if ($fetchGroups->execute())
			{
				$response["result"] = true;
				$response["groupDetails"] = $fetchGroups->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchGroups->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}
	//checkForGroupOwner end

	//checkForGroupUser start
	public function checkForGroupUser($groupid, $groupUserid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$stmt = $this->db->prepare("SELECT id FROM group_user WHERE groupid = :groupid AND userid = :groupUserid");
				
			$stmt->bindValue(':groupid', $groupid, PDO::PARAM_INT);
			$stmt->bindValue(':groupUserid', $groupUserid, PDO::PARAM_INT);
						
			if ($stmt->execute())
			{
				$response["result"] = true;
				$response["groupUserDetails"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
	//checkForGroupUser end

	/*Group Funds or Payments start*/
	public function groupFunds($amount, $group_id, $group_owner_id, $user_id)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("INSERT INTO group_funds(amount, group_id, group_owner_id, user_id) 
 							VALUES(:amount, :group_id, :group_owner_id, :user_id)");


			$stmt->bindValue(":amount", $amount);
			$stmt->bindparam(":group_id", $group_id);
			$stmt->bindparam(":group_owner_id", $group_owner_id);
			$stmt->bindparam(":user_id", $user_id);

			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
	/*Group Funds or Payments end*/

	/*Begin GetTotalFundsForGroupsUserwise List*/
    public function getTotalFundsForGroupsUserwise($skip = 0, $max = 5)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$fetchFundedGroups = $this->db->prepare("SELECT 
				group_funds.user_id,
				group_funds.group_id,
				groups.name AS group_name,
				SUM(group_funds.amount) AS total_funds
                FROM group_funds 
				INNER JOIN groups ON group_funds.group_id = groups.id
				GROUP BY group_funds.user_id, group_funds.group_id
				ORDER BY group_funds.user_id ASC LIMIT :skip, :max");

			if(isset($skip)) {
				$fetchFundedGroups->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			} else {
				$fetchFundedGroups->bindValue(':skip', 0, PDO::PARAM_INT);  
			}

			$fetchFundedGroups->bindValue(':max', $max, PDO::PARAM_INT);

			$fetchFundedGroups->execute() or die(print_r($fetchFundedGroups->errorInfo()));
			
			return $fetchFundedGroups->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}
	/*End GetTotalFundsForGroupsUserwise List*/

	/*Group subscription start*/
	public function subscribeGroup($group_id, $user_id)
	{
		try
		{
			$stmt = $this->db->prepare("INSERT INTO group_subscriptions(group_id,user_id,enabled,created_date) 
							VALUES(:group_id, :user_id, '1', NOW())");
			
			$stmt->bindparam(":group_id", $group_id, PDO::PARAM_INT);
			$stmt->bindparam(":user_id", $user_id, PDO::PARAM_INT);
			
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
	/*Group subscription end*/

	/*Retrieve id's of subscribed users of a Group start*/
	public function getGroupSubscribedUsersIds($group_id)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("SELECT user_id FROM group_subscriptions
					WHERE group_id=:group_id
					AND enabled='1'");
			
			$stmt->bindValue(':group_id', $group_id, PDO::PARAM_INT);

			if($stmt->execute())
			{
				$response["result"] = true;
				$response["subscribedUsersIds"] = $stmt->fetchAll(PDO::FETCH_COLUMN , 0);
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
	/*Retrieve id's of subscribed users of a Group end*/

	/*Disable Group subscription start*/
	public function disableGroupSubscription()
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("UPDATE group_subscriptions
					INNER JOIN users ON group_subscriptions.user_id = users.id
					SET group_subscriptions.enabled = '0'
					WHERE users.wallet = 0");
			
			if($stmt->execute())
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
	/*Disable Group subscription end*/
}
?>

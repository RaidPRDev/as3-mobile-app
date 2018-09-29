<?php
class EVENT
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	//////////////////////////////////////
	// EVENT CREATION
	//////////////////////////////////////
	
	public function createEvent($userid, $eventInfo)
	{
		try
		{
			$stmt = $this->db->prepare("INSERT INTO events(userid,name,description,eventDate,hour,minute,meridien) 
							VALUES(:userid, :name, :description, :date, :hour, :minute, :meridien)");
												  
			$stmt->bindparam(":userid", $userid, PDO::PARAM_INT);
			$stmt->bindparam(":name", $eventInfo["eventName"], PDO::PARAM_STR);
			$stmt->bindparam(":description", $eventInfo["eventDescription"], PDO::PARAM_STR);	
			$stmt->bindparam(":date", $eventInfo["eventDate"], PDO::PARAM_STR);	
			$stmt->bindparam(":hour", $eventInfo["eventTimeHour"], PDO::PARAM_INT);	
			$stmt->bindparam(":minute", $eventInfo["eventTimeMinute"], PDO::PARAM_INT);	
			$stmt->bindparam(":meridien", $eventInfo["eventTimeMeridien"], PDO::PARAM_STR);	
				
			if ($stmt->execute())
			{
				$response["result"] = true;
				$response['neweventid'] = $this->db->lastInsertId();
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
	
	public function addGroupIDToEvent($userid, $eventid, $groupIds)
	{
		$max = sizeof($groupIds);

		try
		{
			for ($x = 0; $x < $max; $x++) 
			{
			
				$stmt = $this->db->prepare("INSERT INTO event_groups(userid,eventid,groupid) 
								VALUES(:userid, :eventid, :groupid)");
													  
				$stmt->bindparam(":userid", $userid, PDO::PARAM_INT);
				$stmt->bindparam(":eventid", $eventid, PDO::PARAM_INT);
				$stmt->bindparam(":groupid", $groupIds[$x], PDO::PARAM_INT);	
					
				if ($stmt->execute())
				{
					$response["result"] = true;
				}
				else 
				{
					$response["result"] = false;
					$response["ERROR"] = $stmt->errorInfo();
				}
				
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}	
	
	public function checkIfGroupAddedToEvent($eventid, $groupid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$stmt = $this->db->prepare("SELECT 
					event_groups.id
				FROM event_groups 
				WHERE event_groups.eventid = :eventid AND event_groups.groupid = :groupid
				ORDER BY event_groups.id");

			$stmt->bindValue(':eventid', $eventid, PDO::PARAM_INT);
			$stmt->bindValue(':groupid', $groupid, PDO::PARAM_INT);

			if ($stmt->execute())
			{
				$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
				$max = sizeof($data);
				
				if ($max > 0)
				{
					$response["eventgroupid"] = $data[0]["id"];
					$response["result"] = true;
				}
				else $response["result"] = false;
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
	
	public function updateGroupIDToEvent($userid, $eventid, $groupIds)
	{
		$currentEventGroupData = $this->getGroupsByEventId($eventid);
		$currentEventGroupList = $currentEventGroupData["event_groups"];
		$currentEventGroupListMax = sizeof($currentEventGroupList); // Current DB Group List 
		$groupIdsMax = sizeof($groupIds);	// Updated Group ID List from Application
		
		try
		{
			// Check if we need to remove any Group IDs not added in the UpdatedGroupIdList (groudIds)
			for ($x = 0; $x < $currentEventGroupListMax; $x++) 
			{
				// Save current EventGroupId and GroupId for later
				$currentEventGroupId = $currentEventGroupList[$x]['id'];
				$currentGroupId = $currentEventGroupList[$x]['groupid'];
				
				// Go through the Updated Group List and see if we find any matches
				$doRemove = true;
				for ($i = 0; $i < $groupIdsMax; $i++) 
				{
					// If we do not find any matches, it has been removed from Application
					$doRemove = ($currentGroupId == $groupIds[$i]) ? false : true;
				}
				
				// If TRUE, remove the current group id from Event
				if ($doRemove == true)	
				{
					$stmt = $this->db->prepare("DELETE FROM event_groups WHERE event_groups.id = :id");
					$stmt->bindparam(":id",$currentEventGroupId, PDO::PARAM_INT);
					$stmt->execute();
				}
			}
			
			// Now lets add the new GroupIds, if any
			for ($i = 0; $i < $groupIdsMax; $i++) 
			{
				// Check if it already exists , do nothing otherwise
				$checkGroupId = $this->checkIfGroupAddedToEvent($eventid, $groupIds[$i]);
			
				if ($checkGroupId["result"] == false)
				{
					$stmt = $this->db->prepare("INSERT INTO event_groups(userid,eventid,groupid) 
								VALUES(:userid, :eventid, :groupid)");	
					
					$stmt->bindparam(":userid", $userid, PDO::PARAM_INT);
					$stmt->bindparam(":eventid", $eventid, PDO::PARAM_INT);
					$stmt->bindparam(":groupid", $groupIds[$i], PDO::PARAM_INT);	
					$stmt->execute();	
				}
			}
			
			$response["result"] = true;
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}
	}		
	
	public function getEventList($userid, $skip = 0, $max = 5)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$stmt = $this->db->prepare("SELECT 
					events.id, 
					events.userid, 
					events.name, 
					events.description,
					events.eventDate,
					events.hour,
					events.minute,
					events.meridien
				FROM events 
				WHERE userid = :userid 
				ORDER BY events.name DESC LIMIT :skip, :max");

			$stmt->bindValue(':userid', $userid, PDO::PARAM_INT);

			if(isset($skip)) $stmt->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			else $stmt->bindValue(':skip', 0, PDO::PARAM_INT);  
	
			$stmt->bindValue(':max', $max, PDO::PARAM_INT);
						
			if ($stmt->execute())
			{
				$response["result"] = true;
				$response["events"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
	
	public function updateEvent($userid, $eventInfo)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("UPDATE events SET 
					events.userid = :userid, 
					events.name = :name, 
					events.description = :description,
					events.eventDate = :date,
					events.hour = :hour,
					events.minute = :minute,
					events.meridien = :meridien
				WHERE events.id = :id");
				
			$stmt->bindparam(":userid", $userid, PDO::PARAM_INT);
			$stmt->bindparam(":name", $eventInfo["eventName"], PDO::PARAM_STR);
			$stmt->bindparam(":description", $eventInfo["eventDescription"], PDO::PARAM_STR);	
			$stmt->bindparam(":date", $eventInfo["eventDate"], PDO::PARAM_STR);	
			$stmt->bindparam(":hour", $eventInfo["eventTimeHour"], PDO::PARAM_INT);	
			$stmt->bindparam(":minute", $eventInfo["eventTimeMinute"], PDO::PARAM_INT);	
			$stmt->bindparam(":meridien", $eventInfo["eventTimeMeridien"], PDO::PARAM_STR);	
			$stmt->bindparam(":id", $eventInfo["eventid"], PDO::PARAM_INT);	

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
	
	public function removeEvent($info)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("DELETE FROM events   
				WHERE events.id = :id");
			
			$stmt->bindValue(':id', $info["eventId"], PDO::PARAM_INT);
			
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
	
	public function getGroupsByEventId($eventid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$stmt = $this->db->prepare("SELECT 
					event_groups.id,
					event_groups.groupid
				FROM event_groups
				WHERE eventid = :eventid 
				ORDER BY event_groups.id");
				
			$stmt->bindValue(':eventid', $eventid, PDO::PARAM_INT);

			if ($stmt->execute())
			{
				$response["result"] = true;
				$response["event_groups"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
	
	public function removeGroupsInEvent($eventid)
	{
		$event_groups = $this->getGroupsByEventId($eventid);
		
		if ($event_groups["result"] == true)
		{
			$groupIds = $event_groups["event_groups"];
			$max = sizeof($groupIds);
			
			try
			{
				for ($x = 0; $x < $max; $x++) 
				{
					
					$stmt = $this->db->prepare("DELETE FROM event_groups   
					WHERE event_groups.id = :id");
														  
					$stmt->bindparam(":id", $groupIds[$x]["id"]);
						
					if ($stmt->execute())
					{
						$response["result"] = true;
					}
					else 
					{
						$response["result"] = false;
					}
				}
				
				if ($max == 0) $response["result"] = true;
				
				return $response;
			}
			catch(PDOException $e)
			{
				$response["ERROR"] = $e->getMessage();
				return $response;
			}	
		}
	}
	
	/////////////////////////////////////////////////////////////////////
	// Event Item/Users
	////////////////////////////////////////////////////////////////////
	
	public function getEventGroupsList($eventid, $skip = 0, $max = 5)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$stmt = $this->db->prepare("SELECT 
				event_groups.id,
				event_groups.eventid, 
				event_groups.groupid, 
				groups.id,
				groups.userid, 
				groups.name,
				groups.description
			FROM event_groups 
			INNER JOIN groups 
			ON event_groups.groupid = groups.id 
			WHERE event_groups.eventid = :eventid 
			ORDER BY groups.name DESC LIMIT :skip, :max");
				
			$stmt->bindValue(':eventid', $eventid, PDO::PARAM_INT);

			if(isset($skip)) $stmt->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			else $stmt->bindValue(':skip', 0, PDO::PARAM_INT);  
	
			$stmt->bindValue(':max', $max, PDO::PARAM_INT);
						
			if ($stmt->execute())
			{
				$response["result"] = true;
				$response["eventGroups"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
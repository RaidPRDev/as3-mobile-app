<?php
class PROFILES
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	//////////////////////////////////////
	// FRIENDS CREATION
	//////////////////////////////////////
	
	public function getProfileList($userid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$fetchProfiles = $this->db->prepare("SELECT *
				FROM users 
				INNER JOIN user_avatar ON users.id = user_avatar.userid 
				INNER JOIN user_settings ON users.id = user_settings.userid 
				WHERE parentid = :userid
				ORDER BY users.id DESC");
				
			$fetchProfiles->bindValue(':userid', $userid, PDO::PARAM_INT);

			if ($fetchProfiles->execute())
			{
				$response["result"] = true;
				$response["profiles"] = $fetchProfiles->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchProfiles->errorInfo();
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
}
?>
<?php
class SETTING
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	public function getMinimumWithdrawl()
	{
		try
		{
			//
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}

	public function getGoaliemindPercent()
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$fetchGoaliemindPercent = $this->db->prepare("SELECT value FROM settings where id=3");

			$fetchGoaliemindPercent->execute() or die(print_r($fetchGoaliemindPercent->errorInfo()));
			
			$response = $fetchGoaliemindPercent->fetchAll(PDO::FETCH_COLUMN , 0);

			return $response[0];
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}

	public function getReferpercent()
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$fetchReferpercent = $this->db->prepare("SELECT value FROM settings where id=4");

			$fetchReferpercent->execute() or die(print_r($fetchReferpercent->errorInfo()));
			
			$response = $fetchReferpercent->fetchAll(PDO::FETCH_COLUMN , 0);

			return $response[0];
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}

	public function getOrganiserReferPercent()
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$fetchOrganiserReferPercent = $this->db->prepare("SELECT value FROM settings where id=8");

			$fetchOrganiserReferPercent->execute() or die(print_r($fetchOrganiserReferPercent->errorInfo()));
			
			$response = $fetchOrganiserReferPercent->fetchAll(PDO::FETCH_COLUMN , 0);

			return $response[0];
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}

	public function getStarForOneDollar()
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$fetchStar = $this->db->prepare("SELECT value FROM settings where id=5");

			$fetchStar->execute() or die(print_r($fetchStar->errorInfo()));
			
			$response = $fetchStar->fetchAll(PDO::FETCH_COLUMN , 0);

			return $response[0];
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}

	public function getUserStars()
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$fetchStar = $this->db->prepare("SELECT value FROM settings where id=7");

			$fetchStar->execute() or die(print_r($fetchStar->errorInfo()));
			
			$response = $fetchStar->fetchAll(PDO::FETCH_COLUMN , 0);

			return $response[0];
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}

	//Retrieve Settings
	public function retrieveSettings()
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$fetchSettings = $this->db->prepare("SELECT keyword, value FROM settings");

			$fetchSettings->execute() or die(print_r($fetchSettings->errorInfo()));
			
			$response = $fetchSettings->fetchAll(PDO::FETCH_ASSOC);

			return $response;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
}
?>
<?php
class DONATIONS
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	//////////////////////////////////////
	// DONATIONS
	//////////////////////////////////////
	
	public function getDonationsList($skip = 0, $max = 5)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$stmt = $this->db->prepare("SELECT 
					donations.id, 
					donations.name, 
					donations.description,
					donations.icon,
					donations.api,
					donations.username,
					donations.password,
					donations.apikey
				FROM donations 
				ORDER BY donations.name ASC LIMIT :skip, :max");

			if(isset($skip)) $stmt->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			else $stmt->bindValue(':skip', 0, PDO::PARAM_INT);  
	
			$stmt->bindValue(':max', $max, PDO::PARAM_INT);
						
			if ($stmt->execute())
			{
				$response["result"] = true;
				$response["donations"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
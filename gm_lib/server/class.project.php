<?php
class PROJECT
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	//////////////////////////////////////
	// PROJECT CREATION
	//////////////////////////////////////
	
	public function createProject($userid, $description, $tags, $acctype, $timestamp, $month, $year, $imageName, $thumbName)
	{
		try
		{
			$stmt = $this->db->prepare("INSERT INTO projects(userid,description,tags,type,timestamp,month,year,image,thumb) 
							VALUES(:userid, :description, :tags, :accountType, :timestamp, :month, :year, :image, :thumb)");
												  
			$stmt->bindparam(":userid", $userid);
			$stmt->bindparam(":description", $description);
			$stmt->bindparam(":tags", $tags);										  
			$stmt->bindparam(":accountType", $acctype);										  
			$stmt->bindparam(":timestamp", $timestamp);										  
			$stmt->bindparam(":month", $month);										  
			$stmt->bindparam(":year", $year);										  
			$stmt->bindparam(":image", $imageName);										  
			$stmt->bindparam(":thumb", $thumbName);										  
				
			$stmt->execute();	
			
			$_SESSION['newprojectid'] = $this->db->lastInsertId();
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}	
	
	public function createProjectMedia($userid, $projectid, $imageName, $thumbName, $videoName, $month, $year, $timestamp)
	{
		$type = 1;
		try
		{
			$stmt = $this->db->prepare("INSERT INTO media(userid,projectid,image,thumb,video,month,year,timestamp,type) 
							VALUES(:userid, :projectid, :image, :thumb, :video, :month, :year, :timestamp, :type)");
												  
			$stmt->bindparam(":userid", $userid);
			$stmt->bindparam(":projectid", $projectid);
			$stmt->bindparam(":image", $imageName);										  
			$stmt->bindparam(":thumb", $thumbName);										  
			$stmt->bindparam(":video", $videoName);										  
			$stmt->bindparam(":month", $month);										  
			$stmt->bindparam(":year", $year);										  
			$stmt->bindparam(":timestamp", $timestamp);										  
			$stmt->bindparam(":type", $type);										  
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}	
	
	public function getProjectList($userid, $skip = 0, $max = 5)
	{
		/*
			MySQL *does* have an “offset,” however: `LIMIT {offset},{length}`. For example, if you wanted to get 30 rows, but skip the first 44, you would use:
			`SELECT mycol FROM mytable LIMIT 44,30`
		*/
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			/*$fetchProjects = $this->db->prepare("SELECT description,tags,type,timestamp,month,year,image 
				FROM projects 
				WHERE userid = :userid 
				ORDER BY id DESC 
				LIMIT :skip, :max");*/

			$fetchProjects = $this->db->prepare("SELECT 
					projects.id, 
					projects.description, 
					projects.tags, 
					projects.type, 
					projects.timestamp,
					media.image, 
					media.thumb,
					media.video,
					media.month,
					media.year,
					media.type
				FROM projects 
				INNER JOIN media 
				ON projects.id = media.projectid 
				WHERE media.userid = :userid 
				ORDER BY projects.id DESC LIMIT :skip, :max");
				
				
			$fetchProjects->bindValue(':userid', $userid, PDO::PARAM_INT);

			if(isset($skip)) {
				$fetchProjects->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			} else {
				$fetchProjects->bindValue(':skip', 0, PDO::PARAM_INT);  
			}

			$fetchProjects->bindValue(':max', $max, PDO::PARAM_INT);
			$fetchProjects->execute() or die(print_r($fetchProjects->errorInfo()));
			
			return $fetchProjects->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage() . " USERID: " . $userid;
		}				
	}	

}
?>
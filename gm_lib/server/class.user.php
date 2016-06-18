<?php
class USER
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	// if ($user->register($uname, $umail, $upass, $acctype, $ugender, $ubirth, $uregdate, $usigndate, $parentid))
	public function register($uname, $umail, $upass, $acctype, $ugender, $ubirth, $uregdate, $usigndate, $parentid)
	{
		try
		{
			$new_password = MD5($upass);
			
			$stmt = $this->db->prepare("INSERT INTO users(username,password,useremail,accountType,gender,birthDate,registerDate,signedInDate,parentid) 
							VALUES(:uname, :upass, :umail, :accountType, :gender, :birthdate, :registerDate, :signedInDate, :parentid)");
												  
			$stmt->bindparam(":uname", $uname);
			$stmt->bindparam(":upass", $new_password);
			$stmt->bindparam(":umail", $umail);										  
			$stmt->bindparam(":accountType", $acctype);										  
			$stmt->bindparam(":gender", $ugender);										  
			$stmt->bindparam(":birthdate", $ubirth);										  
			$stmt->bindparam(":registerDate", $uregdate);										  
			$stmt->bindparam(":signedInDate", $usigndate);										  
			$stmt->bindparam(":parentid", $parentid);										  
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	public function login($uname,$umail,$upass)
	{
		try
		{
			$stmt = $this->db->prepare("SELECT * FROM users WHERE username=:uname OR useremail=:umail LIMIT 1");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() > 0)
			{
				if($userRow['password']==MD5($upass))
				{
					$_SESSION['user_session'] = $userRow['id'];
					$_SESSION['username'] = $userRow['username'];
					$_SESSION['useremail'] = $userRow['useremail'];
					$_SESSION['accountType'] = $userRow['accountType'];
					$_SESSION['parentid'] = $userRow['parentid'];
					$_SESSION['gender'] = $userRow['gender'];
					$_SESSION['birthDate'] = $userRow['birthDate'];
					$_SESSION['isAuthorized'] = $userRow['isAuthorized'];
					$_SESSION['authorizeKey'] = $userRow['authorizeKey'];
					$_SESSION['registerDate'] = $userRow['registerDate'];
					$_SESSION['signedInDate'] = $userRow['signedInDate'];
					$_SESSION['loginToken'] = $userRow['loginToken'];
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		catch(PDOException $e)
		{
			// echo $e-> getMessage();
			$response["ERROR"] = $e-> getMessage();
			echo json_encode($response);
		}
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
	
	public function updateProjectMedia($projectid, $imgFilename, $month, $year)
	{
		try
		{
			$stmt = $this->db->prepare("UPDATE projects SET image=:image, month=:month,year=:year WHERE id=:projectid");
												  
			$stmt->bindparam(":image", $imgFilename);
			$stmt->bindparam(":month", $month);
			$stmt->bindparam(":year", $year);
			$stmt->bindparam(":projectid", $projectid);
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}		
	
	
	public function is_loggedin()
	{
		if(isset($_SESSION['user_session']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function logout()
	{
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}
}
?>
<?php
require_once '../dbconfig.php';

if(isset($_GET['checkUsername']))
{
	$uname = trim($_GET['form_uname']);
	
	try
	{
		$stmt = $DB_con->prepare("SELECT username FROM users WHERE username = :uname");
		$stmt->execute(array(':uname'=>$uname));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
			
		if($row['username'] == $uname) 
		{
			$response["ERROR"] = "Username already taken!";
		}
		else
		{
			$response["OK"] = "Username is valid";
		}
	}
	catch(PDOException $e)
	{
		$response["ERROR"] = $e->getMessage();
	}
	
	echo json_encode($response);
}
?>
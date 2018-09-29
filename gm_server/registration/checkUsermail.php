<?php
require_once '../dbconfig.php';

if(isset($_GET['checkUsermail']))
{
	$umail = trim($_GET['form_umail']);
	
	if (!filter_var($umail, FILTER_VALIDATE_EMAIL))
	{
		$response["ERROR"] = "Please enter a valid email address";
		
		echo json_encode($response);
		
		exit;
	}
	
	try
	{
		$stmt = $DB_con->prepare("SELECT useremail FROM users WHERE useremail = :umail");
		$stmt->execute(array(':umail'=>$umail));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
			
		if($row['useremail'] == $umail) 
		{
			$response["ERROR"] = "Email already taken!";
		}
		else
		{
			$response["OK"] = "Email is valid";
		}
	}
	catch(PDOException $e)
	{
		$response["ERROR"] = $e->getMessage();
	}
	
	echo json_encode($response);
}
?>
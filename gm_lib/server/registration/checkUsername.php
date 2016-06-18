<?php
require_once '../dbconfig.php';

if(isset($_GET['checkUsername']))
{
	$uname = trim($_GET['form_uname']);
	
	if($uname=="")	{
		$error[] = "Provide username!";	
	}
	else
	{
		try
		{
			$stmt = $DB_con->prepare("SELECT username FROM users WHERE username=:uname");
			$stmt->execute(array(':uname'=>$uname));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if($row['username']==$uname) {
				$error[] = "Username already taken!";
			}
			else
			{
				$response["OK"] = "Username is valid";
				echo json_encode($response);
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
}

if(isset($error))
{
	$response["ERROR"] = $error;
	echo json_encode($response);
}

?>
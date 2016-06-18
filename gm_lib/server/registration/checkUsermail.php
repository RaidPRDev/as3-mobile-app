<?php
require_once '../dbconfig.php';

if(isset($_GET['checkUsermail']))
{
	$umail = trim($_GET['form_umail']);
	
	if($umail=="")	{
		$error[] = "provide email id !";	
	}
	else if(!filter_var($umail, FILTER_VALIDATE_EMAIL))	{
	    $error[] = 'Please enter a valid email address !';
	}	
	else
	{
		try
		{
			$stmt = $DB_con->prepare("SELECT useremail FROM users WHERE useremail=:umail");
			$stmt->execute(array(':umail'=>$umail));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if($row['useremail']==$umail) {
				$error[] = "Email already taken!";
			}
			else
			{
				$response["OK"] = "Email is valid";
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
<?php
require_once '../dbconfig.php';

if(isset($_GET['signup']))
{
	// http://raidpr.com/clients/goaliemind/registration/sendRegistration.php?signup=true&form_uname=fania4life&form_umail=fania@fania.com&form_upass=Fania554093&form_accountType=1&form_gender=1&form_birthdate=12321&form_registerDate=999&form_signedInDate=9999999

	$uname = trim($_GET['form_uname']);
	$umail = trim($_GET['form_umail']);
	$upass = trim($_GET['form_upass']);	
	$acctype = trim($_GET['form_accountType']);	
	$ugender = trim($_GET['form_gender']);	
	$ubirth = trim($_GET['form_birthdate']);	
	$uregdate = trim($_GET['form_registerDate']);	
	$usigndate = trim($_GET['form_signedInDate']);	
	$parentid = trim($_GET['form_parentId']);	
	
	if($uname=="")	{
		$error[] = "provide username !";	
	}
	else if($umail=="")	{
		$error[] = "provide email id !";	
	}
	else if(!filter_var($umail, FILTER_VALIDATE_EMAIL))	{
	    $error[] = 'Please enter a valid email address !';
	}
	else if($upass=="")	{
		$error[] = "provide password !";
	}
	else if(strlen($upass) < 6){
		$error[] = "Password must be atleast 6 characters";	
	}
	else
	{
		try
		{
			$stmt = $DB_con->prepare("SELECT username,useremail FROM users WHERE username=:uname OR useremail=:umail");
			$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if($row['username']==$uname) {
				$error[] = "sorry username already taken !";
			}
			else if($row['useremail']==$umail) {
				$error[] = "sorry email id already taken !";
			}
			else
			{
				if ($user->register($uname, $umail, $upass, $acctype, $ugender, $ubirth, $uregdate, $usigndate, $parentid))	
				{
					$response["OK"] = "User registered!";
					echo json_encode($response);
				}
			}
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e-> getMessage();
			echo json_encode($response);
		}
	}	
}

if(isset($error))
{
	$response["ERROR"] = $error;
	echo json_encode($response);
}




?>
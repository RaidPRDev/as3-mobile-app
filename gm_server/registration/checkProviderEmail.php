<?php
require_once '../dbconfig.php';

/*

	This email is coming from a Provider ( Google or Facebook )
	We will check if this email exists, if it does we will continue to log in the user from app
	If it does not, the app will show registration

*/

if(isset($_GET['checkEmailProvider']))
{
	$umail = trim($_GET['form_umail']);
	$provider = trim($_GET['form_provider']);
	$provider_uid = trim($_GET['form_provider_uid']);
	
	try
	{
		$stmt = $DB_con->prepare("SELECT useremail FROM users WHERE useremail = :umail AND provider = :provider");
		$stmt->execute(array(':umail'=>$umail, ':provider'=>$provider));
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
			
		if($row['useremail'] == $umail) 
		{
			// User has already registered
			$response["ERROR"] = "Email already taken!";
			$userInfo = $user->fetchUserIdByProviderUID($provider_uid);
			$response["user"] = $userInfo;
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
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../dbconfig.php';

include_once '../avatar/class.avatar.php';
include_once '../avatar/class.avatar.data.php';
include_once '../payments/class.payment.php';
require_once '../class.setting.php';
require_once '../users/userSettings/class.user.settings.php';

if(isset($_GET['signup']))
{
	$uprovider = trim($_GET['form_provider']);
	$uprovideruid = trim($_GET['form_provider_uid']);
	$uname = trim($_GET['form_uname']);
	$umail = trim($_GET['form_umail']);
	$upass = trim($_GET['form_upass']);	
	$acctype = trim($_GET['form_accountType']);	
	$ugender = trim($_GET['form_gender']);	
	$ubirth = trim($_GET['form_birthdate']);	
	$uregdate = trim($_GET['form_registerDate']);	
	$usigndate = trim($_GET['form_signedInDate']);	
	$parentid = trim($_GET['form_parentId']);

	/*Fetch User Reference Id start*/
	if(isset($_GET['form_referCode']) && !empty($_GET['form_referCode']))
	{
		$referCode = trim($_GET['form_referCode']);
		$result_referid = $user->fetchReferId($referCode);
		
		//$referid = -1 for Not Found
		$referid = $result_referid['refer_id'];
		$organisationreferid = $result_referid['organisation_refer_id'];
	}
	else
	{
		$referid = 0;
		$organisationreferid = 0;
	}
	/*Fetch User Reference Id end*/

	/*Fetch default user stars from setting start*/
	$settings = new SETTING($DB_con);
	$userstars = $settings->getUserStars();
	/*Fetch default user stars from setting end*/
	
	$avatarData = new AVATAR_DATA();
	$avatarData->bodyId = trim($_GET['bodyId']);
	$avatarData->bodyColor = trim($_GET['bodyColor']);
	$avatarData->eyesId = trim($_GET['eyesId']);
	$avatarData->mouthId = trim($_GET['mouthId']);
	$avatarData->feetId = trim($_GET['feetId']);
	$avatarData->feetColor = trim($_GET['feetColor']);
	$avatarData->accessoryId = trim($_GET['accessoryId']);
	$avatarData->accessoryColor = trim($_GET['accessoryColor']);
	
	if($uname=="")	{
		$error[] = "Provide username!";	
	}
	else if($umail=="")	{
		$error[] = "Provide email!";	
	}
	else if(!filter_var($umail, FILTER_VALIDATE_EMAIL))	{
	    $error[] = 'Please enter a valid email address!';
	}
	else if($upass=="")	{
		$error[] = "Provide password!";
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
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if($row['username']==$uname) {
				$error[] = "Sorry, username already taken!";
			}
			else if($row['useremail']==$umail && ($acctype==1 || $acctype==2)) {
				$error[] = "Sorry, email id already taken!";
			}
			else if($referid==-1)
			{
				$error[] = "Sorry, invalid reference code!";
			}
			else
			{
				if ($user->register($uname, $umail, $upass, $acctype, $ugender, $ubirth, $uregdate, $usigndate, $parentid, $referid, $userstars, $organisationreferid, $uprovider, $uprovideruid))	
				{
					// Get Last Insert ID as UserId
					$fetchUserId = $user->fetchUserIdByUsername($uname);
					$userid = $fetchUserId[0]["id"];
					
					// Create User Default Settings
					$userSettings = new USER_SETTINGS($DB_con);
					$userSettingsData = $userSettings->addDefaultUserSettings($userid);
					
					// Now save Avatar 
					$avatar = new AVATAR($DB_con);
					$saveUserAvatarData = $avatar->saveUserAvatar($userid, $avatarData);
		
					if ($saveUserAvatarData["result"] == true)
					{
						$saveUserAvatarImage = $avatar->saveUserAvatarImage($userid, $avatarData);

						if ($saveUserAvatarImage["result"] == true)
						{
							
							//Create Stripe Customer
							$payment = new PAYMENT($DB_con);
							$customer = $payment->createCustomer($userid, $umail, 0);

							//Update Stripe Customer in user table
							$user->updateUserPayment($userid, $customer->id, 0);
							
							$saveUserAvatarImage["OK"] = "User registered!";
							// $saveUserAvatarImage[] = $userSettingsData["user_settings"];
					
							echo json_encode($saveUserAvatarImage);
						}
					}
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
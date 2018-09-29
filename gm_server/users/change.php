<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	require_once '../dbconfig.php';
	
	$xsrf = MD5(time());
	$isAlreadyGenerated = false;
	$isPasswordNoMatch = false;
	$isPasswordChanged = false;
	$isPasswordUpdateError = false;
	$isPasswordEmpty = false;
	
	if(isset($_POST['_xsrf']))
	{
		// If here, user has submitted new password
		// We will need to check if both password fields are a match
		
		$password = trim($_POST['password']);
		$passwordConfirm = trim($_POST['password-confirm']);
		$uid = $_POST['_uid'];
		$vid = $_POST['_vid'];
		
		// Check Length
		if (empty($password) || empty($passwordConfirm)) {
			
			$isPasswordEmpty = true;
		}
		
		if (!$isPasswordEmpty)
		{
			if ($password == $passwordConfirm)
			{
				$passwordInfo = [];
				$passwordInfo["uid"] = $uid;
				$passwordInfo["password"] = $password;
				
				$response = $user->updateUserPassword($passwordInfo);
				if ($response["result"] == true)
				{
					$isPasswordChanged = true;
					$remove = $user->removeChangePasswordByValidationId($vid);
				}
				else
				{
					$isPasswordUpdateError = true;
				}
			}
			else
			{
				$isPasswordNoMatch = true;
			}
		}	
	}
	else if (isset($_GET['v']))
	{
		$uid = $_GET['id'];
		$vid = $_GET['v'];
		// User clicked on email change password button
		// We need to check if this validationid exists
		$exists = $user->checkChangePasswordIdExistsByValidationId($_GET['v']);
		
		if (count($exists) == 0)
		{
			// Change Password has already been generated, delete and make a new one
			$isAlreadyGenerated = true;
		}
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
	<link href="https://fonts.googleapis.com/css?family=Muli:300,400,700" rel="stylesheet">
	<link href="css/goalie_styles.css" rel="stylesheet" type="text/css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	
</head>

<style>
	
.logo {
	
	background-image: url(images/goaliemind_logo.svg);
    display: block;
    text-indent: -9999px;
    overflow: hidden;
    background-repeat: no-repeat;
    width: 200px;
    height: 140px;
    position: relative;
}
	
.pure-button, a.pure-button, button.pure-button {
    background-color: #01a6cf;
    padding: 8px 16px 10px;
    border-radius: 3px;
    text-decoration: none;
    text-align: center;
    border: 0;
    background-image: none;
}
	
</style>

<script type="text/javascript">
	
	var isIE = window.isIE = (Object.hasOwnProperty.call(window, "ActiveXObject") && !window.ActiveXObject);
	isIE = (navigator.userAgent.match(/Trident\/7\./)) ? true : isIE;
	
	console.log("Running navigator.userAgent: ", navigator.userAgent);
	console.log("Running IE: ", isIE);
	
	window.isIE = isIE;
	
	$( document ).ready(function() 
	{
    	console.log( "ready!" );
		
		
	});
	
</script>

<body>
<div class="content-auth">
	<div class="auth-box">
		<div class="auth-info">
			<a href="#" class="logo">GoalieMind</a>
		</div>
		<noscript>
			&lt;div class="no-script-warning"&gt;JavaScript is required to use GoalieMind. Please enable JavaScript and try again.&lt;/div&gt;
		</noscript>
		
		<form class="reset-form auth-form pure-form pure-form-stacked" accept-charset="UTF-8" action="/goiliemind/users/change.php" method="post">
			<input type="hidden" name="_xsrf" value="<?php echo $xsrf; ?>">
			<input type="hidden" name="_vid" value="<?php echo $vid; ?>">
			<input type="hidden" name="_uid" value="<?php echo $uid; ?>">
			<fieldset>
				<?php if ($isAlreadyGenerated) { ?>
		
					<legend>This link is invalid or has expired.</legend>
		
				<?php } else if ($isPasswordChanged) { ?>
		
					<legend>Password has been updated.</legend>
		
				<?php } else { ?>
				
					<legend>Password Reset</legend>
				
				<div class="pure-control-group">
					<input id="login-password" type="password" placeholder="New Password" name="password" maxlength="15">
				</div>
				
				<div class="pure-control-group">
					<input id="login-password" type="password" placeholder="Confirm New Password" name="password-confirm" maxlength="15">
				</div>

				<div class="pure-controls">
					<?php if ($isPasswordNoMatch) { ?>
						
						<div class="alert alert-danger auth-alert reset-alert">Passwords don't match</div>
					
					<?php } else if ($isPasswordUpdateError) { ?>
					
						<div class="alert alert-danger auth-alert reset-alert">There was an error in updating the password.  Please try again.</div>
						
					<?php } else if ($isPasswordEmpty) { ?>
					
						<div class="alert alert-danger auth-alert reset-alert">Please enter your new password.</div>
					
					<?php } ?>
					<button type="submit" class="pure-button pure-button-primary">Change Password</button>
					
				</div>
	
				<?php } ?>
	
			</fieldset>
		</form>
		
		
	</div>
</div>

</body>
</html>
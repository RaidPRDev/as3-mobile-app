<?php
require_once 'dbconfig.php';


if(isset($_POST['btn-signup']))
{
	$uname = trim($_POST['form_uname']);
	$umail = trim($_POST['form_umail']);
	$upass = trim($_POST['form_upass']);	
	
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
				if ($user->register($fname, $lname, $uname, $umail, $upass))	
				{
					echo "Registration Successfull!!";
					// $user->redirect('sign-up.php?joined');
				}
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
	foreach($error as $error)
	{
		echo "FuckYou!" . $error . '<br/>';
	}
}




?>
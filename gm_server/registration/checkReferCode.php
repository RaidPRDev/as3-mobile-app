<?php
require_once '../dbconfig.php';

if(isset($_GET['checkReferCode']))
{
	$referCode = trim($_GET['form_referCode']);
	$result_referid = $user->fetchReferId($referCode);

	//$referid = -1 for Not Found
	//$referid = -2 for Already Used
	
	if ($result_referid['refer_id'] == -1)
	{
		$response["ERROR"] = "Sorry, invalid reference code!";
	}
	else if ($result_referid['refer_id'] == -2)
	{
		$response["ERROR"] = "Sorry, this reference code has already taken!";
	}
	else
	{
		$response["OK"] = true;
	}
	
	echo json_encode($response);
}
?>
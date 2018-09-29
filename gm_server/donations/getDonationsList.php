<?php

require_once '../dbconfig.php';

include_once 'class.donations.php';

if(isset($_GET['login']))
{
	$donations = new DONATIONS($DB_con);
	
	// Get the donations list
	$donationsList = $donations->getDonationsList(0, 500);
	
	if ($donationsList["result"] == true)
	{
		$response["donations"] = $donationsList["donations"];
		$response["OK"] = "success";
	}
	else $response["ERROR"] = "An error has occurred when accessing the server. Please try again.";
	
	echo json_encode($response);
}

?>
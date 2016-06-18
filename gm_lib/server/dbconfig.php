<?php

session_start();

/*$DB_host = "raidp001.mysql.guardedhost.com";
$DB_user = "raidp001_goalie";
$DB_pass = "Fania4093";
$DB_name = "raidp001_goalie";

$DB_host = "localhost:3307";
$DB_user = "root";
$DB_pass = "usbw";
$DB_name = "goaliemind";*/

// $DB_host = "ec2-52-91-212-191.compute-1.amazonaws.com"; // If we want to access db from another server
$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "554093";
$DB_name = "goaliemind";

try
{
	$DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
	$DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	echo $e->getMessage();
}

include_once 'class.project.php';
include_once 'class.user.php';
$user = new USER($DB_con);
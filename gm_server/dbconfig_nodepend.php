<?php

session_start();

$useLocal = false;

if ($useLocal == true)	// Development
{
	
}
else	// Production
{
	
}

try
{
	$DB_con = new PDO("mysql:host=$DB_host; dbname=$DB_name", $DB_user, $DB_pass);
	$DB_con->exec("SET CHARACTER SET utf8");      // Sets encoding UTF-8
}
catch(PDOException $e)
{
	echo $e->getMessage();
}

$GOALIEMIND_ACCOUNT = 1;
$ORGANIZATION_MINDGOALIE = 2;
$PERSONAL_MINDGOALIE = 3;

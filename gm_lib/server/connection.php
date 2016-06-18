<?php

	$hostname = 'raidp001.mysql.guardedhost.com';
	$dbusername = 'raidp001_goalie';
	$dbpassword = 'Fania4093';
	$database = 'raidp001_goalie';
	
	/*$hostname = 'localhost';
	$dbusername = 'root';
	$dbpassword = '554093';
	$database = 'myguide';*/
	
	$conn = new PDO("mysql:host=$hostname; dbname=$database", $dbusername, $dbpassword);
	$conn->exec("SET CHARACTER SET utf8");      // Sets encoding UTF-8

	// Define the SQL statement that will be applied in prepare()
	// $sql = "SELECT 'username' FROM 'users' WHERE 'id'= :id OR 'category'= :category";
	$sql = "SELECT * FROM `users`";
	$sqlprep = $conn->prepare($sql);        // Prepares and stores the SQL statement in $sqlprep

	// The array with values that must be added in the SQL instruction (for ':id', and ':category')
	// $ar_val = array('id'=>2, 'category'=>'education');

	if ($sqlprep->execute()) 
	{
		// gets and displays the data returned by MySQL
		while($row = $sqlprep->fetch()) echo $row['id'].' - '.$row['username'].'<br />';
	}

	
	
	
	/*// Opens a connection to a mySQL server HOSTNAME, USERNAME, PASSWORD
	$connection = mysql_connect ($hostname, $dbusername, $dbpassword);
	if (!$connection) {
		$response["success"] = 0;
		$response["message"] = "Can't connect to Server, try again later.";
		echo json_encode($response); 
		exit;
	}

	// Set the active mySQL database
	$db_selected = mysql_select_db($database, $connection);
	if (!$db_selected) {
		// die ("Can\'t use db : " . mysql_error());
		$response["success"] = 0;
		$response["message"] = "Database not connected.";
		echo json_encode($response); 
		exit;
	}	*/

?>
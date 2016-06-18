<?php

require_once '../dbconfig.php';

echo "/////////////// fetchall(PDO::FETCH_ASSOC)<br/><br/>";

$stmt = $DB_con->query("SELECT * FROM users");

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// PDO::FETCH_ASSOC, FETCH_NUM, FETCH_BOTH

// This output gets the data, but loads all the data array in one blast. Also as in fetch(), it comes in both Associated and Numbered arrays. 
// This method also uses more memory because it loads the entire array in one variable - $results
// fetch() does it by row and so there is less memory usage, but more queries to the DB. 

foreach ($results as $row)
{
	// echo htmlentities($row['username']) . ' ' . htmlentities($row['password']) . '<br/>';
	echo '<pre>' . var_dump($row) . '</pre>';
}

var_dump($DB_con);

?>
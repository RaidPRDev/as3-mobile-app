<?php

require_once '../dbconfig.php';

echo "/////////////// fetch(PDO::FETCH_BOTH)<br/><br/>";

$stmt = $DB_con->query("SELECT * FROM users");

// This output gets the data, but with both Associated and Numbered arrays. Not recommended

while ($row = $stmt->fetch())
{
	// echo htmlentities($row['username']) . ' ' . htmlentities($row['password']) . '<br/>';
	echo '<pre>' . var_dump($row) . '</pre>';
}

echo "/////////////// fetch(PDO::FETCH_ASSOC)<br/><br/>";

$stmt = $DB_con->query("SELECT * FROM users");

// This output gets the data, in an Associated array. Recommended

while ($row2 = $stmt->fetch(PDO::FETCH_ASSOC)) 
{
	// echo htmlentities($row['username']) . ' ' . htmlentities($row['password']) . '<br/>';
	echo '<pre>' . var_dump($row2) . '</pre>';
}

echo "/////////////// fetch(PDO::FETCH_NUM)<br/><br/>";

$stmt = $DB_con->query("SELECT * FROM users");

// This output gets the data, in an Associated array. Recommended

while ($row2 = $stmt->fetch(PDO::FETCH_NUM)) 
{
	// echo htmlentities($row['username']) . ' ' . htmlentities($row['password']) . '<br/>';
	echo '<pre>' . var_dump($row2) . '</pre>';
}


var_dump($DB_con);

?>


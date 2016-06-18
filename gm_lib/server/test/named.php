<?php

require_once '../dbconfig.php';

echo "// PREPARED NAMED PARAMS -> prepare( SQL QUERY )<br/>";
echo "// using :value as placeholder<br/><br/>";

$uname = "fania";

$stmt = $DB_con->prepare("SELECT * FROM users WHERE username = :username");
$stmt->bindParam(':username', $uname);
$stmt->execute();
// This output gets the data, but with both Associated and Numbered arrays. Not recommended

while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
	echo htmlentities($row['username']) . ' ' . htmlentities($row['password']) . '<br/>';
	// echo '<pre>' . var_dump($row) . '</pre>';
}

var_dump($DB_con);

?>


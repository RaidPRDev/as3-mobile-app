<?php

require_once '../dbconfig.php';

echo "// PREPARED STATEMENTS -> prepare( SQL QUERY )<br/>";
echo "// using ? as placeholder<br/><br/>";

$stmt = $DB_con->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bindValue(1, 'fania');
$stmt->execute();
// This output gets the data, but with both Associated and Numbered arrays. Not recommended

while ($row = $stmt->fetch())
{
	// echo htmlentities($row['username']) . ' ' . htmlentities($row['password']) . '<br/>';
	echo '<pre>' . var_dump($row) . '</pre>';
}


echo "// PREPARED STATEMENTS prepare( SQL QUERY using LIKE for searching )<br/>";
echo "// using ? as placeholder<br/><br/>";

$searchKey = "%k%";

$stmt = $DB_con->prepare("SELECT * FROM users WHERE username LIKE ?");
$stmt->bindParam(1, $searchKey);
$stmt->execute();
// This output gets the data, but with both Associated and Numbered arrays. Not recommended

while ($row = $stmt->fetch())
{
	// echo htmlentities($row['username']) . ' ' . htmlentities($row['password']) . '<br/>';
	echo '<pre>' . var_dump($row) . '</pre>';
}


echo "// PREPARED STATEMENTS prepare( SQL QUERY using ARRAYS to bind query )<br/>";
echo "// using ? as placeholder<br/><br/>";

$stmt = $DB_con->prepare("SELECT * FROM users WHERE username LIKE ?");

$searchKeys = array("%k%", "%e%", "%i%");

foreach ($searchKeys as $key)
{
	$stmt->bindParam(1, $key);
	$stmt->execute();

	// This output gets the data, but with both Associated and Numbered arrays. Not recommended

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		echo htmlentities($row['username']) . ' ' . htmlentities($row['password']) . '<br/>';
		// echo '<pre>' . var_dump($row) . '</pre>';
	}	
}




var_dump($DB_con);

?>


<?php

require_once '../dbconfig.php';

echo "// PREPARED FETCH_OBJ -> prepare( SQL QUERY )<br/>";
echo "// using :value as placeholder<br/><br/>";

$uname = "fania";

$stmt = $DB_con->prepare("SELECT * FROM users");  //  WHERE username = :username
// $stmt->bindParam(':username', $uname);

echo '<pre>';

if ($stmt->execute())
{
	while ($row = $stmt->fetch(PDO::FETCH_OBJ))
	{
		// echo htmlentities($row['username']) . ' ' . htmlentities($row['password']) . '<br/>';
		var_dump($row);
	}
}

echo '</pre>';

echo "// PREPARED FETCH_OBJ -> prepare( SQL QUERY )<br/>";
echo "// using :value as placeholder<br/><br/>";

$uname = "fania";

$stmt = $DB_con->prepare("SELECT * FROM users");  //  WHERE username = :username
// $stmt->bindParam(':username', $uname);

echo '<pre>';

if ($stmt->execute())
{
	$rows = $stmt->fetchAll(PDO::FETCH_OBJ);
	print_r($rows);
}

echo '</pre>';


echo "// PREPARED FETCH_OBJ using fetchObject() -> prepare( SQL QUERY )<br/>";
echo "// using :value as placeholder<br/><br/>";

$uname = "fania";

$stmt = $DB_con->prepare("SELECT * FROM users WHERE username = :username");
$stmt->bindParam(':username', $uname);

echo '<pre>';

if ($stmt->execute())
{
	$rows = $stmt->fetchObject();
	print_r($rows);
}

echo '</pre>';


echo "// PREPARED FETCH_OBJ using fetchObject() mapped to Class User -> prepare( SQL QUERY )<br/>";
echo "// using :value as placeholder<br/><br/>";

class UserProfile
{
	public function get ($value)
	{
		if ( property_exists( $this, $value ) )
		{
			return $this->$value;
		}
	
		return false;
	}
}

$uname = "fania";

$stmt = $DB_con->prepare("SELECT * FROM users WHERE username = :username");
$stmt->bindParam(':username', $uname);

echo '<pre>';

if ($stmt->execute())
{
	$rows = $stmt->fetchObject("UserProfile");
	// print_r($rows);
	echo "username: " . $rows->get('username');
}

echo '</pre>';


var_dump($DB_con);

?>


<?php

require_once '../dbconfig.php';

echo "// PREPARED FETCH_OBJ -> prepare( SQL QUERY )<br/>";
echo "// using :value as placeholder<br/><br/>";

$fieldname = "id";

$stmt = $DB_con->prepare("SELECT count(:fieldname) AS total FROM users");  //  WHERE username = :username
$stmt->bindParam(':fieldname', $fieldname);

echo '<pre>';

if ($stmt->execute())
{
	$total_records = $stmt->fetch(PDO::FETCH_COLUMN);
	echo "Total Users: " . $total_records;
}

echo '</pre>';		

?>
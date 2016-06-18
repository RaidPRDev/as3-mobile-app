<?php

// use this to go back to the previous values of the item.  After execute sometimes we will encounter errors, these can be handled with beginTransaction
// PDO is autocommit by default, but by calling PDO->beginTransaction() we disable autocommit and will have to commit the new data manually/explicitly
// We do this by calling PDO->commit(). 
// But as stated above, if for some reason we have some strange error happening after execute.  We can capture this error, and rollBack if needed.
// After a PDO->commit() is called, to PDO->rollBack() you will need to call PDO->beginTransaction() again.

require_once '../dbconfig.php';

echo "// PREPARED FETCH_OBJ -> prepare( SQL QUERY )<br/>";
echo "// using :value as placeholder<br/><br/>";

$uname = "you";
$unewname = "raid2k";

$DB_con->beginTransaction();

$stmt = $DB_con->prepare("UPDATE users set username = ? WHERE username = ?");  
$stmt->bindValue(1, $unewname);
$stmt->bindValue(2, $uname);

echo '<pre>';

if ($stmt->execute())
{
	// #IF Found an error! Call rollBack do not commit!
	// $DB_con->rollBack();  
	// #ELSE commit changes  
	$DB_con->commit();
}

echo '</pre>';

?>


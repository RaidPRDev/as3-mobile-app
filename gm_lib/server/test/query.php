<?php

require_once '../dbconfig.php';

foreach($DB_con->query("SELECT * FROM users") as $row)
{
	echo htmlentities($row['username']) . ' ' . htmlentities($row['password']) . '<br/>';

}

var_dump($DB_con);

?>
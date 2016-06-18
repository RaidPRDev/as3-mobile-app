<?php

require_once '../dbconfig.php';

$uname = "kid";

// BINDPARAM

$stmt = $DB_con->prepare("DELETE FROM users WHERE username = :username");
							
$stmt->bindParam(':username', $uname);
$stmt->execute();

var_dump($DB_con);



?>
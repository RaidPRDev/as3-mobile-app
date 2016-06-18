<?php

require_once '../dbconfig.php';

$uname = "fania";
$upass = "Fania41212";
$pass = MD5($upass);

// BINDVALUE

$stmt = $DB_con->prepare("INSERT INTO users (username, password)
							VALUES (?, ?)");
							
$stmt->bindValue(1, $uname);
$stmt->bindValue(2, $upass);
$stmt->execute();

// BINDPARAM

$stmt = $DB_con->prepare("INSERT INTO users (username, password)
							VALUES (:username, :password)");
							
$stmt->bindParam(':username', $uname);
$stmt->bindParam(':password', $upass);
$stmt->execute();


// BINDPARAM UPDATE
$signedInDate = "1977";
$stmt = $DB_con->prepare("UPDATE users set signedInDate = :signedInDate
							WHERE username = :username");
							
$stmt->bindParam(':username', $uname);
$stmt->bindParam(':signedInDate', $signedInDate);
$stmt->execute();

var_dump($DB_con);



?>
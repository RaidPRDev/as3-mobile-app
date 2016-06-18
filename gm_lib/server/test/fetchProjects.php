<?php

require_once '../dbconfig.php';

echo "/////////////// fetch projects (PDO::FETCH_ASSOC)<br/>";
echo 'By default bindValue binds a string value but limit is an integer, so use PDO::PARAM_INT<br/><br/>';

$userid = 16;
$start = 2;
$offset = 4;

/*//$sql = "SELECT description, timestamp FROM projects WHERE userid = :userid ORDER BY id LIMIT :start OFFSET :offset";
$sql = "SELECT description, timestamp FROM projects WHERE userid = 16 ORDER BY DESC LIMIT ? OFFSET ?";
$stmt = $DB_con->prepare($sql);
//$stmt->bindValue(1, $userid, PDO::PARAM_STR);
$stmt->bindValue(1, $start, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) 
{
  $data = $row['description'] . "\t" . $row['timestamp'] . "<br/>";
  print $data;
}
*/

$skip = 2;
$max = 4;

$DB_con->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

$fetchProjects = $DB_con->prepare("SELECT * 
    FROM projects 
    WHERE userid = :userid 
    ORDER BY id ASC 
    LIMIT :skip, :max");

$fetchProjects->bindValue(':userid', trim($userid), PDO::PARAM_INT);

if(isset($skip)) {
    $fetchProjects->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
} else {
    $fetchProjects->bindValue(':skip', 0, PDO::PARAM_INT);  
}

$fetchProjects->bindValue(':max', $max, PDO::PARAM_INT);
$fetchProjects->execute() or die(print_r($fetchProjects->errorInfo()));
$projects = $fetchProjects->fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
//var_dump($pictures);
echo json_encode($projects);
?>


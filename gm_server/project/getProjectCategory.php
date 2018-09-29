<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../dbconfig.php';

$project = new PROJECT($DB_con);
$projectCatList = $project->getProjectCategoryList();
echo json_encode($projectCatList);


?>
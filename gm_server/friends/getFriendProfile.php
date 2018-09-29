<?php

require_once '../dbconfig.php';

include_once 'class.friends.php';
include_once '../avatar/class.avatar.php';
include_once '../avatar/class.avatar.data.php';

if(isset($_GET['profileData']))
{

	$uid = trim($_GET['uid']);
	$friendProfile = $user->getUserInfo($uid)["userInfo"][0];
	$response["profile"] = $friendProfile;
	
	$avatar = new AVATAR($DB_con);
	$fetchUserAvatarByUserId = $avatar->fetchUserAvatarByUserId($uid);
	$response["userAvatarData"] = $fetchUserAvatarByUserId;

	$projects = new PROJECT($DB_con);
	$fetchUserTotalProjectsByUserId = $projects->fetchUserTotalProjectsByUserId($uid);
	$response["projectsTotal"] = $fetchUserTotalProjectsByUserId["projectsTotal"];

	$friends = new FRIENDS($DB_con);
	$fetchUserTotalFriendsByUserId = $friends->fetchUserTotalFriendsByUserId($uid);
	$response["friendsTotal"] = $fetchUserTotalFriendsByUserId["friendsTotal"];
	
	echo json_encode($response);

}
else
{
	// Error when authenticating user
	if (!$error) $error[] = "Wrong Details!";
	$response["ERROR"] = $error;
	echo json_encode($response);
}
?>
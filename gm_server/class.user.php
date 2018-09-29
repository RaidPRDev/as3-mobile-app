<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
class USER
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	// if ($user->register($uname, $umail, $upass, $acctype, $ugender, $ubirth, $uregdate, $usigndate, $parentid))
	public function register($uname, $umail, $upass, $acctype, $ugender, $ubirth, $uregdate, $usigndate, $parentid, $referid, $userstars, $organisationreferid, $uprovider, $provider_uid)
	{
		try
		{
			$new_password = MD5($upass);
			
			$stmt = $this->db->prepare("INSERT INTO users(username,password,useremail,accountType,gender,birthDate,registerDate,signedInDate,parentid,refer_id,stars,organisation_refer_id,provider,provider_uid) 
 							VALUES(:uname, :upass, :umail, :accountType, :gender, :birthdate, :registerDate, :signedInDate, :parentid, :refer_id, :stars, :organisationreferid, :provider, :provideruid)");
												  
			$stmt->bindparam(":uname", $uname);
			$stmt->bindparam(":upass", $new_password);
			$stmt->bindparam(":umail", $umail);										  
			$stmt->bindparam(":accountType", $acctype);										  
			$stmt->bindparam(":gender", $ugender);										  
			$stmt->bindparam(":birthdate", $ubirth);										  
			$stmt->bindparam(":registerDate", $uregdate);										  
			$stmt->bindparam(":signedInDate", $usigndate);										  
			$stmt->bindparam(":parentid", $parentid);
			$stmt->bindparam(":refer_id", $referid);										  
 			$stmt->bindparam(":stars", $userstars);										  
			$stmt->bindparam(":organisationreferid", $organisationreferid);										  
			$stmt->bindparam(":provider", $uprovider);										  
			$stmt->bindparam(":provideruid", $provider_uid);										  
				
			$stmt->execute();

			//Fetch User_id
             $lastinsertid = $this->db->lastInsertId();
 
             //Set user code in user table
             $this->setUserCode($lastinsertid);
 			
             //Update user_code_status of $referid
             if($referid != 0)
             {
             	$this->updateUserCodeStatus($referid);
             }
 			
	        /*//Create Stripe Customer
			$payment = new PAYMENT($this->db);
			$customer = $payment->createCustomer($lastinsertid, $umail, 0);

			//Update Stripe Customer in user table
			$this->updateUserPayment($lastinsertid, $customer->id, 0);*/
					
			return $stmt;	
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}
 
 	/*Set user code start*/
 	public function setUserCode($userid)
 	{
 		try
 		{
 			$flag = true;
 			while($flag)
 			{
 				$user_code = strtoupper(substr(md5($userid.time()),0,8));
 				$sel_stmt = $this->db->prepare("SELECT id FROM users WHERE user_code = :user_code");
 				$sel_stmt->bindparam(":user_code", $user_code, PDO::PARAM_STR);
 				$sel_stmt->execute();
 				if($sel_stmt->rowCount() == 0)
 				{
 					break;
 				}
 			}
 
 			$update_stmt = $this->db->prepare("UPDATE users SET user_code = :user_code WHERE id = :userid");
 			$update_stmt->bindparam(":user_code", $user_code, PDO::PARAM_STR);
 			$update_stmt->bindparam(":userid", $userid, PDO::PARAM_INT);
 			$update_stmt->execute();
 		}
 		catch(PDOException $e)
 		{
 			$response["ERROR"] = $e->getMessage();
			return $response;
 		}				
 	}
 	/*Set user code end*/
 
 	/*Update user_code_status of $referid start*/
 	public function updateUserCodeStatus($referid)
 	{
 		try
 		{
 			$user_code_status = '1';
 			$update_stmt = $this->db->prepare("UPDATE users SET user_code_status = :user_code_status WHERE id = :referid");
 			$update_stmt->bindparam(":user_code_status", $user_code_status, PDO::PARAM_INT);
 			$update_stmt->bindparam(":referid", $referid, PDO::PARAM_INT);
 			$update_stmt->execute();
 		}
 		catch(PDOException $e)
 		{
 			$response["ERROR"] = $e->getMessage();
			return $response;
 		}				
 	}
 	/*Update user_code_status of $referid end*/
 
 	/*Fetch Refer Id Start*/
 	public function fetchReferId($referCode)
 	{
 		try
 		{
 			$stmt = $this->db->prepare("SELECT id,accountType,refer_id,organisation_refer_id FROM users WHERE user_code = :referCode");
 			$stmt->bindparam(":referCode", $referCode, PDO::PARAM_STR);
 			$stmt->execute();
			if($stmt->rowCount() == 0)
 			{
 				$response['refer_id'] = -1;   //Not Found
 				$response['organisation_refer_id'] = 0; 
 			}
 			else
 			{
 
 				$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
 				$refer_id = $result[0]['id']; //id of referCode
 				$organisation_refer_id1 = $result[0]['refer_id']; //reference id of referCode
 				$account_type = $result[0]['accountType'];
 				$organisation_refer_id2 = $result[0]['organisation_refer_id'];
 
 				$response['refer_id'] = $refer_id;  //Refer id of user registration
 				if($account_type == 2)
 				{
 					//organisation_refer_id will be 0, of user registration if account type of referral is 2
 					$response['organisation_refer_id'] = 0; 
 				}
 				else if($account_type == 3 && $organisation_refer_id2 == 0)
 				{
 					$response['organisation_refer_id'] = $organisation_refer_id1; //refer id referal user
 				}
 				else
 				{
 					$response['organisation_refer_id'] = $organisation_refer_id2; //organisation_refer_id referal user
 				}
 			}
			
 			return $response;
 		}
 		catch(PDOException $e)
 		{
 			$response["ERROR"] = $e->getMessage();
			return $response;
 		}				
 	} 
	/*Fetch Refer Id End*/

	public function login($uname,$umail,$upass)
	{
		/*
		$stmt = $this->db->prepare("SELECT * FROM users WHERE username=:uname OR useremail=:umail LIMIT 1");
		$stmt->execute(array(':uname'=>$uname, ':umail'=>$umail));
		*/
		// Check if user is trying to use an email or username
		if( filter_var($umail, FILTER_VALIDATE_EMAIL) )	$uname = null; else $umail = null;
		
		try
		{
			if (!is_null($umail))	// User is using an email for AccountTypes 1 or 3  ( GoalieMind and MindGoalie Orgranizations )
			{
				$stmt = $this->db->prepare("SELECT * FROM users WHERE useremail=:umail AND (accountType=1 OR accountType=2) LIMIT 1");
				$stmt->execute(array(':umail'=>$umail));
			}
			else	// User is using username
			{
				$stmt = $this->db->prepare("SELECT * FROM users WHERE username=:uname LIMIT 1");
				$stmt->execute(array(':uname'=>$uname));
			}
			
			$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() > 0)
			{
				$isMindGoaliePersonal = false;
				$savedPassword = $upass;
				
				// Check if its an MD5 password from GoalieMind UserManager
				// Parent GoalieMind can change MindGoalie Personal Passwords at any time.
				if (strlen($upass) == 32) $isMindGoaliePersonal = true;
				else $savedPassword = MD5($upass);
				
				if($userRow['password'] == $savedPassword)
				// if($userRow['password']==MD5($upass))
				{
					$_SESSION['user_session'] = $userRow['id'];
					$_SESSION['username'] = $userRow['username'];
					$_SESSION['useremail'] = $userRow['useremail'];
					$_SESSION['accountType'] = $userRow['accountType'];
					$_SESSION['parentid'] = $userRow['parentid'];
					$_SESSION['gender'] = $userRow['gender'];
					$_SESSION['birthDate'] = $userRow['birthDate'];
					$_SESSION['isAuthorized'] = $userRow['isAuthorized'];
					$_SESSION['authorizeKey'] = $userRow['authorizeKey'];
					$_SESSION['registerDate'] = $userRow['registerDate'];
					$_SESSION['signedInDate'] = $userRow['signedInDate'];
					$_SESSION['customerid'] = $userRow['customerid'];
					$_SESSION['paymentid'] = $userRow['paymentid'];	// Default Payment
 					$_SESSION['referid'] = $userRow['refer_id'];	
 					$_SESSION['organisationreferid'] = $userRow['organisation_refer_id'];	
 					$_SESSION['user_code'] = $userRow['user_code'];	
 					$_SESSION['stars'] = number_format($userRow['stars'],0);	
					$_SESSION['wallet'] = number_format($userRow['wallet'],2);
 					$_SESSION['provider'] = $userRow['provider'];	
 					$_SESSION['provider_uid'] = $userRow['provider_uid'];	
		
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e-> getMessage();
			echo json_encode($response);
		}
	}
	
	public function updateUserPayment($uid, $customerid, $paymentid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("UPDATE users SET 
					users.customerid = :customerid,
					users.paymentid = :paymentid
				WHERE users.id = :uid");
				
			$stmt->bindValue(':customerid', $customerid, PDO::PARAM_INT);
			$stmt->bindValue(':paymentid', $paymentid, PDO::PARAM_INT);
			$stmt->bindValue(':uid', $uid, PDO::PARAM_INT);
			
			if ($stmt->execute())
			{
				$response["result"] = true;
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $stmt->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			// echo $e->getMessage() . "userid: " . $info["uid"];
			$response["result"] = false;
			$response["ERROR"] = $e->getMessage();
		}				
	}	

	public function updateUserAuthorization($info)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("UPDATE users SET 
					users.isAuthorized = :isAuthorized
				WHERE users.id = :uid");
				
			$stmt->bindValue(':isAuthorized', $info["isAuthorized"], PDO::PARAM_INT);
			$stmt->bindValue(':uid', $info["uid"], PDO::PARAM_INT);
			$stmt->execute() or die(print_r($stmt->errorInfo()));
			
			return $stmt;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}	
	
	public function updateUserProfile($info)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("UPDATE users SET 
					users.username = :username,
					users.useremail = :useremail,
					users.gender = :gender,
					users.birthDate = :birthDate
				WHERE users.id = :uid");
				
			$stmt->bindValue(':username', $info["newUsername"], PDO::PARAM_STR);
			$stmt->bindValue(':useremail', $info["newEmail"], PDO::PARAM_STR);
			$stmt->bindValue(':gender', $info["gender"], PDO::PARAM_STR);
			$stmt->bindValue(':birthDate', $info["birthDate"], PDO::PARAM_STR);
			$stmt->bindValue(':uid', $info["uid"], PDO::PARAM_INT);
			
			if ($stmt->execute())
			{
				$response["result"] = true;
			}
			else 
			{
				$response["result"] = false;
			}
			
			return $response;
			
		}
		catch(PDOException $e)
		{
			$response["result"] = false;
			$response["ErrorMessage"] = $e->getMessage();
			return $response;
		}				
	}	
	
	public function updateUserPassword($info)
	{
		if (strlen($info["password"]) == 32)
		{
			$newPassword = $info["password"];
		}
		else $newPassword = MD5($info["password"]);
		
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("UPDATE users SET 
					users.password = :password
				WHERE users.id = :uid");
				
			$stmt->bindValue(':password', $newPassword, PDO::PARAM_STR);
			$stmt->bindValue(':uid', $info["uid"], PDO::PARAM_INT);
			
			if ($stmt->execute())
			{
				$response["result"] = true;
			}
			else 
			{
				$response["result"] = false;
			}
			
			return $response;
			
		}
		catch(PDOException $e)
		{
			$response["result"] = false;
			$response["ErrorMessage"] = $e->getMessage();
			return $response;
		}				
	}	
	
	public function updateUserEmail($info)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("UPDATE users SET 
					users.useremail = :useremail
				WHERE users.id = :uid");
			
			$stmt->bindValue(':useremail', $info["email"], PDO::PARAM_STR);	
			$stmt->bindValue(':uid', $info["uid"], PDO::PARAM_INT);
			
			if ($stmt->execute())
			{
				$response["result"] = true;
			}
			else 
			{
				$response["result"] = false;
			}
		}
		catch(PDOException $e)
		{
			$response["result"] = false;
			$response["ErrorMessage"] = $e->getMessage();
			return $response;
		}				
	}		
	
	public function getUserAvatarThumb($userid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$fetchAvatarThumb = $this->db->prepare("SELECT thumb
				FROM user_avatar 
				WHERE userid = :userid");
				
			$fetchAvatarThumb->bindValue(':userid', $userid, PDO::PARAM_INT);

			$fetchAvatarThumb->execute() or die(print_r($fetchAvatarThumb->errorInfo()));
			
			return $fetchAvatarThumb->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			$response["result"] = false;
			$response["ErrorMessage"] = $e->getMessage();
			return $response;
		}	
	}	
	
	//////////////////////////////////////
	// PROJECT CREATION
	//////////////////////////////////////
	
	public function createProject($userid, $description, $tags, $acctype, $timestamp, $month, $year, $imageName, $thumbName)
	{
		try
		{
			$stmt = $this->db->prepare("INSERT INTO projects(userid,description,tags,type,timestamp,month,year,image,thumb) 
							VALUES(:userid, :description, :tags, :accountType, :timestamp, :month, :year, :image, :thumb)");
												  
			$stmt->bindparam(":userid", $userid);
			$stmt->bindparam(":description", $description);
			$stmt->bindparam(":tags", $tags);										  
			$stmt->bindparam(":accountType", $acctype);										  
			$stmt->bindparam(":timestamp", $timestamp);										  
			$stmt->bindparam(":month", $month);										  
			$stmt->bindparam(":year", $year);										  
			$stmt->bindparam(":image", $imageName);										  
			$stmt->bindparam(":thumb", $thumbName);										  
				
			$stmt->execute();	
			
			$_SESSION['newprojectid'] = $this->db->lastInsertId();
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}	
	
	public function updateProjectMedia($projectid, $imgFilename, $month, $year)
	{
		try
		{
			$stmt = $this->db->prepare("UPDATE projects SET image=:image, month=:month,year=:year WHERE id=:projectid");
												  
			$stmt->bindparam(":image", $imgFilename);
			$stmt->bindparam(":month", $month);
			$stmt->bindparam(":year", $year);
			$stmt->bindparam(":projectid", $projectid);
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}		
	
	
	public function is_loggedin()
	{
		if(isset($_SESSION['user_session']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function logout()
	{
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}
	
	//public function searchUsers($uid, $searchTerm, $excludeUserIds, $skip = 0, $max = 5)
	public function searchUsers($searchTerm, $excludeUserIds, $skip = 0, $max = 5)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$searchTerm = "%" . $searchTerm . "%";	
			
			if (!is_null($excludeUserIds))
			{
				$fetchUsers = $this->db->prepare("SELECT 
					users.id,
					users.username, 
					users.useremail,
					user_avatar.thumb
				FROM users 
				INNER JOIN user_avatar ON users.id = user_avatar.userid
				WHERE users.username LIKE :searchTerm 
				AND users.id NOT IN (".$excludeUserIds.")  
				ORDER BY users.username ASC LIMIT :skip, :max");
			}
			else
			{
				$fetchUsers = $this->db->prepare("SELECT 
					users.id,
					users.username, 
					users.useremail,
					user_avatar.thumb
				FROM users 
				INNER JOIN user_avatar ON users.id = user_avatar.userid
				WHERE users.username LIKE :searchTerm 
				ORDER BY users.username ASC LIMIT :skip, :max");
			}

			$fetchUsers->bindValue(':searchTerm', $searchTerm, PDO::PARAM_STR);
	
			if(isset($skip)) $fetchUsers->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			else $fetchUsers->bindValue(':skip', 0, PDO::PARAM_INT);  
	
			$fetchUsers->bindValue(':max', $max, PDO::PARAM_INT);
					
			if ($fetchUsers->execute())
			{
				$response["result"] = true;
				$response["usersFound"] = $fetchUsers->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchUsers->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}			
		
	}
	
	// GET USER INFO 
	
	public function getUserInfoRequest($userid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$userinfo = $this->db->prepare("SELECT *
				FROM users 
				WHERE id = :userid");
				
			$userinfo->bindValue(':userid', $userid);

			if ($userinfo->execute())
			{
				$result = $userinfo->fetchAll(PDO::FETCH_ASSOC);
				
				$response["result"] = true;
				$response["userid"] = $result[0]["id"];
				$response["username"] = $result[0]["username"];
				$response["useremail"] = $result[0]["useremail"];
				$response["accountType"] = $result[0]["accountType"];
				$response["parentid"] = $result[0]["parentid"];
				$response["gender"] = $result[0]["gender"];
				$response["birthDate"] = $result[0]["birthDate"];
				$response["isAuthorized"] = $result[0]["isAuthorized"];
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $userinfo->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["result"] = false;
			$response["ERROR"] = $e->getMessage();
			return $response;
		}	
	}
	
	public function getUserInfo($userid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			$userinfo = $this->db->prepare("SELECT *
				FROM users 
				WHERE id = :userid");
				
			$userinfo->bindValue(':userid', $userid);

			if ($userinfo->execute())
			{
				$response["userInfo"] = $userinfo->fetchAll(PDO::FETCH_ASSOC);
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $userinfo->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["result"] = false;
			$response["ERROR"] = $e->getMessage();
			return $response;
		}	
	}
	
	public function fetchUserByUserCode($usercode)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$fetchUser = $this->db->prepare("SELECT id
				FROM users 
				WHERE usercode = :usercode");
				
			$fetchUser->bindValue(':usercode', $usercode, PDO::PARAM_STR);

			$fetchUser->execute() or die(print_r($fetchUser->errorInfo()));
			
			return $fetchUser->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage() . " Could not retrieve info: " . $usercode;
		}	
	}
	
	public function fetchUserIdByUsername($username)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$fetchUser = $this->db->prepare("SELECT id
				FROM users 
				WHERE username = :username");
				
			$fetchUser->bindValue(':username', $username, PDO::PARAM_STR);

			$fetchUser->execute() or die(print_r($fetchUser->errorInfo()));
			
			return $fetchUser->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}	
	}
	
	public function fetchUserIdByProviderUID($provider_uid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$fetchUser = $this->db->prepare("SELECT *
				FROM users 
				WHERE provider_uid = :provider_uid");
				
			$fetchUser->bindValue(':provider_uid', $provider_uid, PDO::PARAM_STR);

			$fetchUser->execute() or die(print_r($fetchUser->errorInfo()));
			
			return $fetchUser->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			echo $e->getMessage() . " Could not retrieve avatar thumb image: " . $userid;
		}	
	}

	/*Add Stars to User start*/
 	public function addStarsToUser($userid, $stars)
 	{
 		try
 		{
 			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 
 
 			//retrieve value of available stars
 			$availableStars = $this->retrieveStarsOfAUser($userid);
 			if(isset($availableStars["ERROR"]))
 			{
 				die("Sorry some error has been ocurred! Please try again.");
 			}
	
			//appended value of stars
 			$appendedStars = $availableStars+$stars;
 
 			//Update the appended stars in project table
 			$stmt = $this->db->prepare("UPDATE users SET stars = :appendedStars WHERE id = :userid");	

 			$stmt->bindValue(':appendedStars', $appendedStars, PDO::PARAM_INT);
 			$stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
 			$stmt->execute() or die("Sorry some error has been ocurred! Please try again.");
 		}
 		catch(PDOException $e)
 		{
 			$response["ERROR"] = $e->getMessage();
 			return $response;
 		}
 	}
 	/*Add Stars to User end*/
 
 	/*Retrieve Star of a single user start*/
 	public function retrieveStarsOfAUser($userid)
 	{
 		try
 		{
	
 			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

 			$stmt = $this->db->prepare("SELECT stars FROM users WHERE id = :userid");
 			
 			$stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
 			
			$stmt->execute() or die("Sorry some error has been ocurred! Please try again.");

			$stars = $stmt->fetchAll(PDO::FETCH_COLUMN , 0);
 			
			return $stars[0];
		}
 		catch(PDOException $e)
 		{
	
 			$response["ERROR"] = $e->getMessage();
 			return $response;
 		}
 	}
 	/*Retrieve Star of a single project end*/ 
	
	/* Change Password Via Email */
	
	public function createChangePasswordValidation($userid, $validationid, $time)
	{
		try
		{
			$stmt = $this->db->prepare("INSERT INTO user_change_password(userid,validationid,time) 
							VALUES(:userid, :validationid, :time)");
												  
			$stmt->bindparam(":userid", $userid);
			$stmt->bindparam(":validationid", $validationid);
			$stmt->bindparam(":time", $time);
			$stmt->execute();	
			
			$response['changepasswordid'] = $this->db->lastInsertId();
			$response['result'] = true; 
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response['result'] = false; 
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}
	
	public function checkChangePasswordIdExists($changepasswordid)
 	{
 		try
 		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
 			$stmt = $this->db->prepare("SELECT userid, validationid, time FROM user_change_password WHERE id = :changepasswordid");
 			$stmt->bindValue(':changepasswordid', $changepasswordid, PDO::PARAM_INT);
			$stmt->execute();
			$response = $stmt->fetchAll(PDO::FETCH_COLUMN , 0);
			
			return $response;
		}
 		catch(PDOException $e)
 		{
 			$response["ERROR"] = $e->getMessage();
 			return $response;
 		}
 	}

	public function checkChangePasswordIdExistsByValidationId($validationid)
 	{
 		try
 		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
 			$stmt = $this->db->prepare("SELECT id, userid, validationid, time FROM user_change_password WHERE validationid = :validationid");
 			$stmt->bindValue(':validationid', $validationid, PDO::PARAM_STR);
			$stmt->execute();
			$response = $stmt->fetchAll(PDO::FETCH_COLUMN);
			return $response;
		}
 		catch(PDOException $e)
 		{
 			$response["ERROR"] = $e->getMessage();
 			return $response;
 		}
 	}
	
	public function checkChangePasswordIdExistsByUserId($userid)
 	{
 		try
 		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
 			$stmt = $this->db->prepare("SELECT * FROM user_change_password WHERE userid = :userid");
 			$stmt->bindValue(':userid', $userid, PDO::PARAM_INT);
			$stmt->execute();
			
			$response["info"] = $stmt->fetchAll(PDO::FETCH_ASSOC);
			return $response;
		}
 		catch(PDOException $e)
 		{
 			$response["ERROR"] = $e->getMessage();
 			return $response;
 		}
 	}
	
	public function updateChangePasswordValidation($changepasswordid, $userid, $validationid, $time)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("UPDATE user_change_password SET 
					userid = :userid,
					validationid = :validationid,
					time = :time
				WHERE id = :changepasswordid");
				
			$stmt->bindparam(':userid', $userid, PDO::PARAM_INT);
			$stmt->bindparam(':validationid', $validationid, PDO::PARAM_STR);
			$stmt->bindparam(':time', $time, PDO::PARAM_STR);
			$stmt->bindparam(':changepasswordid', $changepasswordid, PDO::PARAM_INT);
			
			if ($stmt->execute()) $response["result"] = true; else $response["result"] = false; 
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}	
	
	public function removeChangePasswordByValidationId($validationid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("DELETE FROM user_change_password   
				WHERE validationid = :validationid");
			
			$stmt->bindparam(':validationid', $validationid, PDO::PARAM_STR);
			
			if ($stmt->execute())
			{
				$response["result"] = true;
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $stmt->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}	
	
	public function validateAndCheckEmailOrUsername($inputField)
	{
		$uname = null;
		$umail = null;
		
		// Check if user is trying to use an email or username
		if( filter_var($inputField, FILTER_VALIDATE_EMAIL)) $umail = $inputField; else $uname = $inputField;
		
		try
		{
			if (!is_null($umail))	// User is using an email for AccountTypes 1 or 3  ( GoalieMind and MindGoalie Orgranizations )
			{
				$stmt = $this->db->prepare("SELECT id,username,useremail,accountType,parentid FROM users WHERE useremail=:umail AND (accountType=1 OR accountType=2) LIMIT 1");
				$stmt->execute(array(':umail'=>$umail));
			}
			else	// User is using username
			{
				$stmt = $this->db->prepare("SELECT id,username,useremail,accountType,parentid FROM users WHERE username=:uname");
				$stmt->execute(array(':uname'=>$uname));
			}
			
			$userRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$rowLen = count($userRow);
			
			if ($rowLen == 0)
			{
				$response["userInfo"] = null;
				$response["childUserInfo"] = null;
			}
			
			for ($i = 0; $i < $rowLen; $i++)
			{
				if ($userRow[$i]["accountType"] == "1" || $userRow[$i]["accountType"] == "2")
				{
					$response['userInfo'] = $userRow[$i];
					$response['childUserInfo'] = null;
				}
				
				if ($userRow[$i]["accountType"] == "3")
				{
					// If its a MindGoalie Personal, we will get the parentID and get their userInfo
					$stmt = $this->db->prepare("SELECT id,username,useremail,accountType,parentid FROM users WHERE id=:parentid");
					$stmt->execute(array(':parentid'=>$userRow[$i]["parentid"]));
					$userParentRow = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$response['childUserInfo'] = array('id'=>$userRow[$i]["id"], 'username'=>$userRow[$i]["username"]);
					$response['userInfo'] = $userParentRow;
				}
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e-> getMessage();
			return $response;
		}
	}
}
?>

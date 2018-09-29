<?php
class PROJECT
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	//////////////////////////////////////
	// PROJECT CREATION
	//////////////////////////////////////
	
	public function createProject($userid, $title, $description, $tags, $acctype, $timestamp, $month, $year, $imageName, $thumbName, $groupids, $payPerPost)
	{
		try
		{
			$stmt = $this->db->prepare("INSERT INTO projects(userid,title,description,tags,type,timestamp,month,year,image,thumb,group_ids,pay_per_post) 
							VALUES(:userid, :title, :description, :tags, :accountType, :timestamp, :month, :year, :image, :thumb, :groupids, :payPerPost)");
												  
			$stmt->bindparam(":userid", $userid);
			$stmt->bindparam(":title", $title);
			$stmt->bindparam(":description", $description);
			$stmt->bindparam(":tags", $tags);										  
			$stmt->bindparam(":accountType", $acctype);										  
			$stmt->bindparam(":timestamp", $timestamp);										  
			$stmt->bindparam(":month", $month);										  
			$stmt->bindparam(":year", $year);										  
			$stmt->bindparam(":image", $imageName);										  
			$stmt->bindparam(":thumb", $thumbName);										  
			$stmt->bindparam(":groupids", $groupids);										  
			$stmt->bindparam(":payPerPost", $payPerPost);										  
				
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
	
	public function updateUserProject($info)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			$stmt = $this->db->prepare("UPDATE projects SET 
					projects.title = :title,
					projects.description = :description,
					projects.tags = :tags
				WHERE projects.id = :projectid");
				
			$stmt->bindValue(':title', $info["title"], PDO::PARAM_STR);
			$stmt->bindValue(':description', $info["description"], PDO::PARAM_STR);
			$stmt->bindValue(':tags', $info["tags"], PDO::PARAM_STR);
			$stmt->bindValue(':projectid', $info["projectid"], PDO::PARAM_INT);
			
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
		}				
	}	
	
	public function createProjectMedia($userid, $projectid, $imageName, $thumbName, $videoName, $month, $year, $timestamp)
	{
		$type = 1;
		try
		{
			$stmt = $this->db->prepare("INSERT INTO media(userid,projectid,image,thumb,video,month,year,timestamp,type) 
							VALUES(:userid, :projectid, :image, :thumb, :video, :month, :year, :timestamp, :type)");
												  
			$stmt->bindparam(":userid", $userid);
			$stmt->bindparam(":projectid", $projectid);
			$stmt->bindparam(":image", $imageName);										  
			$stmt->bindparam(":thumb", $thumbName);										  
			$stmt->bindparam(":video", $videoName);										  
			$stmt->bindparam(":month", $month);										  
			$stmt->bindparam(":year", $year);										  
			$stmt->bindparam(":timestamp", $timestamp);										  
			$stmt->bindparam(":type", $type);										  
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}

	/*Get details of a project start*/
	public function getProjectDetails($projectid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work
			
			$fetchProjects = $this->db->prepare("SELECT 
					projects.id, 
					projects.title, 
					projects.description, 
					projects.image, 
					projects.timestamp,
					projects.month,
					projects.year,
					users.username,
					user_avatar.thumb,
					project_tags.name as tagName,
					project_tags.thumb as tagIcon
				FROM projects 
				INNER JOIN users ON projects.userid = users.id
				LEFT JOIN project_tags ON projects.tags = project_tags.id
				LEFT JOIN user_avatar ON users.id = user_avatar.userid
				WHERE projects.id = :projectid");
			
			$fetchProjects->bindValue(':projectid', $projectid, PDO::PARAM_INT);
			$fetchProjects->execute() or die(print_r($fetchProjects->errorInfo()));
			
			return $fetchProjects->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}
	/*Get details of a project end*/	
	
	public function fetchUserTotalProjectsByUserId($userid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work
			
			$fetchProjects = $this->db->prepare("SELECT id FROM projects WHERE userid = :userid");
			
			$fetchProjects->bindValue(':userid', $userid, PDO::PARAM_INT);
			$fetchProjects->execute() or die(print_r($fetchProjects->errorInfo()));
			
			$response["projectsTotal"] = count($fetchProjects->fetchAll(PDO::FETCH_ASSOC));
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["result"] = false;
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}
	
	
	public function getProjectList($userid, $ptagname, $skip = 0, $max = 5)
	{
		/*
			MySQL *does* have an “offset,” however: `LIMIT {offset},{length}`. For example, if you wanted to get 30 rows, but skip the first 44, you would use:
			`SELECT mycol FROM mytable LIMIT 44,30`
		*/
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			/*$fetchProjects = $this->db->prepare("SELECT description,tags,type,timestamp,month,year,image 
				FROM projects 
				WHERE userid = :userid 
				ORDER BY id DESC 
				LIMIT :skip, :max");*/

			if (!is_null($ptagname))
			{
				$match_ptagname = "%".$ptagname."%";

				$fetchProjects = $this->db->prepare("SELECT 
					projects.id, 
					projects.userid, 
					users.username,	
					user_avatar.thumb AS avatarThumb,
					projects.title, 
					projects.description,
					/*projects.type,*/ 
					(
					    CASE 
					        WHEN projects.type = 1 THEN 'Goaliemind Account'
					        WHEN projects.type = 2 THEN 'Organisation MindGoalie'
					        ELSE 'Personal MindGoalie'
					    END
					) AS accountTypeName, 
					projects.timestamp,
					projects.stars,
					media.image, 
					media.thumb,
					media.video,
					media.month,
					media.year,
					media.type,
					project_tags.name AS tagName,
					project_tags.thumb AS tagIcon,
					IF(projects.status='1','open','closed') AS projectStatus,
					IF(projects.pay_per_post='1','yes','no') AS payPerPost,
					'yes' As display
				FROM projects 
				INNER JOIN media ON projects.id = media.projectid 
				INNER JOIN project_tags ON projects.tags = project_tags.id 
				INNER JOIN users ON users.id = projects.userid 
				INNER JOIN user_avatar ON user_avatar.userid = projects.userid 
				WHERE projects.userid = :userid
				AND project_tags.name LIKE :match_ptagname 
				ORDER BY projects.id DESC LIMIT :skip, :max");

				$fetchProjects->bindValue(':match_ptagname', $match_ptagname, PDO::PARAM_STR);
			}
			else
			{
				$fetchProjects = $this->db->prepare("SELECT 
					projects.id, 
					projects.userid, 
					users.username,	
					user_avatar.thumb AS avatarThumb,
					projects.title, 
					projects.description, 
					/*projects.type,*/
					(
					    CASE 
					        WHEN projects.type = 1 THEN 'Goaliemind Account'
					        WHEN projects.type = 2 THEN 'Organisation MindGoalie'
					        ELSE 'Personal MindGoalie'
					    END
					) AS accountTypeName, 
					projects.timestamp,
					projects.stars,
					media.image, 
					media.thumb,
					media.video,
					media.month,
					media.year,
					media.type,
					project_tags.name AS tagName,
					project_tags.thumb AS tagIcon,
					IF(projects.status='1','open','closed') AS projectStatus,
					IF(projects.pay_per_post='1','yes','no') AS payPerPost,
					'yes' As display
				FROM projects 
				INNER JOIN media ON projects.id = media.projectid
				INNER JOIN project_tags ON projects.tags = project_tags.id 
				INNER JOIN users ON users.id = projects.userid 
				INNER JOIN user_avatar ON user_avatar.userid = projects.userid 
				WHERE projects.userid = :userid
				ORDER BY projects.id DESC LIMIT :skip, :max");
			}

			$fetchProjects->bindValue(':userid', $userid, PDO::PARAM_INT);

			if(isset($skip)) {
				$fetchProjects->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			} else {
				$fetchProjects->bindValue(':skip', 0, PDO::PARAM_INT);  
			}

			$fetchProjects->bindValue(':max', $max, PDO::PARAM_INT);
			$fetchProjects->execute() or die(print_r($fetchProjects->errorInfo()));
			
			return $fetchProjects->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}	

	public function getProjectListOfFriends($userid, $ptagname, $skip = 0, $max = 5)
	{
		/*
			MySQL *does* have an “offset,” however: `LIMIT {offset},{length}`. For example, if you wanted to get 30 rows, but skip the first 44, you would use:
			`SELECT mycol FROM mytable LIMIT 44,30`
		*/
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$fetchFriendsid = $this->db->prepare("SELECT
					friendid
				FROM friends
				WHERE userid = :userid");

			$fetchFriendsid->bindValue(':userid', $userid, PDO::PARAM_INT);
			$fetchFriendsid->execute() or die(print_r($fetchProjects->errorInfo()));
			$friendids_arr = $fetchFriendsid->fetchAll(PDO::FETCH_COLUMN , 0);
			$friendids = implode(",",$friendids_arr);

			if (!is_null($ptagname))
			{
				$match_ptagname = "%".$ptagname."%";

				$fetchProjects = $this->db->prepare("SELECT 
					projects.id, 
					projects.userid, 
					users.username,	
					user_avatar.thumb AS avatarThumb,
					projects.title, 
					projects.description, 
					/*projects.type,*/
					(
					    CASE 
					        WHEN projects.type = 1 THEN 'Goaliemind Account'
					        WHEN projects.type = 2 THEN 'Organisation MindGoalie'
					        ELSE 'Personal MindGoalie'
					    END
					) AS accountTypeName, 
					projects.timestamp,
					projects.stars,
					media.image, 
					media.thumb,
					media.video,
					media.month,
					media.year,
					media.type,
					project_tags.name AS tagName,
					project_tags.thumb AS tagIcon,
					IF(projects.status='1','open','closed') AS projectStatus,
					IF(projects.pay_per_post='1','yes','no') AS payPerPost,
					IF(project_users.id IS NOT NULL,'yes','no') AS display
				FROM projects 
				INNER JOIN media ON projects.id = media.projectid
				INNER JOIN project_tags ON projects.tags = project_tags.id 
				INNER JOIN users ON users.id = projects.userid 
				INNER JOIN user_avatar ON user_avatar.userid = projects.userid
				LEFT JOIN project_users ON
					(
						projects.id = project_users.project_id
						AND project_users.user_id = :userid
					) 
				WHERE FIND_IN_SET(projects.userid, :friendids)
				AND project_tags.name LIKE :match_ptagname 
				ORDER BY projects.id DESC LIMIT :skip, :max");

				$fetchProjects->bindValue(':match_ptagname', $match_ptagname, PDO::PARAM_STR);
			}
			else
			{
				$fetchProjects = $this->db->prepare("SELECT 
					projects.id, 
					projects.userid,
					users.username,			
					user_avatar.thumb AS avatarThumb,					
					projects.title, 
					projects.description, 
					/*projects.type,*/
					(
					    CASE 
					        WHEN projects.type = 1 THEN 'Goaliemind Account'
					        WHEN projects.type = 2 THEN 'Organisation MindGoalie'
					        ELSE 'Personal MindGoalie'
					    END
					) AS accountTypeName,  
					projects.timestamp,
					projects.stars,
					media.image, 
					media.thumb,
					media.video,
					media.month,
					media.year,
					media.type,
					project_tags.name AS tagName,
					project_tags.thumb AS tagIcon,
					IF(projects.status='1','open','closed') AS projectStatus,
					IF(projects.pay_per_post='1','yes','no') AS payPerPost,
					IF(project_users.id IS NOT NULL,'yes','no') AS display
				FROM projects 
				INNER JOIN media ON projects.id = media.projectid
				INNER JOIN project_tags ON projects.tags = project_tags.id 
				INNER JOIN users ON users.id = projects.userid 
				INNER JOIN user_avatar ON user_avatar.userid = projects.userid
				LEFT JOIN project_users ON
					(
						projects.id = project_users.project_id
						AND project_users.user_id = :userid
					)  
				WHERE FIND_IN_SET(projects.userid, :friendids)
				ORDER BY projects.id DESC LIMIT :skip, :max");
			}
			
			$fetchProjects->bindValue(':friendids', $friendids, PDO::PARAM_INT);
			$fetchProjects->bindValue(':userid', $userid, PDO::PARAM_INT);

			if(isset($skip)) {
				$fetchProjects->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			} else {
				$fetchProjects->bindValue(':skip', 0, PDO::PARAM_INT);  
			}

			$fetchProjects->bindValue(':max', $max, PDO::PARAM_INT);
			$fetchProjects->execute() or die(print_r($fetchProjects->errorInfo()));
			
			return $fetchProjects->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}

	public function getProjectTagList($userid, $skip = 0, $max = 5)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$fetchProjectTags = $this->db->prepare("SELECT id, name, thumb	FROM project_tags ORDER BY name ASC LIMIT :skip, :max");

			if(isset($skip)) {
				$fetchProjectTags->bindValue(':skip', trim($skip), PDO::PARAM_INT);
			} else {
				$fetchProjectTags->bindValue(':skip', 0, PDO::PARAM_INT);  
			}

			$fetchProjectTags->bindValue(':max', $max, PDO::PARAM_INT);

			$fetchProjectTags->execute() or die(print_r($fetchProjectTags->errorInfo()));
			
			return $fetchProjectTags->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}
	}
	
	public function getProjectCategoryList()
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$fetchProjectCategories = $this->db->prepare("SELECT * FROM project_category ORDER BY id ASC");

			$fetchProjectCategories->execute() or die(print_r($fetchProjectCategories->errorInfo()));
			
			return $fetchProjectCategories->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}
	}

	/*Add Stars to Project start*/
	public function addStarsToProject($projectid, $projectAvailableStars, $starsToAdd)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); 

			//appended value of stars
			$appendedStars = $projectAvailableStars+$starsToAdd;

			//Update the appended stars in project table
			$stmt = $this->db->prepare("UPDATE projects SET stars = :appendedStars WHERE id = :projectid");
				
			$stmt->bindValue(':appendedStars', $appendedStars, PDO::PARAM_INT);
			$stmt->bindValue(':projectid', $projectid, PDO::PARAM_INT);
			$stmt->execute() or die("Sorry some error has been ocurred! Please try again.");
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}
	}
	/*Add Stars to Project end*/

	/*save project fund start*/
	public function saveProjectFund($projectFundInfo)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("INSERT INTO project_funds(user_payment_id, amount, project_id, user_id, refer_id, goaliemind_amt, refer_amt) 
							VALUES(:user_payment_id, :amount, :project_id, :user_id, :refer_id, :goaliemind_amt, :refer_amt)");
												  
			$stmt->bindparam(":user_payment_id", $projectFundInfo['user_payment_id'], PDO::PARAM_INT);
			$stmt->bindparam(":amount", $projectFundInfo['amount'], PDO::PARAM_STR);
			$stmt->bindparam(":project_id", $projectFundInfo['projectid'], PDO::PARAM_INT);
			$stmt->bindparam(":user_id", $projectFundInfo['userid'], PDO::PARAM_INT);										  
			$stmt->bindparam(":refer_id", $projectFundInfo['referid'], PDO::PARAM_INT);										  
			$stmt->bindparam(":goaliemind_amt", $projectFundInfo['goaliemind_amt'], PDO::PARAM_STR);										  
			$stmt->bindparam(":refer_amt", $projectFundInfo['referred_amt'], PDO::PARAM_STR);										  
				
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
	/*save project fund end*/

	/*Update Project status start*/
	public function updateProjectStatus($projectid, $projectAType)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );

			if($projectAType == 3)
			{
				$stmt = $this->db->prepare("UPDATE projects SET status = '0' WHERE id = :projectid");

				$stmt->bindValue(':projectid', trim($projectid), PDO::PARAM_INT);

				if($stmt->execute())
				{
					
				}
				else
				{
					$response["ERROR"] = "Something went wrong please try again later!";	
				}
			}

			$response["RESULT"] = true;

			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}
	/*Update Project status  start*/

	/*Project Info For Fund start*/
	public function getProjectInfoForFund($projectid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("SELECT userid, title, type, stars, status FROM projects WHERE id = :projectid");

			$stmt->bindparam(":projectid", $projectid, PDO::PARAM_INT);
			
			$stmt->execute() or die(print_r($stmt->errorInfo()));
			
			$response = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $response[0];
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}	
	}
	/*Project Info For Fund end*/

	/*Subscribe Projects start*/
	public function subscribeProjects($organisationId, $userId)
	{
		try
		{
			$stmt = $this->db->prepare("INSERT INTO project_subscriptions(organisation_id,user_id,status,created_on) 
							VALUES(:organisationId, :userId, '1', NOW())");
												  
			$stmt->bindparam(":organisationId", $organisationId);
			$stmt->bindparam(":userId", $userId);
				
			if($stmt->execute())
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
	/*Subscribe Projects end*/

	/*Disable Project subscription start*/
	public function disableProjectSubscription()
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("UPDATE project_subscriptions
					INNER JOIN users ON project_subscriptions.user_id = users.id
					SET project_subscriptions.status = '0'
					WHERE users.wallet = 0");
			
			if($stmt->execute())
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
	/*Disable Project subscription end*/

	/*Retrieve id's of subscribed users of a Project start*/
	public function getProjectSubscribedUsersIds($organisation_id)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("SELECT user_id FROM project_subscriptions
					WHERE organisation_id=:organisation_id
					AND status='1'");
			
			$stmt->bindValue(':organisation_id', $organisation_id, PDO::PARAM_INT);

			if($stmt->execute())
			{
				$response["result"] = true;
				$response["subscribedUsersIds"] = $stmt->fetchAll(PDO::FETCH_COLUMN , 0);
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
	/*Retrieve id's of subscribed users of a Project end*/

	/*Add users to project start*/
	public function addUsersToProject($projectId, $userIdArr)
	{
		try
		{
			$insertData = array(); 
			foreach ($userIdArr as $row) $insertData[] = [$projectId, $row];
					
			$stmtAddUsers = $this->multiPrepare('INSERT INTO project_users (project_id,user_id)', $insertData);
					
			$bindArray = array();
			array_walk_recursive($insertData, function($item) use (&$bindArray) { $bindArray[] = $item; });
					
			if ($stmtAddUsers->execute($bindArray))
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
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}
	/*Add users to project end*/

	// Reference: https://www.daniweb.com/programming/web-development/code/495371/insert-multiple-records-with-pdo-prepared-statement
	public function multiPrepare($sql, $data)
    {
        $rows = count($data);
        $cols = count($data[0]);
        $rowString = '(' . rtrim(str_repeat('?,', $cols), ',') . '),';
        $valString = rtrim(str_repeat($rowString, $rows), ',');
        return $this->db->prepare($sql . ' VALUES ' . $valString);
    }

    /*Begin getFundedProjectsUserwise List*/
    public function getFundedProjectsUserwise($skip = 0, $max = 5)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$fetchFundedProjects = $this->db->prepare("SELECT 
					project_funds.user_id,
					CONCAT('[',GROUP_CONCAT(
						CONCAT('{\"project_id\":\"',project_funds.project_id,'\",\"project_name\":\"',projects.title,'\",\"amount\":\"',project_funds.amount,'\",\"funded_on\":\"',project_funds.updated_on,'\"}')
						),']') AS fundedProjectData
                FROM project_funds 
				INNER JOIN projects ON project_funds.project_id = projects.id
				GROUP BY project_funds.user_id
				ORDER BY project_funds.user_id ASC LIMIT :skip, :max");

			if(isset($skip)) {
				$fetchFundedProjects->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			} else {
				$fetchFundedProjects->bindValue(':skip', 0, PDO::PARAM_INT);  
			}

			$fetchFundedProjects->bindValue(':max', $max, PDO::PARAM_INT);

			$fetchFundedProjects->execute() or die(print_r($fetchFundedProjects->errorInfo()));
			
			$i = $fetchFundedProjects->fetchAll(PDO::FETCH_ASSOC);
			return $i;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}

	/*End getFundedProjectsUserwise List*/

	/*Begin GetTotalFundsForProjectsUserwise List*/
    public function getTotalFundsForProjectsUserwise($skip = 0, $max = 5)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$fetchFundedProjects = $this->db->prepare("SELECT 
				project_funds.user_id,
				project_funds.project_id,
				projects.title AS project_name,
				SUM(project_funds.amount) AS total_funds
                FROM project_funds 
				INNER JOIN projects ON project_funds.project_id = projects.id
				GROUP BY project_funds.user_id, project_funds.project_id
				ORDER BY project_funds.user_id ASC LIMIT :skip, :max");

			if(isset($skip)) {
				$fetchFundedProjects->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			} else {
				$fetchFundedProjects->bindValue(':skip', 0, PDO::PARAM_INT);  
			}

			$fetchFundedProjects->bindValue(':max', $max, PDO::PARAM_INT);

			$fetchFundedProjects->execute() or die(print_r($fetchFundedProjects->errorInfo()));
			
			return $fetchFundedProjects->fetchAll(PDO::FETCH_ASSOC);;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}				
	}
	/*End GetTotalFundsForProjectsUserwise List*/
}
?>
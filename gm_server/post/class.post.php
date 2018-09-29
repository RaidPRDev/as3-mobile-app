<?php
class POST
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}

	//Add Post start
	public function addPost($postInfo)
	{
		try
		{
			$stmt = $this->db->prepare("INSERT INTO posts(subject_type,subject_id,object_type,object_id,content,pay_per_post,post_date,created_by) 
							VALUES(:subject_type,:subject_id,:object_type,:object_id,:content,:pay_per_post,:post_date,:created_by)");
			
			$stmt->bindparam(":subject_type", $postInfo['subject_type'], PDO::PARAM_STR);
			$stmt->bindparam(":subject_id", $postInfo['subject_id'], PDO::PARAM_INT);
			$stmt->bindparam(":object_type", $postInfo['object_type'], PDO::PARAM_STR);
			$stmt->bindparam(":object_id", $postInfo['object_id'], PDO::PARAM_INT);
			$stmt->bindparam(":content", $postInfo['content'], PDO::PARAM_STR);
			$stmt->bindparam(":pay_per_post", $postInfo['pay_per_post'], PDO::PARAM_INT);
			$stmt->bindparam(":post_date", $postInfo['post_date'], PDO::PARAM_STR);
			$stmt->bindparam(":created_by", $postInfo['created_by'], PDO::PARAM_INT);
				
			if ($stmt->execute())
			{
				$response["result"] = true;
				$response['postid'] = $this->db->lastInsertId();
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
	//Add Post end

	//Retrieve Group Post start
	public function retrieveGroupPost($groupid, $skip=0, $max=5)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("SELECT 
					posts.id AS post_id,
					posts.content AS post_text,
					users.username AS posted_by,
					posts.post_date AS posted_on 
					FROM posts
					INNER JOIN users ON users.id = posts.created_by
					WHERE posts.object_type = 'group'
					AND posts.object_id = :groupid
					ORDER BY posts.id DESC LIMIT :skip, :max");
			
			$stmt->bindValue(':groupid', $groupid, PDO::PARAM_INT);
			
			if(isset($skip)) $stmt->bindValue(':skip', trim($skip), PDO::PARAM_INT);    
			else $stmt->bindValue(':skip', 0, PDO::PARAM_INT);  
	
			$stmt->bindValue(':max', $max, PDO::PARAM_INT);
			$stmt->execute() or die(print_r($stmt->errorInfo()));
			
			$response["result"] = true;
			$response["groupPost"] = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $response;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	//Retrieve Group Post end
}
?>
<?php
class NOTIFICATION
{
	private $db;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
	}
	
	public function createNotification($notificationInfo)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("INSERT INTO notifications(subject_type,subject_id,object_type,object_id,notification_message,params) VALUES(:subject_type, :subject_id, :object_type, :object_id, :notification_message, :params)");

			$stmt->bindparam(":subject_type", $notificationInfo['subject_type'], PDO::PARAM_STR);
			$stmt->bindparam(":subject_id", $notificationInfo['subject_id'], PDO::PARAM_INT);
			$stmt->bindparam(":object_type", $notificationInfo['object_type'], PDO::PARAM_STR);
			$stmt->bindparam(":object_id", $notificationInfo['object_id'], PDO::PARAM_INT);
			$stmt->bindparam(":notification_message", $notificationInfo['notification_message'], PDO::PARAM_STR);
			$stmt->bindparam(":params", $notificationInfo['params'], PDO::PARAM_STR);

			$stmt->execute() or die(print_r($stmt->errorInfo()));
			
			$response = $this->viewNotification($this->db->lastInsertId());

			return $response;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}

	public function createMultiNotification($notificationInfo)
	{
		try
		{
			$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work
			$insertData = array(); 
			foreach ($notificationInfo['object_id'] as $row) $insertData[] = [$notificationInfo['subject_type'], $notificationInfo['subject_id'], $notificationInfo['object_type'], $row, $notificationInfo['notification_message'], $notificationInfo['params']];
					
			$stmtAdd = $this->multiPrepare('INSERT INTO notifications(subject_type,subject_id,object_type,object_id,notification_message,params)', $insertData);
					
			$bindArray = array();
			array_walk_recursive($insertData, function($item) use (&$bindArray) { $bindArray[] = $item; });
					
			if ($stmtAdd->execute($bindArray))
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
			echo $e->getMessage();
		}	
	}

	// Reference: https://www.daniweb.com/programming/web-development/code/495371/insert-multiple-records-with-pdo-prepared-statement
	public function multiPrepare($sql, $data)
    {
        $rows = count($data);
        $cols = count($data[0]);
        $rowString = '(' . rtrim(str_repeat('?,', $cols), ',') . '),';
        $valString = rtrim(str_repeat($rowString, $rows), ',');
        return $this->db->prepare($sql . ' VALUES ' . $valString);
    }

	public function listNotification($objectid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			//Set the read to 1 in db
			$this->updateNotificationRead($objectid);
			
			//List notifiactions of user
			$stmt = $this->db->prepare("SELECT * FROM notifications WHERE object_id=:object_id");
			$stmt->bindparam(":object_id", $objectid, PDO::PARAM_INT);
			$stmt->execute() or die(print_r($stmt->errorInfo()));
			
			$response = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $response;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}

	public function viewNotification($notificationId)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false ); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("SELECT * FROM notifications WHERE id = :notificationId");

			$stmt->bindparam(":notificationId", $notificationId, PDO::PARAM_INT);
			
			$stmt->execute() or die(print_r($stmt->errorInfo()));
			
			$response = $stmt->fetchAll(PDO::FETCH_ASSOC);

			return $response;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}

	public function updateNotificationRead($objectid)
	{
		try
		{
			$this->db->setAttribute( PDO::ATTR_EMULATE_PREPARES, false); // for some reason we need to set to false, or LIMIT params do not work

			$stmt = $this->db->prepare("UPDATE notifications SET view='1' WHERE object_id=:object_id");

			$stmt->bindparam(":object_id", $objectid, PDO::PARAM_INT);
			
			$stmt->execute() or die(print_r($stmt->errorInfo()));
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}	
	}
}
?>
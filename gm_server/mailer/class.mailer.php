<?php

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

/*
	Documentation: https://github.com/PHPMailer/PHPMailer
	$this->phpmailer->SMTPDebug = 2;                               // Enable verbose debug output
	$this->phpmailer->Debugoutput = 'html';
*/

require 'phpmailer/PHPMailerAutoload.php';

class MAILER
{
	private $db;
	private $phpmailer;
	
	function __construct($DB_con)
	{
		$this->db = $DB_con;
				
		$this->phpmailer = new PHPMailer;
		$this->phpmailer->isSMTP();                                 // Set mailer to use SMTP
		// $this->phpmailer->SMTPDebug = 2;                                 
		$this->phpmailer->Host = 'mail.omnis.com';  	 			// Specify main and backup SMTP servers
		$this->phpmailer->SMTPAuth = true;                       	// Enable SMTP authentication
		$this->phpmailer->SMTPSecure = 'tls';						// Set the encryption system to use - ssl (deprecated) or tls
		$this->phpmailer->Username = 'fania@raidpr.com';            // SMTP username
		$this->phpmailer->Password = '@Savanna4093';               	// SMTP password
		$this->phpmailer->Port = 587;
	}
	
	//////////////////////////////////////
	// SENDMAIL CREATION
	//////////////////////////////////////
	
	public function initBackgroundProcess()
	{
		// don't let user kill the script by hitting the stop button 
		ignore_user_abort(true); 
		
		// don't let the script time out 
		set_time_limit(0); 
		
		// start output buffering
		ob_start();  
		
		usleep(1500000);
	}
	
	public function startBackgroundProcess()
	{
		// force PHP to output to the browser... 
		$size = ob_get_length(); 
		header("Content-Length: $size"); 
		header('Connection: close'); 
		ob_end_flush(); 
		ob_flush(); 
		flush(); // you need to call all 3 flushes!
		if (session_id()) session_write_close(); 
		
		usleep(10000000); // 10 seconds * 1,000,000 milliseconds
	}
	
	
	
	public function sendEmailMessage($emailInfo)
	{
		if (!isset($emailInfo["emailFrom"])) $emailInfo["emailFrom"] = "appdev@raidpr.com";
		if (!isset($emailInfo["emailFromName"])) $emailInfo["emailFromName"] = $emailInfo["uname"];
		
		try
		{
			$this->phpmailer->setFrom($emailInfo["emailFrom"], $emailInfo["emailFromName"]);
			$this->phpmailer->addAddress($emailInfo["emailTo"], $emailInfo["uname"]);     // Add a recipient
			$this->phpmailer->isHTML(true);
			$this->phpmailer->Subject = $emailInfo["emailSubject"];
			$this->phpmailer->Body    = $emailInfo["emailHtmlBlock"];
			$this->phpmailer->AltBody = $emailInfo["emailNonHtmlBlock"];
			
			$sendMailResult = $this->phpmailer->send();
			
			if(!$sendMailResult) 
			{
				$response["ERROR"] = 'Message could not be sent.';
			} 
			else 
			{
				$response["OK"] = 'Message has been sent.';
			}
		}
		catch(Exception $e)
		{
			$response["ERROR"] = $e->getMessage();
		}	
		
		return $response;
	}
	
	public function createVerificationPost()
	{
		try
		{
			$stmt = $this->db->prepare("INSERT INTO projects(userid,description,tags,type,timestamp,month,year,image,thumb) 
							VALUES(:userid, :description, :tags, :accountType, :timestamp, :month, :year, :image, :thumb)");
												  
			$stmt->bindparam(":userid", $userid, PDO::PARAM_INT);
			$stmt->bindparam(":description", $description, PDO::PARAM_STR);			  
				
			if ($stmt->execute())
			{
				$response["result"] = true;
				$response['newprojectid'] = $this->db->lastInsertId();
			}
			else 
			{
				$response["result"] = false;
				$response["ERROR"] = $fetchGroups->errorInfo();
			}
			
			return $response;
		}
		catch(PDOException $e)
		{
			$response["ERROR"] = $e->getMessage();
			return $response;
		}			
	}
}
?>

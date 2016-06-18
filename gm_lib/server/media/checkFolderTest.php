<?php
	
	$folder = $_SERVER['DOCUMENT_ROOT'] . "/clients/goaliemind/media/images/" . "2015_01";

	if (!is_dir($folder))  // is_dir - tells whether the filename is a directory
	{
		//mkdir - tells that need to create a directory
		echo "The Directory {$folder} does not exist!<br/>";
		mkdir($folder);
	}
	else
	{
		echo "The Directory {$folder} exist!<br/>";
		
		

	}
	
	echo "Document.Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br/>";
?> 
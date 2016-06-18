<?php

require_once '/var/www/html/aws/aws-autoloader.php';

/*define('AWS_KEY', 'AKIAITA4U6A3UNXWC4SA');
define('AWS_SECRET_KEY', '');
define('AWS_ACCOUNT_ID', '4359-5433-2198');
define('AWS_CANONICAL_ID', '3ed142f91dce915d6be9910ff46f0ec914f1fc70eaebf3f3100365247d7c3a80');
define('AWS_CANONICAL_NAME', '');*/

$ec2 = new AmazonEC2();

echo "Test " . __DIR__ . " " . AWS_KEY;

?>
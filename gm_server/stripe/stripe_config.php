<?php

require_once($ROOT_PATH . '/stripe/init.php');
//require_once('init.php');

// Test Keys
$stripe = array(
  "secret_key"      => "",
  "publishable_key" => ""
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);

?>

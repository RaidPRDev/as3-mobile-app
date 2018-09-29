<?php

require_once($ROOT_PATH . '/stripe/init.php');
//require_once('init.php');

// Test Keys
$stripe = array(
  "secret_key"      => "sk_test_UgC60MxgFXnqF2OEAdWQKDcz",
  "publishable_key" => "pk_test_Lefnxq7PakOAACEkJrBE9BlL"
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);

?>
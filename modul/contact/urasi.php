<?php
$taboos = array("viagra","sex");

$action_contact = array();
$action_contact[""] = "";
$action_contact["kirim"] = "send";	
$GLOBALS['action_contact']=$action_contact;			


/**
 * Google reCaptcha 
 * Aly
 */
require_once __DIR__ . '/../../aplikasi/vendor/autoload.php';

// Register API keys at https://www.google.com/recaptcha/admin
$siteKey = '6LewvS8UAAAAAEnQ4FtcVuFzGPH4yoAFyy7W567M';
$secret = '6LewvS8UAAAAAATlAQiilIPcQP2zYIlpccVhWNb7';
?>
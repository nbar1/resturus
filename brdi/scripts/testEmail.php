<?php

$override_constants = array(
	'INCLUDES' => "../../application/includes/",
	'CONFIG' => "../../config/",
	'APPLICATION' => "../../application/",
);

require_once('../../framework_helper.php');

$email = new brdi_Api_SendMail("xnickbarone@gmail.com", "Resturus <web@resturus.com>", "Test email", "Hey, this is a test email!");
if($email->sendMail()) echo "sent";
else echo "nope";
?>
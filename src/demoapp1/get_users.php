<?php

// Load demoapp1 configuration
include_once('config.php');

// Load demoapp1 lib
include_once('lib.php');

// Load SSP lib
include_once($simplesamlphp_path.'lib/_autoload.php');

// Check permission, return user list

$aqcHandler = new sspmod_attributequeryconsumer_AQCHandler($consumerId);

//$aqcHandler->checkPermission();

$users = get_users();

$ids = array();

foreach ($users as $user) {
    $ids[] = $user['id'];
}

echo json_encode($ids); 


?>

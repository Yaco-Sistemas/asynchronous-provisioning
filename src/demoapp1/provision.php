<?php

// Load demoapp1 configuration
include_once('config.php');

// Load demoapp1 lib
include_once('lib.php');

// Load SSP lib
include_once($simplesamlphp_path.'lib/_autoload.php');

if(isset($_REQUEST['userID'])) {
    $userId = $_REQUEST['userID'];
}
else {
    $msg = 'Error when provisioning, userID not found';
    write_msg('error', $msg);
    exit();
}

// Check permission, execute AQC & retrieve user data for provisioning

try {

    $aqcHandler = new sspmod_attributequeryconsumer_AQCHandler($consumerId);

    $aqcHandler->checkPermission();

    if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'deprovision') {
        if (delete_user($userId)) {
            write_msg('ok', $userId.' deleted');
            exit();
        }
        else {
            $msg = 'Error when deprovisioning, '.$userID;
            write_msg('error', $msg);
            exit();
        }
    }


    $response = $aqcHandler->handleQuery($userId);

    $newUserData = $response->getAttributes();

    if(is_array($newUserData) && !empty($newUserData)) {   

	    // Provision the user

        $userId = $newUserData[$uidfield][0];

        $exists = user_exists($userId);
        $user = retrieve_user_data($newUserData);

        if($provision && !empty($user)) {
            if($exists) {
	            update_user($user);
                write_msg('ok', $userId.' updated');                
            }
            else {
	            create_user($user);
                write_msg('ok', $userId.' created');
            }
            exit();       
        }
    }
}
catch (Exception $e) {
    $msg = 'Error when provisioning '.$userId. '. '.$e->getMessage();
    write_msg('error', $msg);
    exit();
}


$msg = 'Error when provisioning '.$userId;
write_msg('error', $msg);

?>

<?php

// Load demoapp1 configuration
include_once('config.php');

// Load demoapp1 lib
include_once('lib.php');

// Load SSP lib
include_once($simplesamlphp_path.'lib/_autoload.php');

// Load attributeQueryConsumer config
$session = SimpleSAML_Session::getInstance();
$aqc_config = SimpleSAML_Configuration::getConfig('module_attributequeryconsumer.php');

$spSource = $aqc_config->getString('sp_source', 'default-sp');
$aqsUrl = $aqc_config->getString('aqs_url');
$adminAttrId = $aqc_config->getString('admin_attr_id', 'uid');
$allowed_admins = $aqc_config->getArray('allowed_admins', array());
$requiredAttrs = $aqc_config->getArray('required_attrs', array());

$userId = 'jdoe';

$session_key = 'attributequeryconsumer:demoaap1';

// Check if a valid admin is logged.
$auth = new SimpleSAML_Auth_Simple($spSource);

$auth->requireAuth();

$attrs = $auth->getAttributes();

if (!isset($attrs[$adminAttrId])) {
	die('No se pudo obtener el atributo '.$adminAttrId.' del usuario, este atributo es necesario para habilitarle permisos para poder provisionar');
}

$logged_user = $attrs[$adminAttrId][0];

if (!in_array($logged_user, $allowed_admins)) {
	die('El usuario no tiene permisos para poder provisionar');
}

// Execute AQC & retrieve user data for provisioning

$aqc = new sspmod_attributequeryconsumer_AQC();

$newUserData = $aqc->handleResponse($spSource);

$sent = false;

if(isset($newUserData)) {
    $dataId = $aqc->getDataId();
    $sent = true;
}
else {
    $dataId = SimpleSAML_Utilities::generateID();
    $aqc->setDataId($dataId);
}

$nameId = array(
	'Format' => SAML2_Const::NAMEFORMAT_UNSPECIFIED,
	'Value' => $userId,
	'NameQualifier' => '',
    'SPNameQualifier' => '',
);

if (!$sent) {
	$aqc->sendQuery($aqsUrl, $spSource, $nameId, $requiredAttrs);
}
else {
	// Provision the user

    $exists = user_exists($userId);
    $user = retrieve_user_data($newUserData);

    if($on_the_fly) {
        if($exists) {
	        update_user($user);
        }
        else {
	        create_user($user);
        }
    }
}


?>



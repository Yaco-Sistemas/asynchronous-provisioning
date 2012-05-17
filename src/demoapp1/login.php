<?php 

include_once('config.php');

include_once($simplesamlphp_path.'lib/_autoload.php');

$auth = new SimpleSAML_Auth_Simple($auth_source);

$params = array(
    'ReturnTo' => $demo_url.'index.php',
);

$auth->requireAuth($params);

?>

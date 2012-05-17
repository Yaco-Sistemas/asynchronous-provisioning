<?php

$session = SimpleSAML_Session::getInstance();
$config = SimpleSAML_Configuration::getInstance();

$provManagerConfig = sspmod_provisionmanager_WritableConfiguration::getConfig('module_provisionmanager.php');
$aqs_config = SimpleSAML_Configuration::getConfig('module_attributequeryserver.php');

// Check permission for provisioning users
sspmod_provisionmanager_Permission::checkPermission();

$apps = $provManagerConfig->getArray('apps', array());

$attributeSources = array();
foreach ($apps as $app) {
    if (!in_array($app['attributeSource'], $attributeSources)) {
        $attributeSources[] = $app['attributeSource'];
    }
}

if(empty($attributeSources)) {
    throw new SimpleSAML_Error_NotFound('No Attribute sources found for provisioning users');
    exit();
}

if(isset($_REQUEST['sourceid'])) {
    $attributeSourceId = $_REQUEST['sourceid'];
}
else {

    if(count($attributeSources) == 1) {
        $attributeSourceId = $attributeSources[0];
    }
    else {
        $t = new SimpleSAML_XHTML_Template(SimpleSAML_Configuration::getInstance(),
                                           'provisionmanager:authsource_list.tpl.php',
                                           'provisionmanager:provisionmanager'
        );

        $t->data['sources'] = $attributeSources;
        $t->show();
        exit();
    }
}

if(!isset($_REQUEST['userid'])) {

    $aqs = new sspmod_attributequeryserver_AQS();
    
    $attributeSourcesConfig = $aqs_config->getArray('attributeSources');

    $uidfield = $attributeSourcesConfig[$attributeSourceId]['uidfield'];

    // Get users
    $users = $aqs->getUsers($attributeSourceId);

    $t = new SimpleSAML_XHTML_Template(SimpleSAML_Configuration::getInstance(),
                                       'provisionmanager:user_list.tpl.php',
                                       'provisionmanager:provisionmanager'
    );
    $t->data['sourceid'] = $attributeSourceId;
    ksort($users);
    $t->data['users'] = $users;
    $t->data['uidfield'] = $uidfield;
    $t->show();
    exit();
} else {
    $userId = $_REQUEST['userid'];
}

$t = new SimpleSAML_XHTML_Template(SimpleSAML_Configuration::getInstance(),
                                   'provisionmanager:app_list.tpl.php',
                                   'provisionmanager:provisionmanager'
);

$valid_apps = array();    

foreach ($apps as $key => $app) {
    if( $app['attributeSource'] == $attributeSourceId) {
        $valid_apps[$key] = $app;
    }
}

$t->data['sourceid'] = $attributeSourceId;
$t->data['userid'] = $userId;
$t->data['apps'] = $valid_apps;
$appids = array();
if(isset($_REQUEST['appids'])) {
    $appids = $_REQUEST['appids'];
}
$t->data['appids'] = $appids;
$t->show();
exit();

?>


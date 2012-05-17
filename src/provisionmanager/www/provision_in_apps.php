<?php

$session = SimpleSAML_Session::getInstance();
$config = SimpleSAML_Configuration::getInstance();

$provManagerConfig = sspmod_provisionmanager_WritableConfiguration::getConfig('module_provisionmanager.php');
$aqs_config = SimpleSAML_Configuration::getConfig('module_attributequeryserver.php');

// Check permission for provisioning users
sspmod_provisionmanager_Permission::checkPermission();

$apps = $provManagerConfig->getArray('apps', array());

if(!isset($_REQUEST['app2'])) {
    $t = new SimpleSAML_XHTML_Template(SimpleSAML_Configuration::getInstance(),
                                       'provisionmanager:app_list2.tpl.php',
                                       'provisionmanager:provisionmanager'
    );
    $t->data['apps'] = $apps;
    $t->show();
    exit();
}

$appId = $_REQUEST['app2'];


$endpoint = $apps[$appId]['endpoint'];

$attributeSourceId = $apps[$appId]['attributeSource'];

$aqs = new sspmod_attributequeryserver_AQS();

$attributeSourcesConfig = $aqs_config->getArray('attributeSources');

$uidfield = $attributeSourcesConfig[$attributeSourceId]['uidfield'];

// Get users from users
$users = $aqs->getUsers($attributeSourceId);

// Get users from app

$curl = curl_init();
curl_setopt ($curl, CURLOPT_URL, $apps[$appId]['users']);
curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt ($curl, CURLOPT_CERTINFO, 0);
curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, 10);
// TODO: Resolve Cookie problem, now the authentication at get_users is disabled
curl_setopt ($curl, CURLOPT_COOKIE, 'SimpleSAMLAuthToken');
$output = curl_exec($curl);

$existing_users = json_decode($output);

if(!is_array($existing_users)) {
    throw new SimpleSAML_Error_NotFound('App user list not found');
    exit();
}

$t = new SimpleSAML_XHTML_Template(SimpleSAML_Configuration::getInstance(),
                                   'provisionmanager:app_provisioner.tpl.php',
                                   'provisionmanager:provisionmanager'
);




$t->data['appId'] = $appId;
$t->data['endpoint'] = $endpoint;
$t->data['users'] = $users;
$t->data['existing_users'] = $existing_users;
$t->data['uidfield'] = $uidfield;

if (isset($_POST['action'])) {
    $t->data['action'] = $_POST['action'];
}

if (isset($_POST['userId'])) {
    $t->data['userId'] = $_POST['userId'];
}

$t->show();
exit();

?>

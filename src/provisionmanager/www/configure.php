<?php 

$session = SimpleSAML_Session::getInstance();
$config = SimpleSAML_Configuration::getInstance();

$provManagerConfig = sspmod_provisionmanager_WritableConfiguration::getConfig('module_provisionmanager.php');

$aqs_config = SimpleSAML_Configuration::getConfig('module_attributequeryserver.php');


$writable = false;
if ($provManagerConfig->isWritable()) {
    $writable = true;
}

// Require a valid user for the authsource that protect the views of this module.
$authSource = $provManagerConfig->getValue('auth', 'admin');
$apps = $provManagerConfig->getArray('apps', array());
$adminAttrId = $provManagerConfig->getValue('admin_attr_id');
$allowedAdmins = $provManagerConfig->getArray('allowed_admins', array());
$parsedAllowedAdmins = implode(',', $allowedAdmins);

$auth = new SimpleSAML_Auth_Simple($authSource);

$auth->requireAuth();

if($_POST && $writable) {
    $count = $_POST['count'];

    $apps = array();
    for ($i = 0; $i < $count; $i++) {
        if(isset($_POST['name:'.$i])) {
            $name = $_POST['name:'.$i];
            $apps[$name] = array();
            $apps[$name]['attributeSource'] = $_POST['attributeSource:'.$i];
            $apps[$name]['SPentityID'] = $_POST['SPentityID:'.$i];
            $apps[$name]['endpoint'] = $_POST['endpoint:'.$i];
            $apps[$name]['users'] = $_POST['users:'.$i];
        }
    }

    $adminAttrId = $_POST['admin_attr_id'];
    $parsedAllowedAdmins = trim($_POST['allowed_admins']);

    $output_config = array();
    $output_config['auth'] = $authSource;
    $output_config['apps'] = $apps;
    $output_config['admin_attr_id'] = $_POST['admin_attr_id'];
    $output_config['allowed_admins'] = explode(',', $parsedAllowedAdmins);

    $saved = $provManagerConfig->saveConfig('module_provisionmanager.php', $output_config);   
}

$data = array();

$data['auth'] = $authSource;
$data['apps'] = $apps;
$data['admin_attr_id'] = $adminAttrId;

$data['allowed_admins'] = $parsedAllowedAdmins; 

$data['attributeSources'] = $aqs_config->getArray('attributeSources', array());


$metadata = SimpleSAML_Metadata_MetaDataStorageHandler::getMetadataHandler();

$html = new SimpleSAML_XHTML_Template(
                $config,
                'provisionmanager:configure.tpl.php',
                'provisionmanager:provisionmanager');
$html->data['config'] = $data;
$html->data['SPs'] = $metadata->getList($set = 'saml20-sp-remote');

$html->data['writable'] = $writable;
if(isset($saved)) {
    $html->data['saved'] = $saved;
}
$html->show();

?>


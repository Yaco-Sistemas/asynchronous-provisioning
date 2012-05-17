<?php 

$session = SimpleSAML_Session::getInstance();
$aqs_config = SimpleSAML_Configuration::getConfig('module_attributequeryconsumer.php');

// Require a valid user for the authsource that protect the views of this module.
$auth = $aqs_config->getValue('auth', 'admin');

if (!$session->isValid($auth)) {
    $returnURL = $session->getData('string', 'refURL');
    if (is_null($returnURL)) {
        $returnURL = SimpleSAML_Utilities::selfURL();
    } else {
        $session->deleteData('refURL');
    }
    
    SimpleSAML_Auth_Default::initLogin(
        $auth,
        $returnURL,
        NULL,
        $_GET
    );
}

$data = array();

$data['auth'] = $aqs_config->getString('auth', '');
$data['test_aqs_url'] = $aqs_config->getString('test_aqs_url', '');
$data['consumers'] = $aqs_config->getArray('consumers', array());

if (!empty($data['consumers'])) {
    // Get SP auth sources
    $spSources = SimpleSAML_Auth_Source::getSourcesOfType('saml:SP');
    $spNameSources = array();
    foreach ($spSources AS $spSource) {
        $spNameSources[] = $spSource->getAuthId();
    }

    // Validate consumers
    foreach ($data['consumers'] as $key => $consumer) {
        $spSource = $data['consumers'][$key]['sp_source'];
        $data['consumers'][$key]['valid_sp_source'] = in_array($spSource, $spNameSources, TRUE);
    }
}


$html = new SimpleSAML_XHTML_Template(
                SimpleSAML_Configuration::getInstance(),
                'attributequeryconsumer:configure.tpl.php',
                'attributequeryconsumer:attributequeryconsumer');
$html->data['config'] = $data;
$html->show();

?>


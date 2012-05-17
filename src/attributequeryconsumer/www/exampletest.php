<?php

$aqcConfig = SimpleSAML_Configuration::getConfig('module_attributequeryconsumer.php');

// Login required
$asId = $aqcConfig->getString('auth');
$as = new SimpleSAML_Auth_Simple($asId);
$as->requireAuth();

$session = SimpleSAML_Session::getInstance();
$aqc = new sspmod_attributequeryconsumer_AQC();

$sourceId = NULL;

if (!array_key_exists('as', $_REQUEST)) {
    $t = new SimpleSAML_XHTML_Template(SimpleSAML_Configuration::getInstance(),
                                       'attributequeryconsumer:authsource_list.tpl.php',
                                       'attributequeryconsumer:attributequeryconsumer'
    );

    $spSources = SimpleSAML_Auth_Source::getSourcesOfType('saml:SP');

    $spNameSources = array();
    foreach ($spSources AS $spSource) {
        $spNameSources[] = $spSource->getAuthId();
    }

    $t->data['sources'] = $spNameSources;
    $t->show();
    exit();
}else {
    $sourceId = (string)$_REQUEST['as'];
}

$data = array();

$data['attributes'] = NULL;

$defNameId = $session->getNameId();
if (empty($defNameId)) {
	$defNameId = array();
}
if (!array_key_exists('Value', $defNameId)) {
	$defNameId['Value'] = SimpleSAML_Utilities::generateID();
}
if (!array_key_exists('Format', $defNameId)) {
	$defNameId['Format'] = SAML2_Const::NAMEID_TRANSIENT;
}
if (!array_key_exists('NameQualifier', $defNameId) || $defNameId['NameQualifier'] === NULL) {
	$defNameId['NameQualifier'] = '';
}
if (!array_key_exists('SPNameQualifier', $defNameId) || $defNameId['SPNameQualifier'] === NULL) {
	$defNameId['SPNameQualifier'] = '';
}

if (array_key_exists('nameIdFormat', $_POST)) {
	$data['nameIdFormat'] = (string)$_POST['nameIdFormat'];
} elseif (!array_key_exists('nameIdFormat', $data)) {
	$data['nameIdFormat'] = $defNameId['Format'];
}

if (array_key_exists('nameIdValue', $_POST)) {
	$data['nameIdValue'] = (string)$_POST['nameIdValue'];
} elseif (!array_key_exists('nameIdValue', $data)) {
	$data['nameIdValue'] = $defNameId['Value'];
}

if (array_key_exists('nameIdQualifier', $_POST)) {
	$data['nameIdQualifier'] = (string)$_POST['nameIdQualifier'];
} elseif (!array_key_exists('nameIdQualifier', $data)) {
	$data['nameIdQualifier'] = $defNameId['NameQualifier'];
}

if (array_key_exists('nameIdSPQualifier', $_POST)) {
	$data['nameIdSPQualifier'] = (string)$_POST['nameIdSPQualifier'];
} elseif (!array_key_exists('nameIdSPQualifier', $data)) {
	$data['nameIdSPQualifier'] = $defNameId['nameIdSPQualifier'];
}

if (array_key_exists('testAQSUrl', $_POST) && !empty($_POST['testAQSUrl'])) {
    $data['testAQSUrl'] = (string)$_POST['testAQSUrl'];
}
else {
    $data['testAQSUrl'] = $aqcConfig->getString('test_aqs_url','');
}

if (array_key_exists('attributeList', $_POST)) {
	$data['attributeList'] = (string)$_POST['attributeList'];
}
else {
    $data['attributeList'] = '';
}

if (!array_key_exists('attributes', $data)) {
	$data['attributes'] = NULL;
}

$data['testAQSUrl'] = $aqcConfig->getString('test_aqs_url', '');

if (array_key_exists('send', $_REQUEST)) {
	$nameId = array(
		'Format' => $data['nameIdFormat'],
		'Value' => $data['nameIdValue'],
		'NameQualifier' => $data['nameIdQualifier'],
		'SPNameQualifier' => $data['nameIdSPQualifier'],
	);

    $reqAttributes = array();
    if (!empty($data['attributeList'])) {
        $parsedAttributeList = explode(', ', $data['attributeList']); 
        foreach($parsedAttributeList as $value) {
            $reqAttributes[$value] = '';
        }
    }

    $response = $aqc->sendQuery($data['testAQSUrl'], $sourceId, $nameId, $reqAttributes);

    $data['attributes'] = $response->getAttributes();
}

$html = new SimpleSAML_XHTML_Template(
                SimpleSAML_Configuration::getInstance(),
                'attributequeryconsumer:exampletest.tpl.php',
                'attributequeryconsumer:attributequeryconsumer'
        );

$html->data['dataId'] = $data['nameIdValue'];
$html->data['spSource'] = $sourceId;
$html->data['testAQSUrl'] = $data['testAQSUrl'];
$html->data['nameIdFormat'] = $data['nameIdFormat'];
$html->data['nameIdValue'] = $data['nameIdValue'];
$html->data['nameIdQualifier'] = $data['nameIdQualifier'];
$html->data['nameIdSPQualifier'] = $data['nameIdSPQualifier'];
$html->data['extend'] = true;
$html->data['attributes'] = $data['attributes'];
$html->data['attributeList'] = $data['attributeList'];

$html->show();

?>

<?php

$aqsConfig = SimpleSAML_Configuration::getConfig('module_attributequeryserver.php');

$default = $aqsConfig->getArray('defaultAqs');

if (!$default) {
	throw new SimpleSAML_Error_NotFound('Could not find default attribute quuery server config data. Check at config/module_attributequeryserver.php');
}
else if(!isset($default['endpoint'])) {
    throw new SimpleSAML_Error_NotFound('Set an endpoint to the default attribute query server');
}
else if(!isset($default['attributeSource'])) {
    throw new SimpleSAML_Error_NotFound('Set an attributeSource to the default attribute query server');
}

$endpoint = $default['endpoint'];
$attributeSource = $default['attributeSource'];

$aqs = new sspmod_attributequeryserver_AQS();
$aqs->init();

$query = $aqs->getAttributeQuery();

/*  SimpleSAMLphp still does not support 'ConsumerAttributeEndpoint'
if(empty($endpoint)) {

    // If not defined at config, check if the metadata contain a ConsumerAttributeEndpoint endpoint

    $metadata = SimpleSAML_Metadata_MetaDataStorageHandler::getMetadataHandler();
        
    $spEntityId = $query->getIssuer();
    if ($spEntityId === NULL) {
	    throw new SimpleSAML_Error_BadRequest('Missing <saml:Issuer> in <samlp:AttributeQuery>.');
    }

    $spMetadata = $metadata->getMetaDataConfig($spEntityId, 'saml20-sp-remote');

    $endpoint = $spMetadata->getString('ConsumerAttributeEndpoint');    
}

*/


/* The name format of the attributes. */
$attributeNameFormat = SAML2_Const::NAMEID_TRANSIENT;

$nameID = $query->getNameId();

if ($nameID['Format'] != SAML2_Const::NAMEFORMAT_UNSPECIFIED) {
    throw new SimpleSAML_Error_BadRequest('Invalid nameID format. Require UNSPECIFIED format');
}
if (empty($nameID['Value'])) {
    throw new SimpleSAML_Error_BadRequest('nameID value required');
}

$userID = $nameID['Value'];

// Collect attributes
$attributes = $aqs->collectAttributes($attributeSource, $userID);

if (!is_array($attributes) || empty($attributes)) {
    throw new SimpleSAML_Error_BadRequest('Error collecting attributes');
}

$aqs->sendResponse($attributes, $endpoint, $attributeNameFormat);

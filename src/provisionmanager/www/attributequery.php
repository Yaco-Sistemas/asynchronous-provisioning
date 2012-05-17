<?php

if (!array_key_exists('PATH_INFO', $_SERVER)) {
	throw new SimpleSAML_Error_BadRequest('Missing provision app id');
}

$provisionId = substr($_SERVER['PATH_INFO'], 1);

$provManagerConfig = SimpleSAML_Configuration::getConfig('module_provisionmanager.php');

$appsConfig = $provManagerConfig->getArray('apps');

if(!isset($appsConfig[$provisionId])) {
	throw new SimpleSAML_Error_NotFound('Could not find provision data with id ' . $provisionId);
}

$provisionInfo = $appsConfig[$provisionId];
if(!isset($provisionInfo['endpoint'])) {
    throw new SimpleSAML_Error_NotFound('Could not find endpoint at the provision data with id ' . $provisionId);
}
else if(!isset($provisionInfo['attributeSource'])) {
    throw new SimpleSAML_Error_NotFound('Could not find attributeSource at the provision data with id ' . $provisionId);
}

$endpoint = $provisionInfo['endpoint'];
$attributeSource = $provisionInfo['attributeSource'];


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


$attributes = $aqs->collectAttributes($attributeSource, $userID);

if (!is_array($attributes) || empty($attributes)) {
    throw new SimpleSAML_Error_BadRequest('Error collecting attributes (before processfilter)');
}

$attributes = $aqs->processFilters($attributes);

if (!is_array($attributes) || empty($attributes)) {
    throw new SimpleSAML_Error_BadRequest('Error collecting attributes (after processfilter)');
}

$aqs->sendResponse($attributes, $endpoint, $attributeNameFormat);

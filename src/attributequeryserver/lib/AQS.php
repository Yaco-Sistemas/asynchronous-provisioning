<?php

/**
 * AttributeQueryServer lib of SimpleSAMLphp
 *
 * @author Sixto Martin, Yaco Sistemas. <smartin@yaco.es>
 * @package simpleSAMLphp
 * @version $Id$
 */

class sspmod_attributequeryserver_AQS {

    protected $query;
    public $config;

	public function __construct() {
        $this->config = SimpleSAML_Configuration::getConfig('module_attributequeryserver.php');
    }

    public function init() {

        $binding = SAML2_Binding::getCurrentBinding();

        $query = $binding->receive();

        if (!($query instanceof SAML2_AttributeQuery)) {
            throw new SimpleSAML_Error_BadRequest('Invalid message received to AttributeQuery endpoint.');
        }
        $this->query = $query;
    }


    public function getAttributeQuery() {

        if(empty($this->query)) {
            $this->init();
        }

        return $this->query;
    }

    /* Retrieve attributes from an attribute collection (require attributecollector module). */
    public function collectAttributes($source, $userID) {
        assert('is_string($userID)');
        assert('is_string($source)');
        $attributeSources = $this->config->getArray('attributeSources');
        if(!isset($attributeSources[$source])) {
            throw new SimpleSAML_Error_BadRequest('Invalid attribute source: '.$source);
        }

        $collectorConfig = $attributeSources[$source]['collector'];
        $uidfield = $attributeSources[$source]['uidfield'];

        if(empty($collectorConfig) || !isset($collectorConfig['class'])) {
            throw new SimpleSAML_Error_BadRequest('Attribute collection required');
        }

        $collectorClassName = SimpleSAML_Module::resolveClass($collectorConfig['class'], 'Collector', 'sspmod_attributecollector_SimpleCollector');
        $collector = new $collectorClassName($collectorConfig);

        $attr = array();
        $attr[$uidfield][0] = $userID;
        $attributes = $collector->getAttributes($attr, $uidfield);

        return $attributes;
    }


    public function processFilters($attributes) {
        $metadata = SimpleSAML_Metadata_MetaDataStorageHandler::getMetadataHandler();

        $spEntityId = $this->query->getIssuer();        
        if ($spEntityId === NULL) {
	        throw new SimpleSAML_Error_BadRequest('Missing <saml:Issuer> in <samlp:AttributeQuery>.');
        }
        $spMetadata = $metadata->getMetaDataConfig($spEntityId, 'saml20-sp-remote');

        $idpEntityId = $metadata->getMetaDataCurrentEntityID('saml20-idp-hosted');
        $idpMetadata = $metadata->getMetadataConfig($idpEntityId, 'saml20-idp-hosted');

        // ProcessingChain uses Arrays, not Objects
        $idpMetadata = $idpMetadata->toArray();
        $spMetadata = $spMetadata->toArray();

        $pc = new SimpleSAML_Auth_ProcessingChain($idpMetadata, $spMetadata, 'idp');

        $state = array();
        $state['Attributes'] = $attributes;
		$state['Destination'] = $spMetadata;
		$state['Source'] = $idpMetadata;

		$pc->processStatePassive($state);

        return $state['Attributes'];
    }


    public function sendResponse($attributes= array(), $endpoint, $attributeNameFormat=SAML2_Const::NAMEFORMAT_UNSPECIFIED) {
        assert('is_string($endpoint)');
	    assert('is_array($attributes)');

        if (!($this->query instanceof SAML2_AttributeQuery)) {
            throw new SimpleSAML_Error_BadRequest('Invalid message received to AttributeQuery endpoint.');
        }

        SimpleSAML_Logger::debug('AttributeQueryServer - Sending test response');

        $spEntityId = $this->query->getIssuer();
        if ($spEntityId === NULL) {
	        throw new SimpleSAML_Error_BadRequest('Missing <saml:Issuer> in <samlp:AttributeQuery>.');
        }

        $metadata = SimpleSAML_Metadata_MetaDataStorageHandler::getMetadataHandler();
        $idpEntityId = $metadata->getMetaDataCurrentEntityID('saml20-idp-hosted');
        $idpMetadata = $metadata->getMetadataConfig($idpEntityId, 'saml20-idp-hosted');

        $reqAttributes = $this->query->getAttributes();
        if (count(array_keys($reqAttributes)) === 0) {
	        SimpleSAML_Logger::debug('No attributes requested - return all attributes.');
	        $returnAttributes = $attributes;
        } elseif ($this->query->getAttributeNameFormat() !== $attributeNameFormat && $this->query->getAttributeNameFormat() !== SAML2_Const::NAMEFORMAT_UNSPECIFIED) {
	        SimpleSAML_Logger::debug('Requested attributes with wrong NameFormat - no attributes returned.');
	        $returnAttributes = array();
        } else {
            $returnAttributes = $this->determineAttributes($attributes, $reqAttributes);
        }

        if (empty($returnAttributes)) {
            throw new SimpleSAML_Error_NotFound('User data not found');
        }

        $spMetadata = $metadata->getMetaDataConfig($spEntityId, 'saml20-sp-remote');

        $state = array();
        $state["Attributes"] = $returnAttributes;
        $state["saml:ConsumerURL"] = $endpoint;
        $state["saml:RequestId"] = $this->query->getId();

        $assertion = sspmod_saml_IdP_SAML2::buildAssertion($idpMetadata, $spMetadata, $state);
        $assertion = sspmod_saml_IdP_SAML2::encryptAssertion($idpMetadata, $spMetadata, $assertion);

        $response = new SAML2_Response();
        $response->setRelayState($this->query->getRelayState());
        $response->setDestination($endpoint);
        $response->setIssuer($idpEntityId);
        $response->setInResponseTo($this->query->getId());
        $response->setAssertions(array($assertion));
        sspmod_saml_Message::addSign($idpMetadata, $spMetadata, $response);

        $binding = new SAML2_SOAP();
        $binding->send($response);

    }

    /* Determine which attributes we will return. */
    public function determineAttributes($attributes, $reqAttributes=array()) {
	    assert('is_array($attributes)');

        $returnAttributes = array();
        foreach ($reqAttributes as $name => $values) {
	        if (!array_key_exists($name, $attributes)) {
		        /* We don't have this attribute. */
		        continue;
	        }

	        if (count($values) === 0) {
		        /* Return all attributes. */
		        $returnAttributes[$name] = $attributes[$name];
		        continue;
	        }

	        /* Filter which attribute values we should return. */
	        $returnAttributes[$name] = array_intersect($values, $attributes[$name]);
        }

        return $returnAttributes;
    }

    public function getUsers($source) {
        assert('is_string($source)');        

        $users = array();

        $attributeSources = $this->config->getArray('attributeSources');

        if(!isset($attributeSources[$source])) {
            throw new SimpleSAML_Error_BadRequest('Invalid attribute source: '.$source);
        }

        $uidfield = $attributeSources[$source]['uidfield'];

        $collectorConfig = $attributeSources[$source]['collector'];

        if(empty($collectorConfig) || !isset($collectorConfig['class'])) {
            throw new SimpleSAML_Error_BadRequest('Attribute collection required');
        }

        $collectorClassName = SimpleSAML_Module::resolveClass($collectorConfig['class'], 'Collector', 'sspmod_attributecollector_SimpleCollector');
        $collector = new $collectorClassName($collectorConfig);

        $users = $collector->getAll($uidfield);

        return $users;

    }

}

?>

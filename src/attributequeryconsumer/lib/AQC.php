<?php

class sspmod_attributequeryconsumer_AQC {

    public function sendQuery($aqsUrl, $sourceId, $nameId, $reqAttributes=array()) {
	    assert('is_string($aqsUrl)');
	    assert('is_string($sourceId)');
	    assert('is_array($nameId)');

	    SimpleSAML_Logger::debug('AttributeQueryConsumer - Sending test request to '.$aqsUrl);

	    $source = SimpleSAML_Auth_Source::getById($sourceId, 'sspmod_saml_Auth_Source_SP');

        if (!($source instanceof sspmod_saml_Auth_Source_SP)) {
                throw new SimpleSAML_Error_NotFound('Source isn\'t a SAML SP: ' . var_export($sourceId, TRUE));
        }

        if (isset($nameId['Format']) && !empty($nameId['Format'])) {
            $nameFormat = $nameId['Format'];
        }
        else {
            $nameFormat = SAML2_Const::NAMEFORMAT_UNSPECIFIED;
        }

        $spEntityId = $source->getEntityId();

        $query = new SAML2_AttributeQuery();

        $query->setRelayState($nameId['Value']);

        $query->setDestination($aqsUrl);
        $query->setIssuer($spEntityId);
        $query->setNameId($nameId);
        $query->setAttributeNameFormat($nameFormat);


        if(!empty($reqAttributes)) {
            $query->setAttributes($reqAttributes);
        }


        $metadata = SimpleSAML_Metadata_MetaDataStorageHandler::getMetadataHandler();

	    $source = SimpleSAML_Auth_Source::getById($sourceId, 'sspmod_saml_Auth_Source_SP');
	    $spMetadata = $source->getMetadata();

        $idpEntityId = $spMetadata->getString('idp');

        $idpMetadata = $metadata->getMetaDataConfig($idpEntityId, 'saml20-idp-remote');

        $soap = new SAML2_SOAPClient();
       
        $response = $soap->send($query, $spMetadata, $idpMetadata);

		if (!$response->isSuccess()) {
			throw new Exception('Received error from Attribute Query Response.');
		}
    
	    $assertion = sspmod_saml_Message::processResponse($spMetadata, $idpMetadata, $response);
	    if (count($assertion) > 1) {
		    throw new SimpleSAML_Error_Exception('More than one assertion in received response.');
	    }
	    $assertion = $assertion[0];

		return $assertion;

    }
}

?>

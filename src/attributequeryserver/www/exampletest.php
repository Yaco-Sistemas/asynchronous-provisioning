<?php

$aqsConfig = SimpleSAML_Configuration::getConfig('module_attributequeryserver.php');

$default = $aqsConfig->getArray('defaultAqs');

if(!isset($default['endpoint'])) {
    throw new SimpleSAML_Error_NotFound('Set an endpoint to the default attribute query server');
}

/* The attributes we retrieve in order to build the Attibute Query Response. */
$attributes = array(
	'uid' => array('test'),
    'eduPersonPrincipalName' => array('test@noexample.com'),
    'eduPersonAffiliation' => array("affiliate"),
    'eduPersonScopedAffiliation' => array("affiliate@noexample.com"),
    'eduPersonPrimaryAffiliation' => array("affiliate"),
    'cn' => array('Test'),
    'sn' => array('User'),
    'displayName' => array("Test"),
	'uid' => array('test'),
    'schacPersonalUniqueID' => array('urn:mace:terena.org:schac:personalUniqueID:es:DNI:0000000T'),
    'mail' => array("test@noexample.com"),
    'irisMailMainAddress' => array("test@noexample.com"),
    'schacUserStatus' => array(
        "urn:mace:terena.org:schac:userStatus:es:noexample.com:MATES01:2011-12:student:active",
        "urn:mace:terena.org:schac:userStatus:es:noexample.com:LENGUA01:2011-12:student:active",
    ),
);


/* The name format of the attributes. */
$attributeNameFormat = SAML2_Const::NAMEID_TRANSIENT;

$aqs = new sspmod_attributequeryserver_AQS();
$aqs->init();

$aqs->sendResponse($attributes, $default['endpoint'], $attributeNameFormat);



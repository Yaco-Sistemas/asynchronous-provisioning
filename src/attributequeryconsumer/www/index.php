<?php

$config = SimpleSAML_Configuration::getInstance();
$session = SimpleSAML_Session::getInstance();
$aqc_config = SimpleSAML_Configuration::getConfig('module_attributequeryconsumer.php');
$asId = $aqc_config->getString('auth');

$links = array();

$auth = new SimpleSAML_Auth_Simple($asId);

	if($auth->isAuthenticated()) {
	    $links[] = array(
     		'href' => SimpleSAML_Module::getModuleURL('attributequeryconsumer/configure.php'),
            'text' => '{attributequeryconsumer:attributequeryconsumer:link_configure}',
            );

     	$links[] = array(
        	'href' => SimpleSAML_Module::getModuleURL('attributequeryconsumer/exampletest.php'),
		    'text' => '{attributequeryconsumer:attributequeryconsumer:link_exampletest}',
	    );
        $links[] = array(
			'href' => $auth->getLogoutURL(),
			'text' => '{status:logout}',
                        );
	}
	if (empty($links)) {
		$links[] = array(
				'href' => $auth->getLoginURL(SimpleSAML_Utilities::selfURL()),
				'text' => '{attributequeryconsumer:attributequeryconsumer:login}',
		);
	}



$html = new SimpleSAML_XHTML_Template(
		$config,
		'attributequeryconsumer:index.tpl.php',
		'attributequeryconsumer:attributequeryconsumer');
$html->data['source'] = $asId;
$html->data['links'] = $links;

$html->show();

?>


<?php

class sspmod_attributequeryconsumer_AQCHandler {

    private $aqcSource;
    public $aqc;
    private $spSource = 'default-sp';
    private $allowedAdmins = array();
    private $aqsUrl;
    private $adminAttrId = 'uid';
    private $reqAttributes = array();


	public function __construct($aqcSource) {
	    assert('is_string($aqcSource)');

        $this->aqcSource = $aqcSource;
        $this->aqc = new sspmod_attributequeryconsumer_AQC();

        $config = SimpleSAML_Configuration::getConfig('module_attributequeryconsumer.php');

        $consumersConfigs = $config->getArray('consumers');
        if(!array_key_exists($aqcSource, $consumersConfigs)) {
            echo '<img src="resources/error.png">Error when provisioning. Cound not found the consumer source: '.$aqcSource;
            exit();
        }
        $consumerConfig = $consumersConfigs[$aqcSource];

        if(array_key_exists('sp_source', $consumerConfig)) {
            $this->spSource = $consumerConfig['sp_source'];
        }

        if(array_key_exists('allowed_admins', $consumerConfig)) {
            $this->allowedAdmins = $consumerConfig['allowed_admins'];
        }

        if(array_key_exists('aqs_url', $consumerConfig)) {
            $this->aqsUrl = $consumerConfig['aqs_url'];
        }
        else {
            echo '<img src="resources/error.png">Error when provisioning. Cound not found AQS url.';
            exit();
        }

        if(array_key_exists('admin_attr_id', $consumerConfig)) {
            $this->adminAttrId = $consumerConfig['admin_attr_id'];
        }

        if(array_key_exists('required_attrs', $consumerConfig)) {
            $this->reqAttributes = $consumerConfig['required_attrs'];
        }

    }

    public function checkPermission() {
        $auth = new SimpleSAML_Auth_Simple($this->spSource);

        $auth->requireAuth();

        $loggedUserData = $auth->getAttributes();

        if (!isset($loggedUserData[$this->adminAttrId])) {
            echo '<img src="resources/error.png">Error when provisioning. Cound not found attribute '.$this->adminAttrId.' in the logged user data, this attribute is required to obtain permission to provision users';
            exit();
        }

        $loggedUserId = $loggedUserData[$this->adminAttrId][0];

        if (!in_array($loggedUserId, $this->allowedAdmins)) {
            echo '<img src="resources/error.png">Error when provisioning. '.$loggedUserId.' (logged user) is not allowed to provision users';
            exit();
        }
    }

    public function handleQuery($userId) {
	    assert('is_string($userId)');
        $nameId = array(
	        'Format' => SAML2_Const::NAMEFORMAT_UNSPECIFIED,
	        'Value' => $userId,
	        'NameQualifier' => '',
            'SPNameQualifier' => '',
        );

        return $this->aqc->sendQuery($this->aqsUrl, $this->spSource, $nameId, $this->reqAttributes);
    }
}

?>

<?php
 
/**
 * Permission for provisioning users
 *
 * @author Sixto Martin, Yaco Sistemas. <smartin@yaco.es>
 * @package simpleSAMLphp
 * @version $Id$
 */
class sspmod_provisionmanager_Permission {

    static public function checkPermission() {

        // Require a valid user for the authsource that protect the views of this module, We need that the auth source
        // is the same source used at the IdP in order to avoid the credential form in the AQS.

        $metadata = SimpleSAML_Metadata_MetaDataStorageHandler::getMetadataHandler();
        $idpEntityId = $metadata->getMetaDataCurrentEntityID('saml20-idp-hosted');
        $idpMetadata = $metadata->getMetaDataConfig($idpEntityId, 'saml20-idp-hosted');

        $authSource = $idpMetadata->getString('auth');

        $auth = new SimpleSAML_Auth_Simple($authSource);

        if(!$auth->isAuthenticated()) {
			$auth->login();
		}

        $provManagerConfig = sspmod_provisionmanager_WritableConfiguration::getConfig('module_provisionmanager.php');

        // Check if logged user is a valid admin is logged.
        $loggedUserData = $auth->getAttributes();        

        $adminAttrId = $provManagerConfig->getString('admin_attr_id');
        $allowedAdmins = $provManagerConfig->getArray('allowed_admins');

        if (!isset($loggedUserData[$adminAttrId])) {
	        throw new SimpleSAML_Error_NotFound('Cound not found attribute '.$adminAttrId.' in the logged user data, this attribute is required to obtain permission to provision users');
        }

        $loggedUserId = $loggedUserData[$adminAttrId][0];

        if (!in_array($loggedUserId, $allowedAdmins)) {
	        throw new SimpleSAML_Error_InvalidCredential('You are not authorized for provisioning users');
        }
    }

}

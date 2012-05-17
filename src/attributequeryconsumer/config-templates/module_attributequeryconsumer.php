<?php
/* 
 * The configuration of simpleSAMLphp attributequeryconsumer module
 */

$config = array (

    // Authentication source that protect the Attribute Query Consumer Manager.  ex. 'admin'
    'auth' => 'admin',

    // Attribute Query Server URL for testing if available
    'test_aqs_url' => 'https://example.com/idp/simplesaml/module.php/attributequeryserver/exampletest.php',

    // Consumers
    'consumers' => array (
        'demoapp1' => array (
            // SAML SP Source .
            'sp_source' => 'example1',

            // Url of the Attribute Query Server
            'aqs_url' => 'https://example.com/idp/simplesaml/module.php/provisionmanager/attributequery.php/example1',

            // admin ID field
            'admin_attr_id' => 'uid',

            // Array of allowed admins, the value of the user ID field must match any attribute of the list to get permission for the attribute query execution
            'allowed_admins' => array('admin', 'admin2'),
        ),
        'example2' => array (
            // SAML SP Source .
            'sp_source' => 'example2',

            // Url of the Attribute Query Server
            'aqs_url' => 'https://example.com/idp/simplesaml/module.php/provisionmanager/attributequery.php/example2',

            // admin ID field
            'admin_attr_id' => 'uid',

            // Array of allowed admins, the value of the user ID field must match any attribute of the list to get permission for the attribute query execution
            'allowed_admins' => array('admin', 'admin2'),
        ),
    ),


);

?>

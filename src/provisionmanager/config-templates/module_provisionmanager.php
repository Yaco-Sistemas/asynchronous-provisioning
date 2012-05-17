<?php

/* 
 * The configuration of simpleSAMLphp provisionmanager module
 */

$config = array(

// Authentication source that protect the configuration view
  'auth' => 'admin',

// List of apps
  'apps' => 
  array (
    'examlple1' => array(
        // Url of the endpoint where is located the provision logic
        'endpoint' => 'https://example.com/example1/provision.php',
        // entityId of the SP that protect the provision view of the app
        'SPentityID' => 'https://example.com/simplesaml/module.php/saml/sp/metadata.php/examlple1',
        // Source with the data of the users for provisioning in the app, require a valid source defined at the attributequery config file
        'attributeSource' => 'attribute_source1',
        // URL that return a JSON with the userID of the registered users on the app        
        'users' => 'https://sined.yaco.es/demoapp1/get_users.php',
    ),
    'examlple2' => array(
        'endpoint' => 'https://example.com/example1/provision.php',
        'SPentityID' => 'https://example.com/simplesaml/module.php/saml/sp/metadata.php/examlple2',
        'attributeSource' => 'attribute_source2',
        'users' => 'https://sined.yaco.es/demoapp2/get_users.php',
    ),
  ),

// admin ID field  
  'admin_attr_id' => 'uid',

// Array of allowed admins, the value of the user ID field must match any attribute of the list to get permission for the provisionmanager views execution
  'allowed_admins' =>
      array (
        0 => 'admin',
        1 => 'user1',
      ),

);

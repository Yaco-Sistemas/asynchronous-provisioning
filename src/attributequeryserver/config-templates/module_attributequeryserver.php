<?php

/* 
 * The configuration of simpleSAMLphp attributequeryserver module
 */

$config = array(

    // This param is required to use the www/attributequery.php view.
    'defaultAqs' => array (
        'endpoint' => 'https://sined.yaco.es/demoapp1/provision.php',
        'attributeSource' => 'ldap-source',
    ),

    // Attribute Sources
    'attributesources' => array(
	    'example-bd-source' => array(
            // Field of the identificator used at the collector
            'uidfield' => 'uid',
		    /*	 Configuration of the attributecollection source 
		             (You may install the attributecollector module available at https://forja.rediris.es/svn/confia/attributecollector/trunk)
		             SQL and LDAP are supported.
		    */
	        'collector' => array(
                    'class' => 'attributecollector:SQLCollector',
                    'dsn' => array('oci:dbname=first', 'mysql:host=localhost;dbname=second'),
                    'username' => array('first', 'second'),
                    'password' => array('first', 'second'),
                    'query' => array("SELECT sid as SUBJECT from subjects where uid=:uidfield",
                                     "SELECT sid as SUBJECT from subjects2 where uid=:uidfield AND status='OK'",
    	  	        ),
                    'get_all_query' => array("SELECT sid as SUBJECT from subjects", "SELECT sid as SUBJECT from subjects2"),
            ),
        ),
	    'example-ldap-source' => array(
            // Field of the identificator used at the collector
            'uidfield' => 'uid',
            //  Configuration of the attributecollection source
            'collector' => array(
                'class' => 'attributecollector:LDAPCollector',
                'host' => 'myldap.srv',
                'port' => 389,
                'binddn' => 'cn=myuser',
                'password' => 'yaco',
                'basedn' => 'dc=my,dc=org',
                'searchfilter' => 'uid=:uidfield',
                // List of requested attributes. OPTIONAL
                'attrlist' => array(
                       // Final attr => LDAP attr
                       'myClasses' => 'objectClass',
                ),
            ),
        ),
    ),
);	



README
======

Elements and modules
--------------------

IdP
~~~

 * provisionmanager.  This module let admins to do provisioning of users in applications using a web interface (There are 2 use cases). 

 * attributecollector.  Used to retrieve user data from LDAPs or Databases (Get it using:   svn co https://forja.rediris.es/svn/confia/attributecollector/trunk).

 * attributequeryserver. This module implements the logic to recieve an Attribute Query Request, handle it and build an Attribute Query Response.


SP
~~

 * attributequeryconsumer. This module implements the logic to build an Attribute Query Request.


Demo App
~~~~~~~~

In this demo we provide code of an example web application called demoapp1.



Patches
-------

There are 2 patches that may be applied to simpleSAMLphp: 

 * idp_patch.diff --> buildAssertion and encryptAssertion functions are private and can not be executed from the Attribute Query Server so must be change to public.
 * sp_patch.diff  --> If your cert is self-signed you must apply this patch in order to disable the cert validation, otherwise the SOAP process won't be executed.

Just copy the files at the simpleSAMLphp folder and execute:       patch -p0 < <patch_name>




Notes
-----

 * If you want use the configuration view of the "manager for provisioning" the config folder must be writable.

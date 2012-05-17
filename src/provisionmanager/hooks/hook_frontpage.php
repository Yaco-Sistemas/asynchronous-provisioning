<?php
/**
 * Hook to add the provisionmanager module to the frontpage.
 *
 * @param array &$links  The links on the frontpage, split into sections.
 */
function provisionmanager_hook_frontpage(&$links) {
        assert('is_array($links)');
        assert('array_key_exists("links", $links)');

        $links['federation'][] = array(
               'href' => SimpleSAML_Module::getModuleURL('provisionmanager/configure.php'),
               'text' => '{provisionmanager:provisionmanager:link_configure}',
        );

        $links['federation'][] = array(
               'href' => SimpleSAML_Module::getModuleURL('provisionmanager/provisioner_steps.php'),
               'text' => '{provisionmanager:provisionmanager:link_provisioner}',
        );

        $links['federation'][] = array(
               'href' => SimpleSAML_Module::getModuleURL('provisionmanager/provision_in_apps.php'),
               'text' => '{provisionmanager:provisionmanager:link_provisioner2}',
        );


}
?>


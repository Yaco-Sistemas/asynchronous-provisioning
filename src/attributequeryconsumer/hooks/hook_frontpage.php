<?php
/**
 * Hook to add the attributequeryconsumer module to the frontpage.
 *
 * @param array &$links  The links on the frontpage, split into sections.
 */
function attributequeryconsumer_hook_frontpage(&$links) {
        assert('is_array($links)');
        assert('array_key_exists("links", $links)');

        $links['federation'][] = array(
                'href' => SimpleSAML_Module::getModuleURL('attributequeryconsumer/index.php'),
               'text' => '{attributequeryconsumer:attributequeryconsumer:link_panel}',
        );

}
?>


<?php
/**
 * Template to show list of apps.
 *
 */

$this->data['jquery'] = array('version' => '1.6', 'core' => TRUE, 'ui' => TRUE, 'css' => FALSE);
$this->data['header'] = $this->t('{provisionmanager:provisionmanager:main_panel}').' (2)';
$this->data['urlhome'] = SimpleSAML_Module::getModuleURL('provisionmanager/provision_in_apps.php');


$this->includeAtTemplateBase('includes/header.php');
?>
<h1><?php echo $this->data['header']; ?></h1>
<h2><?php echo $this->t('{provisionmanager:provisionmanager:select_app}'); ?></h2>

<?php

$script = '';


echo '<form id="select_app2" method="POST">';

foreach ($this->data['apps'] as $key => $app) {
    echo '<input type="radio" name="app2" value="'.$key.'" > '.$key.'<br>';
}

echo '<input type="submit" name="sumbit" value="'.$this->t('{provisionmanager:provisionmanager:provision}').'">';
echo '</form>';

echo '<br><br><a href="'.$this->data['urlhome'].'">'.$this->t('{provisionmanager:provisionmanager:link_return}').'</a>';

$this->includeAtTemplateBase('includes/footer.php');
?>

<?php
/**
 * Template to show list of configured authentication sources.
 *
 */
$this->data['header'] = $this->t('{provisionmanager:provisionmanager:main_panel}');
$this->data['urlhome'] = SimpleSAML_Module::getModuleURL('provisionmanager/provisioner_steps.php');


$this->includeAtTemplateBase('includes/header.php');
?>
<h1><?php echo $this->data['header']; ?></h1>
<h2><?php echo $this->t('{provisionmanager:provisionmanager:select_user_source}'); ?></h2>
<ul>
<?php
foreach ($this->data['sources'] as $id) {
	echo '<li><a href="?sourceid=' . htmlspecialchars(urlencode($id)) . '">' . htmlspecialchars($id) . '</a></li>';
}
?>
</ul>

<?php

echo '<br><a href="'.$this->data['urlhome'].'">'.$this->t('{provisionmanager:provisionmanager:link_return}').'</a>';

$this->includeAtTemplateBase('includes/footer.php');
?>

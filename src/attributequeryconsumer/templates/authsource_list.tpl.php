<?php
/**
 * Template to show list of configured authentication sources.
 *
 */
$this->data['header'] = $this->t('{attributequeryconsumer:attributequeryconsumer:link_panel}');

$this->includeAtTemplateBase('includes/header.php');
?>
<h1><?php echo $this->data['header']; ?></h1>
<h2><?php echo $this->t('{attributequeryconsumer:attributequeryconsumer:select_spsource}'); ?></h2>
<ul>
<?php
foreach ($this->data['sources'] as $id) {
	echo '<li><a href="?as=' . htmlspecialchars(urlencode($id)) . '">' . htmlspecialchars($id) . '</a></li>';
}
?>
</ul>

<?php
$this->includeAtTemplateBase('includes/footer.php');
?>

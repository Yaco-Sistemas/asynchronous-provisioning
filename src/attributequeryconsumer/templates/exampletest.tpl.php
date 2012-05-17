<?php

$this->data['baseurlpath'] = $this->configuration->getBaseURL();
$this->data['urlhome'] = SimpleSAML_Module::getModuleURL('attributequeryconsumer/index.php');

$this->data['header'] = $this->t('{attributequeryconsumer:attributequeryconsumer:link_panel}');
$this->data['head'] = '<link rel="stylesheet" href="resources/attributequeryconsumer.css" type="text/css">';

$this->includeAtTemplateBase('includes/header.php');

if(isset($this->data['userMessage'])){
    echo '<div class="umesg">'.$this->t($this->data['userMessage']),'</div>';
}

$dataId = $this->data['dataId'];
assert('is_string($dataId)');

$testAQSUrl = $this->data['testAQSUrl'];
assert('is_string($testAQSUrl)');

$nameIdFormat = $this->data['nameIdFormat'];
assert('is_string($nameIdFormat)');

$nameIdValue = $this->data['nameIdValue'];
assert('is_string($nameIdValue)');

$nameIdQualifier = $this->data['nameIdQualifier'];
assert('is_string($nameIdQualifier)');

$nameIdSPQualifier = $this->data['nameIdSPQualifier'];
assert('is_string($nameIdSPQualifier)');

$attributeList = $this->data['attributeList'];
assert('is_string($attributeList)');

$attributes = $this->data['attributes'];
assert('is_null($attributes) || is_array($attributes)');


?>

<h1> <?php echo $this->t('{attributequeryconsumer:attributequeryconsumer:link_panel}') ?> </h1>

<h2><?php echo $this->t('{attributequeryconsumer:attributequeryconsumer:exampletest_title}') ?></h2>

<p><?php echo $this->t('{attributequeryconsumer:attributequeryconsumer:exampletest_desc}'); ?></p>

<form action="?" method="post">

<?php

if (!$this->data['extend'] && empty($testAQSUrl)) {
    $testAQSUrl = $this->t('{attributequeryconsumer:attributequeryconsumer:undefined_test_url}');
}
else {
?>
    <p>
    <input type="submit" name="send" value="<?php echo $this->t('{attributequeryconsumer:attributequeryconsumer:send_query_button}'); ?>" />
    </p>
<?php
}
?>

<h4><?php echo $this->t('{attributequeryconsumer:attributequeryconsumer:data_request}'); ?></h4>

<input name="dataId" type="hidden" value="<?php echo htmlspecialchars($dataId); ?>" />

<p>
<label for="spSource"><?php echo $this->t('{attributequeryconsumer:attributequeryconsumer:help_spsource}'); ?>:</label><br />
<input readonly="readonly" name="as" type="text" size="80" value="<?php echo htmlspecialchars($this->data['spSource']); ?>" />
</p>


<p>
<label for="testAQSUrl"><?php echo $this->t('{attributequeryconsumer:attributequeryconsumer:help_aqs_url}'); ?>:</label><br />
<input name="testAQSUrl" type="text" size="80" value="<?php echo htmlspecialchars($testAQSUrl); ?>" />
</p>
<p>
<label for="nameIdFormat"><?php echo $this->t('{attributequeryconsumer:attributequeryconsumer:help_nameid_format}'); ?>:</label><br />
<input name="nameIdFormat" type="text" size="80" value="<?php echo htmlspecialchars($nameIdFormat); ?>" />
</p>

<p>
<label for="nameIdValue"><?php echo $this->t('{attributequeryconsumer:attributequeryconsumer:help_nameid_value}'); ?>:</label><br />
<input name="nameIdValue" type="text" size="80" value="<?php echo htmlspecialchars($nameIdValue); ?>" />
</p>

<p>
<label for="nameIdQualifier"><?php echo $this->t('{attributequeryconsumer:attributequeryconsumer:help_namequalifer}'); ?>:</label><br />
<input name="nameIdQualifier" type="text" size="80" value="<?php echo htmlspecialchars($nameIdQualifier); ?>" />
</p>

<p>
<label for="nameIdSPQualifier"><?php echo $this->t('{attributequeryconsumer:attributequeryconsumer:help_spnamequalifer}'); ?>:</label><br />
<input name="nameIdSPQualifier" type="text" size="80" value="<?php echo htmlspecialchars($nameIdSPQualifier); ?>" />
</p>

<p>
<label for="attributeList"><?php echo $this->t('{attributequeryconsumer:attributequeryconsumer:help_attributelist}'); ?>:</label><br />
<input name="attributeList" type="text" size="80" value="<?php echo htmlspecialchars($attributeList); ?>" />
</p>

</form>

<?php
if ($attributes !== NULL) {

    echo '<h3>'.$this->t('{attributequeryconsumer:attributequeryconsumer:attr_received}').'</h3><dl>';

    $config = SimpleSAML_Configuration::getInstance();
    $t2 = new SimpleSAML_XHTML_Template($config, 'status.php', 'attributes');
    $attr_printer = new sspmod_attributequeryconsumer_AttrPrinter();
    echo $attr_printer->present_attributes($t2, $attributes, '');
}

echo '<br><a href="'.$this->data['urlhome'].'">'.$this->t('{attributequeryconsumer:attributequeryconsumer:link_return}').'</a>';

$this->includeAtTemplateBase('includes/footer.php');

 ?>

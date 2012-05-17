<?php
/**
 * Template to show list of configured authentication sources.
 *
 */
$this->data['head'] = '<link rel="stylesheet" href="resources/provisionmanager.css" type="text/css">';
$this->data['jquery'] = array('version' => '1.6', 'core' => TRUE, 'ui' => TRUE, 'css' => FALSE);

$this->data['header'] = $this->t('{provisionmanager:provisionmanager:main_panel}');
$this->data['urlhome'] = SimpleSAML_Module::getModuleURL('provisionmanager/provisioner_steps.php');

$this->includeAtTemplateBase('includes/header.php');
?>
<h1><?php echo $this->data['header']; ?></h1>
<h2><?php echo $this->t('{provisionmanager:provisionmanager:select_user}'); ?></h2>
<ul>
<?php

$show_text = $this->t('{provisionmanager:provisionmanager:shown}');
$hide_text = $this->t('{provisionmanager:provisionmanager:hidden}');

$config = SimpleSAML_Configuration::getInstance();
$t2 = new SimpleSAML_XHTML_Template($config, 'status.php', 'attributes');
$attr_printer = new sspmod_provisionmanager_AttrPrinter();


echo    '<form id="selectUser" method="GET">';
echo    '<input type="hidden" name="sourceid" value="'.htmlspecialchars($this->data['sourceid']).'">';

if(count($this->data['users']) > 10) {
    echo '<input type="submit" name="submit" value="'.$this->t('{provisionmanager:provisionmanager:select}').'"><br>';
}

 
foreach ($this->data['users'] as $user) {
    $id = $user[$this->data['uidfield']][0];
	echo '<input type="radio" name="userid" value="'.htmlspecialchars($id).'"> <b>'.htmlspecialchars($id).'</b>';
    echo '<span id="'.$id.'_Shown" class="linkshown"> ( '.$show_text.' )</span>';
    echo '<span id="'.$id.'_Hidden" class="linkhidden"> ( '.$hide_text.' )</span><br>';
    echo $attr_printer->present_attributes($t2, $user, '', $id.'_Info');
}

echo '<input type="submit" name="submit" value="'.$this->t('{provisionmanager:provisionmanager:select}').'">';
    
echo '<br><a href="'.$this->data['urlhome'].'">'.$this->t('{provisionmanager:provisionmanager:link_return}').'</a>';

echo '
<script language="javascript">
$(document).ready(function() {';

foreach ($this->data['users'] as $user) {
    $id = $user[$this->data['uidfield']][0];

    echo '
       $("#'.$id.'_Shown").click(function(){
         $("#'.$id.'_Shown").hide();
         $("#'.$id.'_Hidden").show();
         $("#'.$id.'_Info").show();
       });
       $("#'.$id.'_Hidden").click(function(){
         $("#'.$id.'_Shown").show();
         $("#'.$id.'_Hidden").hide();
         $("#'.$id.'_Info").hide();
       }); 
    ';

}

echo '
});
</script>';

$this->includeAtTemplateBase('includes/footer.php');

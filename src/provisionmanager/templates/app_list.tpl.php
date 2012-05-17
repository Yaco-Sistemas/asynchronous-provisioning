<?php
/**
 * Template to show list of configured authentication sources.
 *
 */

$this->data['jquery'] = array('version' => '1.6', 'core' => TRUE, 'ui' => TRUE, 'css' => FALSE);
$this->data['header'] = $this->t('{provisionmanager:provisionmanager:main_panel}');
$this->data['urlhome'] = SimpleSAML_Module::getModuleURL('provisionmanager/provisioner_steps.php');


$this->includeAtTemplateBase('includes/header.php');
?>
<h1><?php echo $this->data['header']; ?></h1>

<?php

$script = '';


echo '<form id="user_provision" method="POST">';

echo '<input type="hidden" name="sourceid" value="'.$this->data['sourceid'].'">';
echo '<input type="hidden" name="userid" value="'.$this->data['userid'].'">';

echo '<h3>'.$this->t('{provisionmanager:provisionmanager:provision}').' '.$this->data['userid'].':<h3>';

foreach ($this->data['apps'] as $key =>$app) {
    if(isset($app['endpoint']) && !empty($app['endpoint'])) {
        echo '<div id="'.$key.'" style="margin-bottom:30px;">';
        echo '<input type="checkbox" name="appids[]" value="'.$key.'" '.(in_array($key, $this->data['appids'])? 'checked="checked"':'').'> '.$key;
        if(isset($this->data['appids']) && in_array($key, $this->data['appids'])) {
            echo('<iframe scrolling="no" height="30px" style="margin-left:10px;position:absolute;border:0px;margin-top:-5px;" src="' . htmlspecialchars($app['endpoint'].'?userID='.$this->data['userid']) . '"></iframe>');
        }
        echo '</div>';
    }
}

echo '<input type="submit" name="sumbit" value="'.$this->t('{provisionmanager:provisionmanager:provision}').'">';
echo '</form>';

echo '<br><br><a href="'.$this->data['urlhome'].'">'.$this->t('{provisionmanager:provisionmanager:link_return}').'</a>';

$this->includeAtTemplateBase('includes/footer.php');
?>

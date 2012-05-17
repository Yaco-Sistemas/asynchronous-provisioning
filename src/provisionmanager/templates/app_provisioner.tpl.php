<?php
/**
 * Template to show list of apps.
 *
 */

$this->data['jquery'] = array('version' => '1.6', 'core' => TRUE, 'ui' => TRUE, 'css' => FALSE);
$this->data['head'] = '<link rel="stylesheet" href="resources/provisionmanager.css" type="text/css">';
$this->data['header'] = $this->t('{provisionmanager:provisionmanager:main_panel}').' (2)';
$this->data['urlhome'] = SimpleSAML_Module::getModuleURL('provisionmanager/provision_in_apps.php');


$this->includeAtTemplateBase('includes/header.php');
?>
<h1><?php echo $this->data['header']; ?></h1>
<h2><?php echo $this->t('{provisionmanager:provisionmanager:source_user_status}').': '.$this->data['appId']; ?></h2>

<?php

$config = SimpleSAML_Configuration::getInstance();
$t2 = new SimpleSAML_XHTML_Template($config, 'status.php', 'attributes');
$attr_printer = new sspmod_provisionmanager_AttrPrinter();

echo '<form id="provisionUser" method="post">';

echo '<input type="hidden" name="app2" value="'.$this->data['appId'].'" >';

echo '<input type="hidden" id="userId" name="userId" value="" >';

echo '<input type="hidden" id="action" name="action" value="" >';


echo '<table width="70%" style="text-align: center;"><tr><td width="10%">Action</td><td width="35%">User</td><td width="55%">Status</td></tr></table>';

$i= 0;

foreach ($this->data['users'] as $userId => $user) {

    $exists = in_array($userId, $this->data['existing_users']);
    
    echo '<table width="70%" style="text-align: center;">';

    echo '<tr '.(($i % 2 == 0) ? '' : 'style="background:#e5e5e5;"').'><td width="10%" height="30px">';

    if(isset($this->data['action']) && isset($this->data['userId']) && $userId == $this->data['userId']) {
        echo '<img onClick="javascript:refresh(\''.$userId.'\');" src="resources/images/refresh.png" style="display:inline;margin-bottom: -3px;cursor: pointer">';
    }
    else {
        if ($exists) {
            echo '<img onClick="javascript:appDeprovision(\''.$userId.'\');" src="resources/images/delete.png" style="display:inline;margin-bottom: -3px;cursor: pointer">';
        }
        else {
            echo '<img onClick="javascript:appProvision(\''.$userId.'\');" src="resources/images/add.png" style="display:inline;margin-bottom: -3px;cursor: pointer">';
        }
    }

    echo'</td><td width="35%">';

    $id = $user[$this->data['uidfield']][0];
	echo '<span style="font-weight:bold;">'.htmlspecialchars($id).'</span>';

    echo '<span id="'.$id.'_Shown" class="linkshown"><img src="resources/images/info.png" style="display:inline;padding-left:7px;margin-bottom: -3px;" ></span>';

    echo '</td>';
    

    echo '<td width="55%">';

    if(isset($this->data['action']) && isset($this->data['userId']) && $userId == $this->data['userId']) {
            echo('<iframe scrolling="no" height="30px" style="border:0px;" src="' . htmlspecialchars($this->data['endpoint'].'?userID='.$this->data['userId']) . '&action='.$this->data['action'].'"></iframe>');
    }
    else {

        if ($exists) {
            echo '<img src="resources/images/check.png" style="display:inline;margin-bottom: -3px;">';
        }
        else {
            echo '<img src="resources/images/cross.png" style="display:inline;margin-bottom: -3px;">';
        }
    }

    
    echo '</td></tr>';    

    echo '</table>';

    echo $attr_printer->present_attributes($t2, $user, '', $id.'_Info');

    $i = $i + 1;
}

echo '</table>';

echo '</form>';

echo '
<script language="javascript">
$(document).ready(function() {';

foreach ($this->data['users'] as $user) {
    $id = $user[$this->data['uidfield']][0];

    echo '
       $("#'.$id.'_Shown").click(function(){
         $("#'.$id.'_Info").toggle();
       });
    ';

}

echo '}); 

function appDeprovision(userId) {
    $("input[type=hidden][name=userId]").val(userId);
    $("input[type=hidden][name=action]").val("deprovision");
    $("#provisionUser").submit();
}

function appProvision(userId) {
    $("input[type=hidden][name=userId]").val(userId);
    $("input[type=hidden][name=action]").val("provision");
    $("#provisionUser").submit();

}

function refresh(userId) {
    $("input[type=hidden][name=userId]").val("");
    $("input[type=hidden][name=action]").val("");
    $("#provisionUser").submit();


}

</script>';

echo '<br><br><a href="'.$this->data['urlhome'].'">'.$this->t('{provisionmanager:provisionmanager:link_return}').'</a>';

$this->includeAtTemplateBase('includes/footer.php');
?>

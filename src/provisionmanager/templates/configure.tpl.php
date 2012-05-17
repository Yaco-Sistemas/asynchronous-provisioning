<?php

$this->data['baseurlpath'] = $this->configuration->getBaseURL();
$this->data['backurl'] = SimpleSAML_Module::getModuleURL('core/frontpage_federation.php');

$this->data['header'] = $this->t('{provisionmanager:provisionmanager:main_panel}');
$this->data['head'] = '<link rel="stylesheet" href="resources/provisionmanager.css" type="text/css">';
$this->data['head'] .= '<script src="resources/js/jquery.validate.js" type="text/javascript"></script> ';

if(isset($this->data['saved'])) {
    $this->data['head'] .= '<script src="resources/js/jquery.slidingmessage.js" type="text/javascript"></script> ';
}


$this->data['jquery'] = array('version' => '1.6', 'core' => TRUE, 'ui' => TRUE, 'css' => FALSE);


$this->includeAtTemplateBase('includes/header.php');

if(isset($this->data['userMessage'])){ ?>
        <div class="umesg"><?php echo $this->t($this->data['userMessage']); ?></div>
<?php }?>

<h1> <?php echo $this->t('{provisionmanager:provisionmanager:main_panel}'); ?> </h1>

<h2><?php echo $this->t('{provisionmanager:provisionmanager:attr_sources_title}'); ?>
    <span id="attributeSourcesShown">( <?php echo $this->t('{provisionmanager:provisionmanager:shown}');  ?> )</span>
    <span id="attributeSourcesHidden">( <?php echo $this->t('{provisionmanager:provisionmanager:hidden}');  ?> )</span>
</h2>

<div id="attributeSources">

<?php

if (empty($this->data['config']['attributeSources'])) {
    echo '<p>'.$this->t('{provisionmanager:provisionmanager:not_attr_sources}').'</p>';
}
else {
    foreach ($this->data['config']['attributeSources'] as $key => $attrSource) {

        $class = $attrSource['collector']['class'];
        $type = str_replace('attributecollector:', '', $class);


        echo '<b>'.$key.'</b> ('.$type.')';        
        echo '<div class="enablebox attrsources"><table>';
        echo '<tr class="enabled"><td>uidfield</td><td width="80%"> ' . $attrSource['uidfield'] . '</td></tr>';

        foreach ($this->data['config']['attributeSources'][$key]['collector'] as $param => $value) {
            if (in_array($param, array('password', 'class'))) {
                continue;
            }
            if(is_array($value)) {
                $value = join('<br>', $value);
            }

            echo '<tr class="enabled"><td>'.$param.'</td><td width="80%">' . $value . '</td></tr>';    
        }
        echo('</tbody></table></div>');
    }
}
echo '</div>';

?>

<h2><?php echo $this->t('{core:frontpage:configuration}'); ?><img id="helpConfigurationFile" src="resources/images/help.png" title="<?php echo $this->t('{provisionmanager:provisionmanager:help_configuration_file}'); ?>"></h2>

<?php

echo '<form id="configureForm" action="?" method="POST">';

echo '<div class="enablebox"><table>';

echo '<thead><tr class="enabled"><td>'.$this->t('{provisionmanager:provisionmanager:parameter}').'</td>'.
     '<td width="80%">'.$this->t('{provisionmanager:provisionmanager:value}').'</td><td></td></tr></thead>';

echo '<tr class="enabled"><td>auth</td><td width="80%"> ' . $this->data['config']['auth'] . '</td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{provisionmanager:provisionmanager:help_auth}').'"></td></tr>';

echo '<tr class="enabled"><td>admin_attr_id</td><td width="80%"><input name ="admin_attr_id" value="' . $this->data['config']['admin_attr_id'] . '"></td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{provisionmanager:provisionmanager:help_admin_attr_id}').'"></td></tr>';

echo '<tr class="enabled"><td>allowed_admins</td><td width="80%"><input name ="allowed_admins" value="' . $this->data['config']['allowed_admins'] . '" size="80"></td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{provisionmanager:provisionmanager:help_allowed_admins}').'"></td></tr>';

echo('</tbody></table></div>');


echo '<h3>'.$this->t('{provisionmanager:provisionmanager:app_provision_title}').'</h3>';


$count = 0;
foreach ($this->data['config']['apps'] as $name => $app ) {

    echo '<div class="container">';

        echo '<div class="app_title"><input type="text" value="'.$name.'" name="name:'.$count.'"  class="required"><img style="display:inline;padding-left:5px;" src="resources/images/help.png" title="'.$this->t('{provisionmanager:provisionmanager:help_name}').'">';
        if($this->data['writable']) {
            echo'<div class="action_button"><a><span class="delete">Delete</span></a></div></div>';
        }
        echo '<div id="'.$name.'" class="enablebox"><table>';

        echo '<thead><tr class="enabled"><td>'.$this->t('{provisionmanager:provisionmanager:parameter}').'</td>'.
         '<td width="80%">'.$this->t('{provisionmanager:provisionmanager:value}').'</td><td></td></tr></thead>';

        echo '<tr class="enabled"><td>attributeSource</td><td width="80%"><select name="attributeSource:'.$count.'" class="required">';

        foreach($this->data['config']['attributeSources'] as $key => $attrSource) {
            echo '<option name="'.$key.'" '.($app['attributeSource'] == $key? 'selected="selected"' : '').'>'.$key.'</option>';
        }

        echo '</select></td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{provisionmanager:provisionmanager:help_attribute_source}').'"></td></tr>';

        echo '<tr class="enabled"><td>SPentityID</td><td width="80%"><select name="SPentityID:'.$count.'" class="required">';
        foreach($this->data['SPs'] as $SP) {
            echo '<option name="'.$SP['entityid'].'" '.($app['SPentityID'] == $SP['entityid']? 'selected="selected"' : '').'>'.$SP['entityid'].'</option>';
        }

        echo '</select></td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{provisionmanager:provisionmanager:help_spentityid}').'"></td></tr>';

        echo '<tr class="enabled"><td>endpoint</td><td width="80%"><input type="text" value="'.$app['endpoint'].'" size="70" name="endpoint:'.$count.'" class="required"></td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{provisionmanager:provisionmanager:help_endpoint}').'"></td></tr>';

        echo '<tr class="enabled"><td>users</td><td width="80%"><input type="text" value="'.$app['users'].'" size="70" name="users:'.$count.'" class="required"></td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{provisionmanager:provisionmanager:help_users}').'"></td></tr>';

        echo('</tbody></table></div>');
    echo '</div>';

    $count++;
}

if ($this->data['writable']) {

 echo '<br>';
 echo '<input id="count" name="count" type="hidden" value="'.$count.'">';
 echo '<div id="addButton" class="action_button"><a><span id="cloneAction">Add</span></a></div>';

    echo '<div id="cloneAppTemplate" class="container" style="display:none">';

    echo '<div class="app_title"><input name="name" type="text" value=""><img style="display:inline;padding-left:5px" src="resources/images/help.png" title="'.$this->t('{provisionmanager:provisionmanager:help_name}').'"><div class="action_button"><a><span class="delete">Delete</span></a></div></div>';

    echo '<div class="enablebox"><table>';

    echo '<thead><tr class="enabled"><td>'.$this->t('{provisionmanager:provisionmanager:parameter}').'</td>'.
     '<td width="80%">'.$this->t('{provisionmanager:provisionmanager:value}').'</td><td></td></tr></thead>';

    echo '<tr class="enabled"><td>attributeSource</td><td width="80%"><select name="attributeSource">';

    foreach($this->data['config']['attributeSources'] as $key => $attrSource) {
        echo '<option name="'.$key.'">'.$key.'</option>';
    }

    echo '</select></td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{provisionmanager:provisionmanager:help_attribute_source}').'"></td></tr>';

    echo '<tr class="enabled"><td>SPentityID</td><td width="80%"><select name="SPentityID">';

    foreach($this->data['SPs'] as $SP) {
        echo '<option name="'.$SP['entityid'].'">'.$SP['entityid'].'</option>';
    }

    echo '</select></td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{provisionmanager:provisionmanager:help_spentityid}').'"></td></tr>';

    echo '<tr class="enabled"><td>endpoint</td><td width="80%"><input type="text" value="" size="70" name="endpoint"></td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{provisionmanager:provisionmanager:help_endpoint}').'"></td></tr>';

    echo '<tr class="enabled"><td>users</td><td width="80%"><input type="text" value="" size="70" name="users"></td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{provisionmanager:provisionmanager:help_users}').'"></td></tr>';

    echo('</tbody></table></div>');

 echo '</div>';

echo '<input id ="sbmt" type="submit" name="send" value="'.$this->t('{provisionmanager:provisionmanager:save}').'">';
?>
    <div class="error_msg"> 
      <img src="resources/images/warning.png" alt="Warning!" /> 
       <span><?php echo $this->t('{provisionmanager:provisionmanager:form_errors}'); ?></span> 
    </div> 

    </form>

<?php
}
else {
    echo '<span style="color:red;">'.$this->t('{provisionmanager:provisionmanager:help_nowritable}').'</span>';
}

if(isset($this->data['saved'])) {

    if($this->data['saved']) {
        $saved_msg = $this->t('{provisionmanager:provisionmanager:config_saved}');
        $colour = 'green';
    }
    else {
        $saved_msg = $this->t('{provisionmanager:provisionmanager:config_unsaved}');
        $colour = 'red';
    }

    echo "<script language=\"javascript\"> \n";
    echo "$(function() { \n";
    echo "var options = {id: 'message_from_top',
               position: 'top',
               size: 50,
               backgroundColor: '".$colour."',
               delay: 3500,
               speed: 500,
               fontSize: '30px'
              }; \n";
               
    echo "$.showMessage(\"".$saved_msg."\", options); \n";
    echo '})';
    echo '</script>';

}



echo '<br><a href="'.$this->data['backurl'].'">'.$this->t('{provisionmanager:provisionmanager:link_return}').'</a>';

$this->includeAtTemplateBase('includes/footer.php');
?>

<script language="javascript">
$(document).ready(function() {
   $('#attributeSourcesShown').click(function(){
     $('#attributeSourcesShown').hide();
     $('#attributeSourcesHidden').show();
     $('#attributeSources').show();
   });
   $('#attributeSourcesHidden').click(function(){
     $('#attributeSourcesShown').show();
     $('#attributeSourcesHidden').hide();
     $('#attributeSources').hide();
   });

   $('#configureForm').validate({
    invalidHandler: function(e, validator) {
		var errors;
        validator.valid();      
        errors = validator.numberOfInvalids();
		if (errors) {
			$("div.error_msg").show();
		} else {
			$("div.error_msg").hide();
		}
	},

    submitHandler: function(form) {
        $("div.error_msg").hide();
        form.submit();
    },
    onkeyup: false,
    errorPlacement: function(error, element) { }
   }); 
});

$("#cloneAction").live("click", function(e) {
    var count = parseInt($("#count").val() ,10);
    var new_fieldset = $("#cloneAppTemplate").clone();
        new_fieldset.show();
        new_fieldset.find("input[name=name]").attr({"name": "name:"+count, "class": "required"});
        new_fieldset.find("select[name=attributeSource]").attr({"name": "attributeSource:"+count, "class": "required"});
        new_fieldset.find("select[name=SPentityID]").attr({"name": "SPentityID:"+count, "class": "required"});
        new_fieldset.find("input[name=endpoint]").attr({"name": "endpoint:"+count, "class": "required"});
        new_fieldset.find("input[name=users]").attr({"name": "users:"+count, "class": "required"});

        new_fieldset.attr("id", "new_"+count);
        new_fieldset.insertAfter("#addButton");
        $("#count").val(count+1);
});

$("span.delete").live("click", function(e) {
    $(this).parents('.container').remove();
        
});
</script>

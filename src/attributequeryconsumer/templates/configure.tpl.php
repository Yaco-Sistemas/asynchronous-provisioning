<?php

$this->data['baseurlpath'] = $this->configuration->getBaseURL();
$this->data['urlhome'] = SimpleSAML_Module::getModuleURL('attributequeryconsumer/index.php');

$this->data['header'] = $this->t('{attributequeryconsumer:attributequeryconsumer:link_panel}');
$this->data['head'] = '<link rel="stylesheet" href="resources/attributequeryconsumer.css" type="text/css">';

$this->includeAtTemplateBase('includes/header.php');

if(isset($this->data['userMessage'])){ ?>
        <div class="umesg"><?php echo $this->t($this->data['userMessage']); ?></div>
<?php }?>

<h1> <?php echo $this->t('{attributequeryconsumer:attributequeryconsumer:link_panel}') ?> </h1>

<h2><?php echo $this->t('{core:frontpage:configuration}'); ?><img id="helpConfigurationFile" src="resources/images/help.png" title="<?php echo $this->t('{attributequeryconsumer:attributequeryconsumer:help_configuration_file}'); ?>"></h2>

<?php

$icon_enabled  = '<img src="/' . $this->data['baseurlpath'] . 'resources/icons/silk/accept.png" alt="Ok" title="Ok"/>';
$icon_disabled = '<img src="/' . $this->data['baseurlpath'] . 'resources/icons/silk/delete.png" alt="Error" title="Error" />';

echo '<div class="enablebox"><table>';

echo '<thead><tr class="enabled"><td class="helper"></td><td>'.$this->t('{attributequeryconsumer:attributequeryconsumer:parameter}').'</td>'.
     '<td width="80%">'.$this->t('{attributequeryconsumer:attributequeryconsumer:value}').'</td><td></td></tr></thead>';

echo '<tr class="enabled"><td>' . (!empty($this->data['config']['auth']) ? $icon_enabled : $icon_disabled) . '</td>
		<td>auth</td><td width="80%"> ' . $this->data['config']['auth'] . '</td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{attributequeryconsumer:attributequeryconsumer:help_auth}').'"></td></tr>';

echo '<tr class="enabled"><td>' . (!empty($this->data['config']['test_aqs_url']) ? $icon_enabled : $icon_disabled) . '</td>
		<td>test_aqs_url</td><td width="80%"> ' . $this->data['config']['test_aqs_url'] . '</td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{attributequeryconsumer:attributequeryconsumer:help_test_aqs_url}').'"></td></tr>';

echo('</tbody></table></div>');


echo '<h3>'.$this->t('{attributequeryconsumer:attributequeryconsumer:consumers}').':</h3>';

if (empty($this->data['config']['consumers'])) {
    echo '<p>'.$this->t('{attributequeryconsumer:attributequeryconsumer:not_consumers}').'</p>';
}
else {
    foreach ($this->data['config']['consumers'] as $key => $consumer) {
        echo '<b>'.$key.'</b>';        

        echo '<div class="enablebox consumer"><table>';

        echo '<tbody><tr class="enabled"><td>' . (!empty($consumer['sp_source']) && $consumer['valid_sp_source'] ? $icon_enabled : $icon_disabled) . '</td>
		    <td>sp_source</td><td width="80%"> ' . $consumer['sp_source'] . '</td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{attributequeryconsumer:attributequeryconsumer:help_sp_source}').'"></td></tr>';
        
        echo '<tr class="enabled"><td>' . (!empty($consumer['aqs_url']) ? $icon_enabled : $icon_disabled) . '</td>
		<td>aqs_url</td><td width="80%"> ' . $consumer['aqs_url'] . '</td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{attributequeryconsumer:attributequeryconsumer:help_aqs_url}').'"></td></tr>';


        echo '<tr class="enabled"><td>' . (!empty($consumer['admin_attr_id']) ? $icon_enabled : $icon_disabled) . '</td>
		<td>admin_attr_id</td><td width="80%"> ' . $consumer['admin_attr_id'] . '</td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{attributequeryconsumer:attributequeryconsumer:help_admin_attr_id}').'"></td></tr>';

        echo '<tr class="enabled"><td>' . (!empty($consumer['allowed_admins']) ? $icon_enabled : $icon_disabled) . '</td>
		<td>allowed_admins</td><td width="80%"> ' . join(', ', $consumer['allowed_admins']) . '</td><td class="helper"><img src="resources/images/help.png" title="'.$this->t('{attributequeryconsumer:attributequeryconsumer:help_allowed_admins}').'"></td></tr>';


        echo('</tbody></table></div>');
    }
}

echo '<br><a href="'.$this->data['urlhome'].'">'.$this->t('{attributequeryconsumer:attributequeryconsumer:link_return}').'</a>';

$this->includeAtTemplateBase('includes/footer.php');
?>

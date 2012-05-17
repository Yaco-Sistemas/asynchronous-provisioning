<?php

include_once('header.tpl.php');


echo '<div class="restricted">';

if ($authenticated && $userdata) {
	echo $translation['logged_at_privarea'][$idiom].'<br><br>';

	echo '<h3>'.$translation['config_params'][$idiom].'</h3>';
	echo $translation['provision_mode'][$idiom].': '.($on_the_fly? 'on': 'off').'<br>';
	echo $translation['authsource'][$idiom].': '.$auth_source.'<br>';
        
}
else {
	echo 'Área restringida. Acceso denegado';
}

echo '</div>';


include_once('footer.tpl.php');

?>

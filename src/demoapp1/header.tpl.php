<?php

include_once('translation.php');

echo '<html>';
echo '<head>';
echo '<link rel="stylesheet" type="text/css" href="resources/style.css" />';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
echo '</head>';
echo '<body>';
echo '<div class="title">';
echo '<h1>'.$translation['title'][$idiom].' (demoapp1)</h1>';

include_once('config.php');
include_once('lib.php');
include_once($simplesamlphp_path.'lib/_autoload.php');

$auth = new SimpleSAML_Auth_Simple($auth_source);

echo '<div class="login_menu">';

$authenticated = $auth->isAuthenticated();

if(!$authenticated) {
	echo $translation['not_logged'][$idiom];

}
else {
	$attributes = $auth->getAttributes();
	$user = retrieve_user_data($attributes);

	$exists = user_exists($user['id']);

	if($on_the_fly) {
		if($exists) {
			update_user($user);
		}
		else {
			create_user($user);
		}
	}

	$userdata = get_user($user['id']);

	if(!$userdata) {
        echo $translation['logged'][$idiom].' '.$user['name'].' '.$translation['user_not exists'][$idiom].'(<a href="logout.php">'.$translation['logout'][$idiom].'</a>)';
	}
	else {
 		echo $translation['logged'][$idiom].' '.$user['name'].' (<a href="logout.php">'.$translation['logout'][$idiom].'</a>)';
	}
	
}
echo '</div>';


echo '</div>';

$actual_page = "";
if (stripos($_SERVER['SCRIPT_NAME'], 'index.php') > 0) {
	$actual_page = 'index';
}
else if(stripos($_SERVER['SCRIPT_NAME'], 'restricted.php') > 0) {
        $actual_page = 'restricted';
}
else if(stripos($_SERVER['SCRIPT_NAME'], 'users.php') > 0) {
        $actual_page = 'users';
}

echo '<div class="menu">';
echo '<h3>Menu:</h3>';
echo '<ul>';
	echo '<li><a '.($actual_page=='index' ? 'class="active"' : '').' href="index.php">'.$translation['main'][$idiom].'</a></li>';
	echo '<li><a '.($actual_page=='restricted' ? 'class="active"' : '').' href="restricted.php">'.$translation['private'][$idiom].'</a></li>';
	echo '<li><a '.($actual_page=='users' ? 'class="active"' : '').'  href="users.php">'.$translation['userlist'][$idiom].'</a></li>';
echo '</ul>';
echo '</div>';

echo '<div class="main">';

?>

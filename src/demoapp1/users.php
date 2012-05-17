<?php

include_once('header.tpl.php');


echo '<h2>'.$translation['userlist'][$idiom].'</h2>';

$users = get_users();

if(!empty($users)) {
	echo '<table id="users" class="sortable">';
	echo '<thead>';
	echo '<tr><th>'.$translation['id'][$idiom].'</th><th>'.$translation['name'][$idiom].'</th><th>'.$translation['mail'][$idiom].'</th></tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach($users as $user) {
		if (!empty($user['id'])) {
			echo '<tr><td>'.$user['id'].'</td><td>'.$user['name'].'</td><td>'.$user['mail'].'</td></tr>';
		}
	}

	echo '</tbody>';
	echo '</table>';

	echo '<script type="text/javascript" src="resources/order.js"></script>';
	echo '
		<script type="text/javascript"> 
			var sorter = new TINY.table.sorter("sorter");
			sorter.head = "head";
			sorter.asc = "asc";
			sorter.desc = "desc";
			sorter.even = "evenrow";
			sorter.odd = "oddrow";
			sorter.evensel = "evenselected";
			sorter.oddsel = "oddselected";
			sorter.paginate = false;
			sorter.init("users",1);
		  </script>';

}
else {
	echo $translation['users_not_found'][$idiom];
}

include_once('footer.tpl.php');

?>

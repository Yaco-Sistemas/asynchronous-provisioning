<?php

include_once('config.php');


function user_exists($userid) {
	global $dbhost, $dbname, $dbuser, $dbpass;

	$exists = false;
	$sql = "SELECT * FROM users WHERE id ='".$userid."'";
	
	$conn = mysql_connect($dbhost, $dbuser, $dbpass);
	if (!$conn) {
		die('Invalid conn: ' . mysql_error());
	}
	$result = mysql_select_db($dbname, $conn);
    if (!$result) {
            die('Invalid database: ' . mysql_error());
    }

	$result = mysql_query($sql, $conn);
	if (!$result) {
	    die('Invalid query: ' . mysql_error());
	}
	if (mysql_num_rows($result) > 0) {
		$exists = true;
	}

	mysql_close($conn);	
	return $exists;
} 


function get_users() {
    global $dbhost, $dbname, $dbuser, $dbpass;

    $users = array();
    $sql = "SELECT * FROM users";

    $conn = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$conn) {
        die('Invalid conn: ' . mysql_error());
    }
    $result = mysql_select_db($dbname, $conn);
    if (!$result) {
        die('Invalid database: ' . mysql_error());
    }

    $result = mysql_query($sql, $conn);
    if (!$result) {
        die('Invalid query: ' . mysql_error());
    }
    if (mysql_num_rows($result) > 0) {
	    while($row = mysql_fetch_array($result)) {
		    $users[] = $row;
	    }
    }
    return $users;
}


function get_user($id) {
	global $dbhost, $dbname, $dbuser, $dbpass;

	$userdata = false;
	$sql = "SELECT * FROM users WHERE id ='".$id."'";

	$conn = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$conn) {
        die('Invalid conn: ' . mysql_error());
    }
    $result = mysql_select_db($dbname, $conn);
    if (!$result) {
        die('Invalid database: ' . mysql_error());
    }
	
	$result = mysql_query($sql, $conn);
    if (!$result) {
        die('Invalid query: ' . mysql_error());
    }
    if (mysql_num_rows($result) > 0) {
            $userdata = mysql_fetch_array($result);
    }
	return $userdata;
}

function create_user($userdata) {
    global $dbhost, $dbname, $dbuser, $dbpass;

	$sql = "INSERT INTO users (id, name, mail) VALUES ('".$userdata["id"]."','".$userdata["name"]."','".$userdata["mail"]."')";

    $conn = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$conn) {
        die('Invalid conn: ' . mysql_error());
    }
    $result = mysql_select_db($dbname, $conn);
    if (!$result) {
        die('Invalid database: ' . mysql_error());
    }

    $result = mysql_query($sql, $conn);
    if (!$result) {
        die('Invalid query: ' . mysql_error());
    }
	return true;
}

function update_user($userdata) {
    global $dbhost, $dbname, $dbuser, $dbpass;

    $sql = "UPDATE users SET id= '".$userdata["id"]."', name='".$userdata["name"]."', mail='".$userdata["mail"]."' WHERE id='".$userdata["id"]."'";

    $conn = mysql_connect($dbhost, $dbuser, $dbpass);
    if (!$conn) {
        die('Invalid conn: ' . mysql_error());
    }
    $result = mysql_select_db($dbname, $conn);
    if (!$result) {
        die('Invalid database: ' . mysql_error());
    }

    $result = mysql_query($sql, $conn);
    if (!$result) {
        die('Invalid query: ' . mysql_error());
    }
    return true;
}

function delete_user($userId) {
    global $dbhost, $dbname, $dbuser, $dbpass;

	$sql = "DELETE FROM users WHERE id = '".$userId."'";
    
    $conn = mysql_connect($dbhost, $dbuser, $dbpass);

    if (!$conn) {
        die('Invalid conn: ' . mysql_error());
    }

    $result = mysql_select_db($dbname, $conn);
    if (!$result) {
        die('Invalid database: ' . mysql_error());
    }

    $result = mysql_query($sql, $conn);
    if (!$result) {
        die('Invalid query: '.$sql .'--> ' . mysql_error());
    }
	return true;        
}


function retrieve_user_data($attrs) {
	$user = array();
    if(is_array($attrs) && !empty($attrs)) {
	    if (array_key_exists('uid', $attrs) && !empty($attrs['uid'][0])) {
		    $user['id'] = $attrs['uid'][0]; 
	    }
	    else {
		    if (array_key_exists('eduPersonPrincipalName', $attrs) && !empty($attrs['eduPersonPrincipalName'][0])) {
			    $user['id'] = $attrs['eduPersonPrincipalName'][0];
		    }
	    }

	    if (array_key_exists('mail', $attrs) && !empty($attrs['mail'][0])) {
		    $user['mail'] = $attrs['mail'][0];
	    }
	    else {
		    if (array_key_exists('irisMailMainAddress', $attrs) && !empty($attrs['irisMailMainAddress'][0])) {
			    $user['mail'] = $attrs['irisMailMainAddress'][0];
		    }
	    }
	
	    if (array_key_exists('cn', $attrs) && !empty($attrs['cn'][0])) {
                    $user['name'] = $attrs['cn'][0];
            }
	    else {
		    if (array_key_exists('displayName', $attrs) && !empty($attrs['displayName'][0])) {
                    	$user['name'] = $attrs['displayName'][0];
            	}
	    }
    }
	return $user;
}

// type:  'ok' or 'error'
function write_msg($type, $msg) {
    echo '<img src="resources/'.$type.'.png"> '.$msg;
}



?>

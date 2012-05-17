<?php

$session = SimpleSAML_Session::getInstance();
$config = SimpleSAML_Configuration::getInstance();

$provManagerConfig = sspmod_provisionmanager_WritableConfiguration::getConfig('module_provisionmanager.php');

$apps = $provManagerConfig->getArray('apps', array());

if(isset($_POST['userID']) && !empty($_POST['userID']) && isset($_POST['AppID']) && !empty($_POST['AppID'])) {

    if(!isset($apps[$_POST['AppID']])) {
        echo 'The AppID does not exist';
        exit();
    }
    $appID = $_POST['AppID'];

    if(!isset($apps[$appID]['endpoint'])) {
        echo 'The endpoint for the AppID '.$_POST['AppID'].' does not exist. Check the config file';
        exit();
    }

    $endpoint = $apps[$appID]['endpoint'];

    SimpleSAML_Utilities::postRedirect($endpoint, array (
        'userID' => $_POST['userID']
    ));


}
?>

<html>
<head>
</head>
<body>

<form method="POST" action="">
<label for="userID">UserID: </label><input name="userID" id="userID" value=""><br>
<label for="AppID">AppID: </label><select name="AppID" id="AppID">
<?php
foreach($apps as $key=>$value) {
    echo '<option name="'.$key.'">'.$key.'</option>';
}
?>
</select><br>
<input type="submit" name="submit">
</form>
</body>
</html>

<?php
?>

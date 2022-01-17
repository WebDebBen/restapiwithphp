<?php
    include_once("./config.php");
    include_once("./connection.php");

    $db = new dbObj();
	$connection =  $db->getConnstring();
?>
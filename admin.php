<?php 
require_once("php/functions.php");
$user = check_user(true);
error_log(print_r($user,true));






require_once("templates/header.php"); ?>







<?php require_once("templates/footer.php"); ?>
<?php
chdir ($_SERVER['DOCUMENT_ROOT']);
require_once("php/functions.php");
$user = check_user();
if ($user == false) {
    print("<script>location.href='/login.php'</script>");
}
require_once("templates/header.php"); 
?>
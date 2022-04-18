<?php 
require_once("php/functions.php");
$user = check_user();
if ($user['admin'] !== "1") {
    print("<script>location.href='login.php'</script>");
}
// error_log(print_r($user,true));






require_once("templates/header.php"); ?>







<?php require_once("templates/footer.php"); ?>
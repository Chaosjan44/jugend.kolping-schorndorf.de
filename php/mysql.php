<?php
require_once "config.php";

try {
  $pdo = new PDO('mysql:host=' . $ini_array["mysql"]["host"] . ';dbname=' . $ini_array["mysql"]["database"] . ';charset=utf8', $ini_array["mysql"]["user"], $ini_array["mysql"]["passwd"]);
  //Connected successfully
} catch(PDOException $e) {
  // If there is an error with the connection, stop the script and display the error.  
  sqlError('Connection failed: ' . $e->getMessage());
  #error_log($e->getMessage());
}
?>

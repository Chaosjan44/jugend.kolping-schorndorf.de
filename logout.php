<?php 
session_start();
session_destroy();
unset($_SESSION['userid']);
// bindet die PHP Funktionen ein
require_once("php/functions.php");

// Löscht den Security Token aus der Datenbank
$stmt = $pdo->prepare("DELETE FROM securitytokens WHERE identifier = ?");
$stmt->bindValue(1, $_COOKIE['identifier']);
$result = $stmt->execute();
// Fehler Seite anzeigen (wenn ein Fehler aufgetreten ist)
if (!$result) {
    error('Datenbank Fehler', pdo_debugStrParams($stmt));
}

// Entfernt Cookies
setcookie("identifier","",time()-(3600*24*365)); 
setcookie("securitytoken","",time()-(3600*24*365)); 

header("location: index.php");
exit();
?>

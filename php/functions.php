<?php
require_once("php/mysql.php");

function check_user($redirect = FALSE) {
	global $pdo;
	if(!isset($_SESSION['userid']) && isset($_COOKIE['identifier']) && isset($_COOKIE['securitytoken'])) {
		$identifier = $_COOKIE['identifier'];
		$securitytoken = $_COOKIE['securitytoken'];
		$stmt = $pdo->prepare("SELECT * FROM securitytokens WHERE identifier = ?");
		$stmt->bindValue(1, $identifier);
		$result = $stmt->execute();
		if (!$result) {
			exit;
		}
		$securitytoken_row = $stmt->fetch();
		if(sha1($securitytoken) !== $securitytoken_row['securitytoken']) {
			exit;
		} else { //Token war korrekt
			//Setze neuen Token
			$neuer_securitytoken = md5(uniqid());
			$stmt = $pdo->prepare("UPDATE securitytokens SET securitytoken = ? WHERE identifier = ?");
			$stmt->bindValue(1, sha1($neuer_securitytoken));
			$stmt->bindValue(2, $identifier);
			$result = $stmt->execute();
			if (!$result) {
				exit;
			}
			setcookie("identifier",$identifier,time()+(3600*24*90)); //90 Tage Gültigkeit
			setcookie("securitytoken",$neuer_securitytoken,time()+(3600*24*90)); //90 Tage Gültigkeit
			//Logge den Benutzer ein
			$_SESSION['userid'] = $securitytoken_row['user_id'];
		}
		if(!isset($_SESSION['userid'])) {
			if($redirect) {
				header("location: login.php");
				exit();
			} else {
				return FALSE;
			}
		} else {
			$stmt = $pdo->prepare("SELECT * FROM users WHERE users.id = ?");
			$stmt->bindValue(1, $_SESSION['userid'], PDO::PARAM_INT);
			$result = $stmt->execute();
			if (!$result) {
				error_log("Error while pulling user with id: " + $_SESSION['userid'] + " from Database");
			}
			$user = $stmt->fetch();
			return $user;
		}
	}
}





function error($error_msg) {
	global $pdo;
	include_once("templates/header.php");
	include_once("templates/footer.php");
	exit();
}

function pdo_debugStrParams($stmt) {
	ob_start();
	$stmt->debugDumpParams();
	$r = ob_get_contents();
	ob_end_clean();
	return $r;
}

function isMobile () {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function check_style() {
	if(isset($_COOKIE['style'])) {
		if ($_COOKIE['style'] == 'dark') {
			return 'dark';
		} else if ($_COOKIE['style'] == 'light') {
			return 'light';
		}
	} else {
		return 'light';
	}
}

function check_cookie() {
	if(isset($_COOKIE['acceptCookies'])) {
		return true;
	} else {
		return false;
	}
}
?>
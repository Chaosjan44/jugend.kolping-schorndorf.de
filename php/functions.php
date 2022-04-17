<?php
require_once("php/mysql.php");







function error($error_msg) {
	global $pdo;
	include_once("templates/header.php");
	include_once("templates/error.php");
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
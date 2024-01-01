<?php 
chdir ($_SERVER['DOCUMENT_ROOT']);
require_once("php/functions.php");
$user = check_user();
if (!isset($user)) {
    print("<script>location.href='/login.php'</script>");
    exit;
}
if ($user['perm_admin'] != 1) {
    error('Unzureichende Berechtigungen!');
}

$error_msg = "";
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'register') {
        if(isset($_POST['user']) && !empty($_POST['user']) && isset($_POST['passwort']) && !empty($_POST['passwort']) && isset($_POST['passwort2']) && !empty($_POST['passwort2']) && isset($_POST['vorname']) && !empty($_POST['vorname']) && isset($_POST['nachname']) && !empty($_POST['nachname'])) {
            if ($_POST['passwort'] == $_POST['passwort2']) {
                $stmt = $pdo->prepare('SELECT login FROM users where login = ?');
                $stmt->bindValue(1, $_POST['user']);
                $stmt->execute();
                if ($stmt->rowCount() != 0) {
                    $error_msg = "<span class='text-danger'>Ein User mit dem Anmeldename existiert bereits!<br><br></span>";
                } else {
                    $username = $_POST['user'];
                    $passwort = password_hash($_POST['passwort'], PASSWORD_DEFAULT);
                    $vorname = trim($_POST['vorname']);
                    $nachname = trim($_POST['nachname']);

                    $stmt = $pdo->prepare("INSERT INTO users SET login = ?, password = ?, nachname = ?, vorname = ?, perm_login = ?, perm_admin = ?, perm_event = ?, perm_blog = ?");
                    $stmt->bindValue(1, $username);
                    $stmt->bindValue(2, $passwort);
                    $stmt->bindValue(3, $nachname);
                    $stmt->bindValue(4, $vorname);
                    $stmt->bindValue(5, (isset($_POST['perm_login']) ? "1" : "0"), PDO::PARAM_INT);
                    $stmt->bindValue(6, (isset($_POST['perm_admin']) ? "1" : "0"), PDO::PARAM_INT);
                    $stmt->bindValue(7, (isset($_POST['perm_event']) ? "1" : "0"), PDO::PARAM_INT);
                    $stmt->bindValue(8, (isset($_POST['perm_blog']) ? "1" : "0"), PDO::PARAM_INT);
                    $result = $stmt->execute();
                    if (!$result) {
                        error_log("Error while registering user");
                        exit;
                    }
                    $error_msg = "<span class='text-success'>Der User wurde erfolgreich angelegt. :)<br><br></span>";
                }
            } else {
                $error_msg = "<span class='text-danger'>Die angegebenen Passwörter stimmen nicht überein.<br><br></span>";
            }
        } else {
            $error_msg = "<span class='text-danger'>Es müssen alle Felder ausgefüllt werden!<br><br></span>";
        }
    }
}
ob_start();
require_once("templates/header.php"); 
$buffer=ob_get_contents();
ob_end_clean();

$title = "ADMIN - Kolpingjugend Schorndorf";
$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
echo $buffer;
?>
<div class="container py-3" style="min-height: 80vh;">
	<div class="row justify-content-center">
		<div class="col">
			<div class="card cbg3 shadow1">
                <div class="card-body">
                    <h3 class="card-title display-3 text-center mb-4 text-kolping-orange">Registrieren</h3>
                    <div class="card-text">
                        <?=$error_msg?>
                        <form action="register.php" method="post">
                            <div class="form-floating mb-3 cbg">
                                <input id="inputUser" type="text" name="user" placeholder="User" autofocus class="form-control ps-4 fw-bold" required>
                                <label for="inputUser" class="fw-bold">Nutzername</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input id="inputPassword" type="password" name="passwort" placeholder="Passwort" class="form-control ps-4 fw-bold" required>
                                <label for="inputPassword" class="fw-bold">Passwort</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input id="inputPassword2" type="password" name="passwort2" placeholder="Passwort wiederholen" class="form-control ps-4 fw-bold" required>
                                <label for="inputPassword2" class="fw-bold">Passwort wiederholen</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input id="inputVorname" type="text" name="vorname" placeholder="Vorname" class="form-control ps-4 fw-bold" required>
                                <label for="inputVorname" class="fw-bold">Vorname</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input id="inputNachname" type="text" name="nachname" placeholder="Nachname" class="form-control ps-4 fw-bold" required>
                                <label for="inputNachname" class="fw-bold">Nachname</label>
                            </div>
                            <div class="col mb-3">
                                <div class="input-group justify-content-center">
                                    <label for="perm_login" class="input-group-text">Anmelde Berechtigungen?</label>
                                    <div class="input-group-text">
                                        <input value="perm_login" id="perm_login" type="checkbox" name="perm_login" value="0" class="form-check-input checkbox-kolping m-0">
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-3">
                                <div class="input-group justify-content-center">
                                    <label for="perm_admin" class="input-group-text">Admin Berechtigungen?</label>
                                    <div class="input-group-text">
                                        <input value="perm_admin" id="perm_admin" type="checkbox" name="perm_admin" value="0" class="form-check-input checkbox-kolping m-0">
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-3">
                                <div class="input-group justify-content-center">
                                    <label for="perm_event" class="input-group-text">Termine Berechtigungen?</label>
                                    <div class="input-group-text">
                                        <input value="perm_event" id="perm_event" type="checkbox" name="perm_event" value="0" class="form-check-input checkbox-kolping m-0">
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-3">
                                <div class="input-group justify-content-center">
                                    <label for="perm_blog" class="input-group-text">Nachrichten Berechtigungen?</label>
                                    <div class="input-group-text">
                                        <input value="perm_blog" id="perm_blog" type="checkbox" name="perm_blog" value="0" class="form-check-input checkbox-kolping m-0">
                                    </div>
                                </div>
                            </div>
                            <div class="col text-center">
                                <button type="submit" name="action" value="register" class="btn btn-kolping btn-floating">Registrieren</button>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>
<?php
include_once("templates/footer.php");
?>
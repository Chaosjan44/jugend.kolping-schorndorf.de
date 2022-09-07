<?php 
require_once("php/functions.php");
$user = check_user();
if (!isset($user)) {
    print("<script>location.href='/login.php'</script>");
    exit;
}

$error_msg = "";
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'register') {
        if(isset($_POST['user']) && !empty($_POST['user']) && isset($_POST['passwort']) && !empty($_POST['passwort']) && isset($_POST['passwort2']) && !empty($_POST['passwort2']) && isset($_POST['vorname']) && !empty($_POST['vorname']) && isset($_POST['nachname']) && !empty($_POST['nachname'])) {
            if ($_POST['passwort'] == $_POST['passwort2']) {

                $username = $_POST['user'];
                $passwort = password_hash($_POST['passwort'], PASSWORD_DEFAULT);
                $vorname = trim($_POST['vorname']);
                $nachname = trim($_POST['nachname']);

                $stmt = $pdo->prepare("INSERT INTO users SET login = ?, password = ?, nachname = ?, vorname = ?, loginperms = ?, admin = ?");
                $stmt->bindValue(1, $username);
                $stmt->bindValue(2, $passwort);
                $stmt->bindValue(3, $nachname);
                $stmt->bindValue(4, $vorname);
                $stmt->bindValue(5, (isset($_POST['loginrechte']) ? "1" : "0"), PDO::PARAM_INT);
                $stmt->bindValue(6, (isset($_POST['adminrechte']) ? "1" : "0"), PDO::PARAM_INT);
                $result = $stmt->execute();
                if (!$result) {
                    error_log("Error while registering user");
                    exit;
                }
                $error_msg = "<span class='text-success'>Der User wurde erfolgreich angelegt. :)<br><br></span>";
            } else {
                $error_msg = "<span class='text-danger'>Die angegebenen Passwörter stimmen nicht überein.<br><br></span>";
            }
        } else {
            $error_msg = "<span class='text-danger'>Es müssen alle Felder ausgefüllt werden!<br><br></span>";
        }
    }
}
include_once("templates/header.php");
?>
<div class="container py-3" style="min-height: 80vh;">
	<div class="row justify-content-center">
		<div class="col">
			<div class="card cbg2">
                <div class="card-body">
                    <h3 class="card-title display-3 text-center mb-4 text-kolping-orange">Registrieren</h3>
                    <div class="card-text">
                        <?=$error_msg?>
                        <form action="register.php" method="post">
                            <div class="form-floating mb-3">
                                <input id="inputUser" type="text" name="user" placeholder="User" autofocus class="form-control border-0 ps-4 text-dark fw-bold" required>
                                <label for="inputUser" class="text-dark fw-bold">Username</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input id="inputPassword" type="password" name="passwort" placeholder="Passwort" class="form-control border-0 ps-4 text-dark fw-bold" required>
                                <label for="inputPassword" class="text-dark fw-bold">Passwort</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input id="inputPassword2" type="password" name="passwort2" placeholder="Passwort wiederholen" class="form-control border-0 ps-4 text-dark fw-bold" required>
                                <label for="inputPassword2" class="text-dark fw-bold">Passwort wiederholen</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input id="inputVorname" type="text" name="vorname" placeholder="Vorname" class="form-control border-0 ps-4 text-dark fw-bold" required>
                                <label for="inputVorname" class="text-dark fw-bold">Vorname</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input id="inputNachname" type="text" name="nachname" placeholder="Nachname" class="form-control border-0 ps-4 text-dark fw-bold" required>
                                <label for="inputNachname" class="text-dark fw-bold">Nachname</label>
                            </div>
                            <div class="col mb-3">
                                <div class="input-group justify-content-center">
                                    <label for="loginrechte" class="input-group-text">Login Berechtigungen?</label>
                                    <div class="input-group-text">
                                        <input value="remember-me" id="loginrechte" type="checkbox" name="loginrechte" value="0" class="form-check-input checkbox-kolping" checked>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-3">
                                <div class="input-group justify-content-center">
                                    <label for="adminrechte" class="input-group-text">Admin Berechtigungen?</label>
                                    <div class="input-group-text">
                                        <input value="remember-me" id="adminrechte" type="checkbox" name="adminrechte" value="0" class="form-check-input checkbox-kolping" checked>
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
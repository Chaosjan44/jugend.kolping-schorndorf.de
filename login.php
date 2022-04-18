<?php 
require_once("php/functions.php");

$error_msg = "";
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'login') {
        if(isset($_POST['user']) && isset($_POST['passwort'])) {
            $username = $_POST['user'];
            $passwort = $_POST['passwort'];

            $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
            $stmt->bindValue(1, $username);
            $result = $stmt->execute();
            if (!$result) {
                error_log("Error #1 while user login");
                exit;
            }
            $user = $stmt->fetch();
            if ($user['loginperms'] == "1") {
                //Überprüfung des Passworts
                if ($user !== false && password_verify($passwort, $user['password'])) {
                    $_SESSION['userid'] = $user['user_id'];
                    //Möchte der Nutzer angemeldet beleiben?
                    if (check_cookie()) {
                        if(isset($_POST['angemeldet_bleiben'])) {
                            $identifier = md5(uniqid());
                            $securitytoken = md5(uniqid());
                            
                            $stmt = $pdo->prepare("INSERT INTO securitytokens (user_id, identifier, securitytoken) VALUES (?, ?, ?)");
                            $stmt->bindValue(1, $user['user_id'], PDO::PARAM_INT);
                            $stmt->bindValue(2, $identifier);
                            $stmt->bindValue(3, sha1($securitytoken));
                            $result = $stmt->execute();
                            if (!$result) {
                                error_log("Error #2 while user login");
                                exit;
                            }
                            setcookie("identifier",$identifier,time()+(3600*24*365)); //Valid for 1 year
                            setcookie("securitytoken",$securitytoken,time()+(3600*24*365)); //Valid for 1 year
                        }
                        $error_msg = "<span class='text-success'>Login Erfolgreich!<br><br></span>";
                        echo("<script>location.href='admin.php'</script>");
                        exit;
                    } else {
                        $error_msg = "<span class='text-danger'>für den Login müssen Cookies aktiv sein!<br><br></span>";
                        exit;
                    }
                } else {
                    $error_msg = "<span class='text-danger'>User oder Passwort war ungültig!<br><br></span>";
                }
            } else {
                $error_msg = "<span class='text-danger'>Dieser User darf dich nicht einloggen!<br><br></span>";
            }
        }
    }
}
require_once("templates/header.php"); ?>

<div class="container py-3" style="min-height: 80vh;">
	<div class="row justify-content-center">
		<div class="col">
			<div class="card cbg2">
                <div class="card-body">
                    <h3 class="card-title display-3 text-center mb-4 text-kolping-orange">Anmelden</h3>
                    <div class="card-text">
                        <?=$error_msg?>
                        <form action="login.php" method="post">
                            <div class="form-floating mb-3">
                                <input id="inputUser" type="text" name="user" placeholder="User" autofocus class="form-control border-0 ps-4 text-dark fw-bold" required>
                                <label for="inputUser" class="text-dark fw-bold">Username</label>
                            </div>
                            <div class="form-floating mb-3">
                                <input id="inputPassword" type="password" name="passwort" placeholder="Passwort" class="form-control border-0 ps-4 text-dark fw-bold" required>
                                <label for="inputPassword" class="text-dark fw-bold">Passwort</label>
                            </div>
                            <div class="<?php if (!isMobile()) {print('row row-cols-2 justify-content-between');} ?>">
                                <div class="col">
                                    <div class="input-group <?php if (isMobile()) {print('mb-3 justify-content-center');} ?>">
                                        <label for="customCheck1" class="input-group-text">Angemeldet bleiben</label>
                                        <div class="input-group-text">
                                            <input value="remember-me" id="customCheck1" type="checkbox" name="angemeldet_bleiben" value="1" class="form-check-input checkbox-kolping" checked>
                                        </div>
                                    </div>
                                </div>
                                <div class="col <?php if (!isMobile()) {print('text-end');} else {print('text-center');} ?>">
                                    <button type="submit" name="action" value="login" class="btn btn-kolping btn-floating">Anmelden</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
		</div>
	</div>
</div>




<?php require_once("templates/footer.php"); ?>
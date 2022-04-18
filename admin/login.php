<?php 
chdir ($_SERVER['DOCUMENT_ROOT']);
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
                print("error while getting user"); // To be removed
            }
            $user = $stmt->fetch();
            //Überprüfung des Passworts
            if ($user !== false && password_verify($passwort, $user['password'])) {
                $_SESSION['userid'] = $user['user_id'];
                //Möchte der Nutzer angemeldet beleiben?
                if(isset($_POST['angemeldet_bleiben'])) {
                    $identifier = md5(uniqid());
                    $securitytoken = md5(uniqid());
                    
                    $stmt = $pdo->prepare("INSERT INTO securitytokens (user_id, identifier, securitytoken) VALUES (?, ?, ?)");
                    $stmt->bindValue(1, $user['user_id'], PDO::PARAM_INT);
                    $stmt->bindValue(2, $identifier);
                    $stmt->bindValue(3, sha1($securitytoken));
                    $result = $stmt->execute();
                    if (!$result) {
                        print("error while creating cookies"); // To be removed
                    }
                    setcookie("identifier",$identifier,time()+(3600*24*365)); //Valid for 1 year
                    setcookie("securitytoken",$securitytoken,time()+(3600*24*365)); //Valid for 1 year
                }

                echo("<script>location.href='/admin.php'</script>");
                exit;
            } else {
                $error_msg =  "User oder Passwort war ungültig<br><br>";
            }
        }
    }
}

$username = "";
if(isset($_POST['user'])) {
	$username = htmlentities($_POST['user']); 
}

require_once("templates/header.php"); ?>





<div class="container-fluid">
	<div class="row no-gutter">
		<div class="ctext">
			<div class="d-flex align-items-center py-5" style="min-height: 80vh;">
				<div class="container">
					<div class="row">
						<div class="col-lg-10 col-xl-7 mx-auto cbg rounded">
							<h3 class="display-4 text-kolping-orange">Anmelden</h3>
                            <?php 
							if(isset($error_msg) && !empty($error_msg)) {
								echo $error_msg;
							}
							?>
							<form action="login.php" method="post">
								<div class="form-floating mb-3">
									<input id="inputUser" type="text" name="user" placeholder="User" value="<?php echo $username; ?>" autofocus class="form-control border-0 ps-4 text-dark fw-bold" required>
									<label for="inputUser" class="text-dark fw-bold">Username</label>
								</div>
								<div class="form-floating mb-3">
                                    <input id="inputPassword" type="password" name="passwort" placeholder="Passwort" class="form-control border-0 ps-4 text-dark fw-bold" required>
									<label for="inputPassword" class="text-dark fw-bold">Passwort</label>
								</div>

								<div class="custom-control custom-checkbox mb-3">
									<input value="remember-me" id="customCheck1" type="checkbox" name="angemeldet_bleiben" value="1" checked class="custom-control-input">
									<label for="customCheck1" class="custom-control-label">Angemeldet bleiben</label>
								</div>
								
								<button type="submit" name="action" value="login" class="btn btn-primary btn-block text-uppercase mb-2 shadow-sm">Anmelden</button>
							</form>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>




<?php require_once("templates/footer.php"); ?>
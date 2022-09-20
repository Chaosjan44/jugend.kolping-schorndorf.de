<?php
chdir ($_SERVER['DOCUMENT_ROOT']);
require_once("php/functions.php");
$user = check_user();
if ($user == false) {
    print("<script>location.href='/login.php'</script>");
}
if ($user['perm_admin'] != 1) {
    error('Unzureichende Berechtigungen!');
}
$stmt = $pdo->prepare('SELECT * FROM users ORDER BY user_id');
$result = $stmt->execute();
if (!$result) {
    error('Datenbank Fehler!', pdo_debugStrParams($stmt));
}
$total_users = $stmt->rowCount();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
if(isset($_POST['action'])) {
    if ($_POST['action'] == 'deleteconfirm') {
        if ($user['perm_admin'] != 1) {
            error('Unzureichende Berechtigungen!');
        }
        if(isset($_POST['user_id']) and !empty($_POST['user_id'])) {
            if ($_POST['user_id'] != 0) {
                $stmt = $pdo->prepare('DELETE FROM securitytokens WHERE user_id = ?');
                $stmt->bindValue(1, $_POST['user_id'], PDO::PARAM_INT);
                $result = $stmt->execute();
                if (!$result) {
                    error('Datenbank Fehler!', pdo_debugStrParams($stmt));
                }

                $stmt = $pdo->prepare('UPDATE events SET created_by = 0 WHERE created_by = ?');
                $stmt->bindValue(1, $_POST['user_id'], PDO::PARAM_INT);
                $result = $stmt->execute();
                if (!$result) {
                    error('Datenbank Fehler!', pdo_debugStrParams($stmt));
                }

                $stmt = $pdo->prepare('UPDATE blog_entrys SET created_by = 0 WHERE created_by = ?');
                $stmt->bindValue(1, $_POST['user_id'], PDO::PARAM_INT);
                $result = $stmt->execute();
                if (!$result) {
                    error('Datenbank Fehler!', pdo_debugStrParams($stmt));
                }


                $stmt = $pdo->prepare('DELETE FROM users WHERE user_id = ?');
                $stmt->bindValue(1, $_POST['user_id'], PDO::PARAM_INT);
                $result = $stmt->execute();
                if (!$result) {
                    error('Datenbank Fehler!', pdo_debugStrParams($stmt));
                }
                echo("<script>location.href='user.php'</script>");
                exit;
            }
        }
    }
    // Wenn action "mod" ist
    if($_POST['action'] == 'mod') {
        // Zeit die Error Seite wenn der User keine Berechtigungen hat
        if ($user['perm_admin'] != 1 && $_POST['user_id'] != 0) {
            error('Unzureichende Berechtigungen!');
        }
        // Ziehe alle Daten zu gegebenen User aus der Datenbank
        $stmt = $pdo->prepare('SELECT * FROM users where user_id = ?');
        $stmt->bindValue(1, $_POST['user_id'], PDO::PARAM_INT);
        $result = $stmt->execute();
        if (!$result) {
            error('Datenbank Fehler !', pdo_debugStrParams($stmt));
        }
        $user1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(isset($_POST['vorname']) and isset($_POST['nachname']) and isset($_POST['passwortNeu']) and isset($_POST['passwortNeu2']) and !empty($_POST['vorname']) and !empty($_POST['nachname'])) {
            $stmt = $pdo->prepare("UPDATE users SET vorname = ?, nachname = ?, perm_login = ?, perm_admin = ?, perm_event = ?, perm_blog = ? WHERE user_id = ?");
            $stmt->bindValue(1, $_POST['vorname']);
            $stmt->bindValue(2, $_POST['nachname']);
            $stmt->bindValue(3, (isset($_POST['perm_login']) ? "1" : "0"), PDO::PARAM_INT);
            $stmt->bindValue(4, (isset($_POST['perm_admin']) ? "1" : "0"), PDO::PARAM_INT);
            $stmt->bindValue(5, (isset($_POST['perm_event']) ? "1" : "0"), PDO::PARAM_INT);
            $stmt->bindValue(6, (isset($_POST['perm_blog']) ? "1" : "0"), PDO::PARAM_INT);
            $stmt->bindValue(7, $_POST['user_id'], PDO::PARAM_INT);
            $result = $stmt->execute();
            if (!$result) {
                error('Datenbank Fehler!', pdo_debugStrParams($stmt));
            }
            // Überprüfe ob die eingegebenen Passwörter übereinstimmen
            if($_POST['passwortNeu'] == $_POST['passwortNeu2']) {
                // überprüft das die Passwörter nicht leer sind
                if (!empty($_POST['passwortNeu']) and !empty($_POST['passwortNeu2'])) {
                    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                    $stmt->bindValue(1, password_hash($_POST['passwortNeu'], PASSWORD_DEFAULT));
                    $stmt->bindValue(2, $_POST['user_id'], PDO::PARAM_INT);
                    $result = $stmt->execute();
                    if (!$result) {
                        error('Datenbank Fehler!', pdo_debugStrParams($stmt));
                    }                    
                }
            } else {
                error('Passwörter stimmen nicht überein!');
            }
            echo("<script>location.href='user.php'</script>");
            exit;
        } else {
        require_once("templates/header.php");
        ?>
        <!-- Formular zur Bearbeitung des Users anzeigen -->
        <div class="px-3 py-3" style="min-height: 80vh;">
            <h1>Einstellungen</h1>
            <div>
                <form action="user.php" method="post">
                    <div class="row d-flex justify-content-between">
                        <div class="col-6">
                            <div class="input-group py-2">
                                <span class="input-group-text" for="inputVorname" style="min-width: 150px;">Vorname</span>
                                <input class="form-control" id="inputVorname" name="vorname" type="text" value="<?=$user1[0]['vorname']?>" required>
                            </div>
                            <div class="input-group py-2">
                                <span class="input-group-text" for="inputNachname" style="min-width: 150px;">Nachname</span>
                                <input class="form-control" id="inputNachname" name="nachname" type="text" value="<?=$user1[0]['nachname']?>" required>
                            </div>
                            <div class="col mb-3">
                                <div class="input-group justify-content-center">
                                    <label for="perm_login" class="input-group-text">Login Berechtigungen?</label>
                                    <div class="input-group-text">
                                        <input value="remember-me" id="perm_login" type="checkbox" name="perm_login" value="1" class="form-check-input checkbox-kolping" <?php if ($user1[0]['perm_login'] == 1) print("checked");?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-3">
                                <div class="input-group justify-content-center">
                                    <label for="perm_admin" class="input-group-text">Admin Berechtigungen?</label>
                                    <div class="input-group-text">
                                        <input value="remember-me" id="perm_admin" type="checkbox" name="perm_admin" value="1" class="form-check-input checkbox-kolping" <?php if ($user1[0]['perm_admin'] == 1) print("checked");?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-3">
                                <div class="input-group justify-content-center">
                                    <label for="perm_event" class="input-group-text">Termine Berechtigungen?</label>
                                    <div class="input-group-text">
                                        <input value="remember-me" id="perm_event" type="checkbox" name="perm_event" value="1" class="form-check-input checkbox-kolping" <?php if ($user1[0]['perm_event'] == 1) print("checked");?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col mb-3">
                                <div class="input-group justify-content-center">
                                    <label for="perm_blog" class="input-group-text">Nachrichten Berechtigungen?</label>
                                    <div class="input-group-text">
                                        <input value="remember-me" id="perm_blog" type="checkbox" name="perm_blog" value="1" class="form-check-input checkbox-kolping" <?php if ($user1[0]['perm_blog'] == 1) print("checked");?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="input-group py-2">
                                <span class="input-group-text" for="inputPasswortNeu" style="min-width: 300px;">Neues Passwort</span>
                                <input class="form-control" id="inputPasswortNeu" name="passwortNeu" type="password">
                            </div>
                            <div class="input-group py-2">
                                <span class="input-group-text" for="inputPasswortNeu2" style="min-width: 300px;">Neues Passwort (wiederholen)</span>
                                <input class="form-control" id="inputPasswortNeu2" name="passwortNeu2" type="password">
                            </div>
                        </div>
                    </div>
                    <input type="number" value="<?=$_POST['user_id']?>" name="user_id" style="display: none;" required>
                    <button type="submit" name="action" value="mod" class="me-2 btn btn-success">Speichern</button>
                    <button type="submit" name="action" value="cancel" class="ms-2 btn btn-danger">Abrechen</button>
                </form>
            </div>
        </div>
        <?php 
        include_once("templates/footer.php");
        exit;
        } 
    }
    if ($_POST['action'] == 'cancel') {
        echo("<script>location.href='user.php'</script>");
        exit;
    }
}
require_once("templates/header.php"); 
?>
<div class="container users content-wrapper py-3 px-3" style="min-height: 80vh;">
    <div class="row">
        <div class="py-3 px-3 cbg ctext rounded">
            <div class="d-flex justify-content-between">
                <div class="col-4">
                    <h1>Benutzerverwaltung</h1>
                </div>
                <div class="col-4 d-flex justify-content-end">
                    <div>
                        <button class="btn btn-kolping" onclick="window.location.href = '/register.php';">User registrieren</button>
                    </div>
                </div>
            </div>
            <p><?php print($total_users); ?> Benutzer</p>
            <div class="table-responsive">
                <table class="table align-middle table-borderless table-hover">
                    <thead>
                        <tr>
                            <div class="cbg ctext rounded">
                                <th scope="col" class="border-0 text-center">
                                    <div class="p-2 px-3 text-uppercase ctext">User ID</div>
                                </th>
                                <th scope="col" class="border-0 text-center">
                                    <div class="p-2 px-3 text-uppercase ctext">Vorname</div>
                                </th>
                                <th scope="col" class="border-0 text-center">
                                    <div class="p-2 px-3 text-uppercase ctext">Nachname</div>
                                </th>
                                <th scope="col" class="border-0">
                                    <div class="p-2 px-3 text-uppercase ctext">Erstellt</div>
                                </th>
                                <th scope="col" class="border-0" style="width: 15%"></th>
                            </div>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user1): ?>
                            <tr>
                                <td class="border-0 text-center">
                                    <strong><?=$user1['user_id']?></strong>
                                </td>
                                <td class="border-0 text-center">
                                    <strong><?=$user1['vorname']?></strong>
                                </td>
                                <td class="border-0 text-center">
                                    <strong><?=$user1['nachname']?></strong>
                                </td>
                                <td class="border-0">
                                    <strong><?=$user1['created_at']?></strong>
                                </td>
                                <td class="border-0 actions text-center">
                                    <?php if ($user1['user_id'] != 0):?>
                                    <form action="user.php" method="post" class="d-grid gap-2 d-md-flex justify-content-md-end">
                                        <div class="">
                                            <input type="number" value="<?=$user1['user_id']?>" name="user_id" style="display: none;" required>
                                            <button type="submit" name="action" value="mod" class="btn btn-kolping">Editieren</button>
                                        </div>
                                        <div class="">
                                            <input type="number" value="<?=$user1['user_id']?>" name="user_id" style="display: none;" required>
                                            <button class="btn btn-danger" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas<?=$user1['user_id']?>" aria-controls="offcanvas<?=$user1['user_id']?>">Löschen</button>
                                            <div class="offcanvas offcanvas-end cbg" data-bs-scroll="true" tabindex="-1" id="offcanvas<?=$user1['user_id']?>" aria-labelledby="offcanvas<?=$user1['user_id']?>Label">
                                                <div class="offcanvas-header">
                                                    <h2 class="offcanvas-title ctext" id="offcanvas<?=$user1['user_id']?>Label">Wirklich Löschen?</h2>
                                                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                                </div>
                                                <div class="offcanvas-body">
                                                    <button class="btn btn-success mx-2" type="submit" name="action" value="deleteconfirm">Ja</button>
                                                    <button class="btn btn-danger mx-2" type="button" data-bs-dismiss="offcanvas" aria-label="Close">Nein</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>         
        </div>
    </div>
</div> 
<?php 
require_once("templates/footer.php"); 
?>
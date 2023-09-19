<?php 
chdir ($_SERVER['DOCUMENT_ROOT']);
require_once("php/functions.php"); 
$user = check_user();
if ($user == false) {
    print("<script>location.href='/login.php'</script>");
}
if ($user['perm_fest'] != 1) {
    error('Unzureichende Berechtigungen!');
}

$stmt = $pdo->prepare("SELECT * from fest ORDER BY fest_id");
$stmt->execute();
$fests = $stmt->fetchAll(PDO::FETCH_ASSOC);
$total_fests = $stmt->rowCount();



if(isset($_POST['action'])) {
    if ($_POST['action'] == 'fest_add') {
        if ($user['perm_fest'] != 1) {
            error('Unzureichende Berechtigungen!');
        } else {
            ob_start();
            require_once("templates/header.php"); 
            $buffer=ob_get_contents();
            ob_end_clean();

            $title = "ADMIN - Fest hinzufügen";
            $buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
            echo $buffer;
            ?>
            <div class="container content-wrapper p-3" style="min-height: 80vh;">
                <h1 class="mb-3 text-center display-5">Fest hinzufügen</h1>
                <div class="cbg2 rounded p-3">
                    <form action="fest.php" method="post" class="<?php if (!isMobile()) print('row');?> align-items-center">
                        <div class="col-10">
                            <div class="form-floating mb-2">
                                <input id="inputName" type="text" name="fest_name" placeholder="Fest Name" class="form-control border-0 ps-4 fw-bold" required autofocus>
                                <label for="inputName" class="fw-bold">Fest Name</label>
                            </div>
                            <div class="form-floating mt-2">
                                <input id="inputUrl" type="text" name="fest_url" placeholder="Fest URL" class="form-control border-0 ps-4 fw-bold" required autofocus>
                                <label for="inputUrl" class="fw-bold">Fest URL</label>
                            </div>
                        </div>
                        <div class="col-2 d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" name="action" value="fest_add_save" class="btn btn-success"><i class="bi bi-sd-card text-light"></i></button>
                            <button class="btn btn-danger" type="button" onclick='window.location.href = "fest.php";'><i class="bi bi-x-circle text-light"></i></button>
                        </div>
                    </form>
                </div>
            </div>
            <?php
            require_once("templates/footer.php");
        }

    } else if ($_POST['action'] == 'fest_add_save') {
        $stmt = $pdo->prepare("INSERT INTO fest (url, name, created_by) VALUE (?, ?, ?)");
        $stmt->bindValue(1, $_POST['fest_url']);
        $stmt->bindValue(2, $_POST['fest_name']);
        $stmt->bindValue(3, $user['user_id'], PDO::PARAM_INT);
        $result0 = $stmt->execute();
        if (!$result0) {
            error("Datenbankfehler beim erstellen des Festes");
        }
        print("<script>location.href='fest.php'</script>");
        exit;
    } else if ($_POST['action'] == 'fest_mod') {
        error_log("ellooo");
        print("<script>location.href='fest.php?id=".$_POST['fest_id']."'</script>");
        exit;
    } else if ($_POST['action'] == 'fest_del') {
        $stmt = $pdo->prepare("DELETE from fest_food WHERE fest_id = ?");
        $stmt->bindValue(1, $_POST['fest_id'], PDO::PARAM_INT);
        $result0 = $stmt->execute();
        if (!$result0) {
            error("Datenbankfehler beim Löschen der gegenstände des Festes");
        }
        $stmt = $pdo->prepare("DELETE from fest WHERE fest_id = ?");
        $stmt->bindValue(1, $_POST['fest_id'], PDO::PARAM_INT);
        $result1 = $stmt->execute();
        if (!$result1) {
            error("Datenbankfehler beim Löschen des Festes");
        }
        print("<script>location.href='fest.php'</script>");
        exit;
    }
}


if(isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * from fest where fest_id = ?");
    $stmt->bindValue(1, $_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $fest = $stmt->fetch();
    if ($stmt->rowCount() != 1) {
        error("Datenbankfehler beim laden des Fests");
    }
    $stmt = $pdo->prepare("SELECT * from fest_food where fest_id = ?");
    $stmt->bindValue(1, $_GET['id'], PDO::PARAM_INT);
    $result = $stmt->execute();
    $fest_foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!$result) {
        error("Datenbankfehler beim laden der Fest Gegenstände");
    }

    ob_start();
    require_once("templates/header.php"); 
    $buffer=ob_get_contents();
    ob_end_clean();

    $title = "ADMIN - Fest anpassen";
    $buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
    echo $buffer;
    ?>
    <div class="container content-wrapper p-3" style="min-height: 80vh;">
        <h1 class="mb-3 text-center display-5">Fest anpassen</h1>
        <div class="cbg2 rounded p-3">
            <form action="poll.php" method="post">
                <div class="mb-2 row align-items-center">
                    <div class="col-8 pe-0">
                        <div class="form-floating">
                            <input id="inputName" type="text" name="poll_name" placeholder="Fest Name" value="<?=$fest['name']?>" class="form-control border-0 ps-4" required autofocus>
                            <label for="inputName" class="">Fest Name</label>
                        </div>
                    </div>
                    <div class="col-4 row justify-content-end px-0">
                        <div class="col-5 justify-content-end d-grid px-0">
                            <input type="number" value="<?=$_POST['fest_id']?>" name="fest_id" style="display: none;" required>
                            <button type="submit" name="action" value="fest_add_save" class="btn btn-success"><i class="bi bi-sd-card text-light"></i></button>
                        </div>
                        <div class="col-5 justify-content-end d-grid px-0">
                            <button class="btn btn-danger" type="button" onclick='window.location.href = "fest.php";'><i class="bi bi-x-circle text-light"></i></button>
                        </div>
                    </div>
                    
                </div>
                <div class="my-2">
                    <?php foreach ($fest_foods as $fest_food):?>
                        <div class="my-2 row">
                            <div class="col-9">
                                <span>Name: <?=$fest_food['name']?></span><br>
                                <span>Preis: <?=$fest_food['price']?></span>
                            </div>
                            <div class="col-3 d-grid justify-content-end">
                                <input type="number" value="<?=$_POST['fest_id']?>" name="fest_id" style="display: none;" required>
                                <button type="submit" name="action" value="fest_mod" class="btn btn-kolping"><i class="bi bi-pencil"></i></button>

                            </div>
                        </div>

                    <?php endforeach; ?>
                </div>



                <div class="mt-2">
                    
                </div>
            </form>
        </div>
    </div>



    <?php
    require_once("templates/footer.php");
    exit;

}



ob_start();
require_once("templates/header.php"); 
$buffer=ob_get_contents();
ob_end_clean();

$title = "ADMIN - Kolpingjugend Schorndorf";
$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
echo $buffer;
?>
<div class="container content-wrapper py-3 px-3" style="min-height: 80vh;">
    <div class="row">
        <div class="py-3 px-3 cbg ctext rounded">
            <div class="d-flex justify-content-between">
                <div class="col-4">
                    <h1>Festverwaltung</h1>
                </div>
                <div class="col-4 d-flex justify-content-end">
                    <div>
                        <form action="fest.php" method="post">
                            <button class="btn btn-success" type="submit" name="action" value="fest_add"><i class="bi bi-plus-circle"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            <p><?php print($total_fests); ?> Feste</p>
            <?php if (!isMobile()):?>
                <div class="p-2 rounded cbg2">
                    <div class="table-responsive">
                        <table class="table table-dark table-striped align-middle table-borderless table-hover">
                            <thead>
                                <tr>
                                    <div class="cbg ctext rounded">
                                        <th scope="col" class="border-0 text-center" style="width: 5%">
                                            <div class="p-2 px-3 text-uppercase ctext">Aktiv</div>
                                        </th>
                                        <th scope="col" class="border-0 text-center">
                                            <div class="p-2 px-3 text-uppercase ctext">Fest ID</div>
                                        </th>
                                        <th scope="col" class="border-0 text-center">
                                            <div class="p-2 px-3 text-uppercase ctext">Name</div>
                                        </th>
                                        <th scope="col" class="border-0">
                                            <div class="p-2 px-3 text-uppercase ctext">Erstellt</div>
                                        </th>
                                        <th scope="col" class="border-0" style="width: 15%"></th>
                                    </div>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($fests as $fest): ?>
                                        <td class="border-0 text-center">
                                            <?php if ($fest['active'] == "1"): print('<button class="btn btn-success" disabled>Aktiv</button>'); else: print('<button class="btn btn-secondary" disabled>Inaktiv</button>'); endif;?>
                                        </td>
                                        <td class="border-0 text-center">
                                            <strong><?=$fest['fest_id']?></strong>
                                        </td>
                                        <td class="border-0 text-center">
                                            <strong><?=$fest['name']?></strong>
                                        </td>
                                        <td class="border-0">
                                            <strong><?=$fest['created_at']?></strong>
                                        </td>
                                        <td class="border-0 actions text-center">
                                            <form action="fest.php" method="post" class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                <div class="">
                                                    <input type="number" value="<?=$fest['fest_id']?>" name="fest_id" style="display: none;" required>
                                                    <button type="submit" name="action" value="fest_mod" class="btn btn-kolping">Editieren</button>
                                                </div>
                                                <div class="">
                                                    <input type="number" value="<?=$fest['fest_id']?>" name="fest_id" style="display: none;" required>
                                                    <button class="btn btn-danger" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas<?=$fest['fest_id']?>" aria-controls="offcanvas<?=$fest['fest_id']?>">Löschen</button>
                                                    <div class="offcanvas offcanvas-end cbg" data-bs-scroll="true" tabindex="-1" id="offcanvas<?=$fest['fest_id']?>" aria-labelledby="offcanvas<?=$fest['fest_id']?>Label">
                                                        <div class="offcanvas-header">
                                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                                        </div>
                                                        <div class="offcanvas-body">
                                                            <span class="pb-3">Alles was in diesem Fest ist wird gelöscht!<br></span>
                                                            <h2 class="offcanvas-title ctext" id="offcanvas<?=$fest['fest_id']?>Label">Wirklich Löschen?</h2>
                                                            <button class="btn btn-success mx-2" type="submit" name="action" value="fest_del">Ja</button>
                                                            <button class="btn btn-danger mx-2" type="button" data-bs-dismiss="offcanvas" aria-label="Close">Nein</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div> 
                </div>
            <?php else:?>
                <div class="row row-cols-1">
                    <?php foreach($fests as $fest):?>
                        <div class="col my-2">
                            <div class="card">
                                <div class="card-body">
                                    <h1 class="card-title"><?=$fest['name']?></h1>
                                    <div class="card-text">
                                        <div class="ctext">Fest ID: <?=$fest['fest_id']?></div>
                                        <div class="ctext">Name: <?=$fest['name']?></div>
                                        <div class="ctext">Erstellt: <?=$fest['created_at']?></div>
                                        <div class="row mt-2">
                                            <div class="col-3 align-items-start">
                                                <?php if ($fest['active'] == "1"): print('<button class="btn btn-success" disabled>Aktiv</button>'); else: print('<button class="btn btn-secondary col-4" disabled>Inaktiv</button>'); endif;?>
                                            </div>
                                            <div class="col-9">
                                                <form action="fest.php" method="post" class="d-grid justify-content-end">
                                                    <div class="">
                                                        <input type="number" value="<?=$fest['fest_id']?>" name="fest_id" style="display: none;" required>
                                                        <button type="submit" name="action" value="fest_mod" class="btn btn-kolping me-1">Editieren</button>
                                                        <button class="btn btn-danger ms-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas<?=$fest['fest_id']?>" aria-controls="offcanvas<?=$fest['fest_id']?>">Löschen</button>
                                                        <div class="offcanvas offcanvas-end cbg" data-bs-scroll="true" tabindex="-1" id="offcanvas<?=$fest['fest_id']?>" aria-labelledby="offcanvas<?=$fest['fest_id']?>Label">
                                                            <div class="offcanvas-header">
                                                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                                            </div>
                                                            <div class="offcanvas-body">
                                                                <span class="pb-3">Alles was in diesem Fest ist wird gelöscht!<br></span>
                                                                <h2 class="offcanvas-title ctext" id="offcanvas<?=$fest['fest_id']?>Label">Wirklich Löschen?</h2>
                                                                <button class="btn btn-success mx-2" type="submit" name="action" value="fest_del">Ja</button>
                                                                <button class="btn btn-danger mx-2" type="button" data-bs-dismiss="offcanvas" aria-label="Close">Nein</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>    
            <?php endif; ?>     
        </div>
    </div>
</div> 
<?php require_once("templates/footer.php"); ?>
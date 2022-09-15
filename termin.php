<?php
require_once("php/functions.php");

if (!isset($_GET["id"])) {
    header("location: events.php");
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM events where events_id  = ?  and visible = 1');
// bindValue will allow us to use integer in the SQL statement, we need to use for LIMIT
$stmt->bindValue(1, $_GET["id"], PDO::PARAM_INT);
$stmt->execute();
if ($stmt->rowCount() != 1) {
    error_log($stmt->rowCount());
    header("location: termine.php");
    exit;
}
$event = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT vorname, nachname FROM users where user_id = ?');
$stmt->bindValue(1, $event[0]['created_by'], PDO::PARAM_INT);
$stmt->execute();
$creator = $stmt->fetchAll(PDO::FETCH_ASSOC);

require("templates/header.php");
?>
<div class="container-xxl py-3" style="min-height: 80vh;">
    <div class="row">
        <h1 class="display-4 text-center mb-3 text-kolping-orange"><?=$event[0]["title"]?></h1>
    </div>
    <div class="<?php if (!isMobile()) print("row");?>">
        <div class="col<?php if (!isMobile()) print("-6");?> mb-3">
            <div class="card cbg2 py-3 px-3">
                <div class="row g-0">
                    <div class="col">
                        <div class="card cbg text-size-larger py-3 px-3 align-items-center text-center">
                            <?=date('d', strtotime($event[0]['date']))?>
                            <br>
                            <?=date('M', strtotime($event[0]['date']))?>
                        </div>
                    </div>
                    <div class="col-md-10 d-flex justify-content-start align-items-center">
                        <div class="card-body ctext align-items-center">
                            <h3 class="card-title align-center"><?=$event[0]['title']?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <span class="col<?php if (!isMobile()) print("-6");?>" id="text-<?=$event[0]["events_id"]?>"><?=$event[0]["text"]?></span>
        <script>unMarkToSpan("text-<?=$event[0]["events_id"]?>")</script>
    </div>
    <div class="row justify-content-between pt-3">
        <div class="col d-flex justify-content-start text-start ctext text-size-large <?php if (!isMobile()) print("ps-0");?>">
            <?=$creator[0]['vorname']?> <?=$creator[0]['nachname']?>
        </div>
        <div class="col d-flex justify-content-end text-end ctext text-size-large">
            <?=date('d.m.Y H:i', strtotime($event[0]['created_at']))?>
        </div>
    </div>
</div>

<?php
include_once("templates/footer.php")
?>

<?php
require_once("php/functions.php");

if (!isset($_GET["id"])) {
    header("location: blogs.php");
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM blog_entrys where blog_entrys_id  = ?');
// bindValue will allow us to use integer in the SQL statement, we need to use for LIMIT
$stmt->bindValue(1, $_GET["id"], PDO::PARAM_INT);
$stmt->execute();
if ($stmt->rowCount() != 1) {
    error_log($stmt->rowCount());
    header("location: blogs.php");
    exit;
}
$entry = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM blog_images where blog_entrys_id = ?');
$stmt->bindValue(1, $entry[0]['blog_entrys_id'], PDO::PARAM_INT);
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT vorname, nachname FROM users where user_id = ?');
$stmt->bindValue(1, $entry[0]['created_by'], PDO::PARAM_INT);
$stmt->execute();
$creator = $stmt->fetchAll(PDO::FETCH_ASSOC);

require("templates/header.php");
?>
<div class="container-xxl py-3" style="min-height: 80vh;">
    <div class="row">
        <h1 class="display-4 text-center mb-3 text-kolping-orange"><?=$entry[0]["name"]?></h1>
    </div>
    <div class="clearfix">
        <div class="col-md-6 float-md-end mb-3 ms-md-3">
            <div class="card py-3 px-3 cbg2 d-flex justify-content-center">
                <div class="">
                    <?php $i = 0; foreach ($images as $image):?>
                        <div>
                            <a data-bs-toggle="modal" data-bs-target="#picModal-<?=$i?>">
                                <img src="<?=$image['source']?>" alt="<?=$image['alt']?>" class="img-fluid rounded">
                                <span class="ctext d-flex pt-2"><?=$image['alt']?> | Quelle: <?=$image['owner']?></span>
                            </a>
                        </div>
                        <div class="modal fade" id="picModal-<?=$i?>" tabindex="-1" aria-labelledby="picModal-<?=$i?>-Label" style="display: none;" aria-modal="true">
                            <div class="modal-dialog modal-fullscreen">
                                <div class="modal-content">
                                    <div class="modal-header cbg">
                                        <h5 class="modal-title" id="picModal-<?=$i?>-Label"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body cbg">
                                        <div class="d-flex justify-content-center align-content-center">
                                            <img src="<?=$image['source']?>" class="img-fluid rounded" style="max-height: 70vh;" alt="<?=$image['alt']?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer cbg justify-content-center">
                                        <span class="ctext"><?=$image['alt']?> | Quelle: <?=$image['owner']?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php $i++; endforeach;?>
                </div>
            </div>
        </div>
        <span class="text-center justify-content-center d-block" id="prevtext-<?=$entry['blog_entrys_id']?>"><?=$entry[0]["prev_text"]?></span>
        <script>unMarkToSpan("prevtext-<?=$entry['blog_entrys_id']?>")</script>
        <span id="text-<?=$entry['blog_entrys_id']?>"><?=$entry[0]["text"]?></span>
        <script>unMarkToSpan("text-<?=$entry['blog_entrys_id']?>")</script>
        <div class="row justify-content-between py-3">
            <div class="col ctext text-size-large ps-0">
                <?=$creator[0]['vorname']?> <?=$creator[0]['nachname']?>
            </div>
            <div class="col text-end ctext text-size-large">
                <?=date('d.m.Y H:i', strtotime($entry[0]['created_at']))?>
            </div>
        </div>
    </div>
</div>

<?php
include_once("templates/footer.php")
?>

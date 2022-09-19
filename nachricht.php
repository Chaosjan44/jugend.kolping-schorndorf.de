<?php
require_once("php/functions.php");

if (!isset($_GET["id"])) {
    header("location: nachrichten.php");
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM blog_entrys where blog_entrys_id  = ? and visible = 1');
// bindValue will allow us to use integer in the SQL statement, we need to use for LIMIT
$stmt->bindValue(1, $_GET["id"], PDO::PARAM_INT);
$stmt->execute();
if ($stmt->rowCount() != 1) {
    error_log($stmt->rowCount());
    header("location: nachrichten.php");
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
                <div>
                    <?php $i = 0; foreach ($images as $image):?>
                        <div class="py-3">
                            <a data-bs-toggle="modal" data-bs-target="#picModal-<?=$i?>">
                                <picture style="max-height: 70vh;">
                                    <source type="image/webp" srcset="<?=$image['source']?>.webp" class="img-fluid rounded">
                                    <source type="image/jpeg" srcset="<?=$image['source']?>" class="img-fluid rounded">
                                    <img src="<?=$image['source']?>" class="img-fluid rounded" alt="<?=$image['alt']?>">
                                </picture>
                                <span class="ctext d-flex pt-2"><?=$image['alt']?><?php if (!isMobile()) print(" | "); else print("<br>");?>Quelle: <?=$image['owner']?></span>
                            </a>
                        </div>
                        <div class="modal fade" id="picModal-<?=$i?>" tabindex="-1" aria-labelledby="picModal-<?=$i?>-Label" style="display: none;" aria-modal="true">
                            <div class="modal-dialog modal-fullscreen">
                                <div class="modal-content">
                                    <div class="modal-header cbg">
                                        <h5 class="modal-title" id="picModal-<?=$i?>-Label"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body cbg px-auto py-auto">
                                        <div class="d-flex justify-content-center align-content-center">
                                            <img src="<?=$image['source']?>" class="img-fluid rounded" alt="<?=$image['alt']?>" style="max-height: 70vh;">
                                        </div>
                                    </div>
                                    <div class="modal-footer cbg justify-content-center">
                                        <span class="ctext"><?=$image['alt']?><?php if (!isMobile()) print(" | "); else print("<br>");?>Quelle: <?=$image['owner']?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php $i++; endforeach;?>
                </div>
            </div>
        </div>
        <span id="text-<?=$entry['blog_entrys_id']?>"><?=$entry[0]["text"]?></span>
        <script>unMarkToSpan("text-<?=$entry['blog_entrys_id']?>")</script>
        <div class="row justify-content-between pt-3">
            <div class="col ctext text-size-large <?php if (!isMobile()) print("ps-0");?>">
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

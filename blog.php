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


require("templates/header.php");
?>
<div class="container-xxl py-3" style="min-height: 80vh;">
    <div class="row ctext">
        <h1 class="display-4 text-center mb-3 text-kolping-orange"><?=$entry[0]["name"]?></h1>
    </div>
    <div class="row gx-5 pt-3">
        <div class="card cbg py-2 px-2 mx-2">
            <div class="card-body px-3 py-3">
                <div id="carouselExampleDark" class="carousel <?php if (check_style() == "dark") { print("carousel-dark "); }?>slide" data-bs-ride="carousel">
                    <?php if($images == null):?>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="images/404_<?=check_style();?>.gif" class="img-fluid rounded" alt="<?=$entry[0]['name']?>">
                            </div>
                        </div>
                    <?php elseif (count($images) == 1):?>
                        <div class="carousel-inner">
                            <?php foreach ($images as $image): { ?>
                                <div class="carousel-item active">
                                    <img src="<?=$image['source']?>" class="img-fluid rounded" alt="<?=$image['alt']?>">
                                </div>
                            <?php } endforeach; ?>
                        </div>
                    <?php elseif (count($images) > 1):?>
                        <div class="carousel-indicators">
                            <?php $i = 0; foreach ($images as $image) {
                                if ($i == 0) {
                                    print('<button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Img 1"></button>');
                                }
                                else {
                                    print('<button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="'.$i.'" aria-label="Img'.$i.'"></button>');
                                }
                                $i++;
                            } ?>
                        </div>
                        <div class="carousel-inner">
                            <?php $i = 1; foreach ($images as $image) {
                                if ($i == 1):?>
                                    <div class="carousel-item active">
                                        <img src="<?=$image['source']?>" class="img-fluid rounded" alt="<?=$image['alt']?>">
                                    </div>
                                <?php else:?>
                                    <div class="carousel-item">
                                        <img src="<?=$image['source']?>" class="img-fluid rounded" alt="<?=$image['alt']?>">
                                    </div>
                                <?php endif;?>
                            <?php $i++; }?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once("templates/footer.php")
?>

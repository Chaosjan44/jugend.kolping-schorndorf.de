<?php
require_once("php/functions.php");

if (!isset($_GET["id"])) {
    header("location: blogs.php");
    exit;
}
error_log($_GET["id"]);

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
$stmt->bindValue(1, $entry['id'], PDO::PARAM_INT);
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);


require("templates/header.php");
?>
<div class="container-xxl" style="min-height: 80vh;">
    <div class="row ctext">
        <h1 class="display-4 text-center mb-3 text-kolping-orange"><?=$entry["name"]?></h1>
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
                                    <img src="<?=$image['source']?>" class="img-fluid rounded" alt="<?=$entry['name']?>">
                                </div>
                            <?php } endforeach; ?>
                        </div>
                    <?php elseif (count($images) != 1):?>
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
                                if ($i == 1) {
                                    print('<div class="carousel-item active" data-bs-interval="10000">');
                                        print('<img src="product_img/'.$image['img'].'" class="img-fluid rounded" alt="'.$entry[0]['name'].'">');
                                    print('</div>');
                                }
                                else {
                                    print('<div class="carousel-item" data-bs-interval="10000">');
                                        print('<img src="product_img/'.$image['img'].'" class="img-fluid rounded" alt="'.$entry[0]['name'].'">');
                                    print('</div>');
                                }
                                $i++;
                            } ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    <?php endif;?>              
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card cbg py-2 px-2 mx-2">
            <div class="card-body px-3 py-3">
                <div class="row">
                    <div>
                        <h1 class="ctext"><?=$product[0]['name']?></h1>
                        <span class="ctext col">Preis: <?=$product[0]['price']?>&euro;</span> 
                        <?php if ($product[0]['rrp'] > 0): ?>
                            <span class="ctext col">UVP <?=$product[0]['rrp']?>&euro;</span>
                        <?php endif; ?>
                        <?php if ($product[0]['visible'] == 0):?>
                            <h2 class="text-danger my-2">Das Produkt aktuell nicht bestellbar!</h2>
                        <?php elseif ($product[0]['quantity'] >= 20):?>
                            <h2 class="text-success my-2">Auf Lager</h2>
                        <?php elseif ($product[0]['quantity'] > 5 && $product[0]['quantity'] < 20):?>
                            <h2 class="text-warning my-2">Nur noch <?=$product[0]['quantity']?> auf Lager!</h2>
                        <?php elseif ($product[0]['quantity'] == 0):?>
                            <h2 class="text-danger my-2">Das Produkt ist ausverkauft!</h2>
                        <?php else: ?>
                            <h2 class="text-danger my-2">Nur noch <?=$product[0]['quantity']?> auf Lager!</h2>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($product[0]['visible'] == 1 && $product[0]['quantity'] != 0):?>
                <div class="row">
                    <div class="cart">
                        <form action="cart.php" method="post">
                            <div class="input-group">
                                <span class="input-group-text">Anzahl:</span>
                                <input type="number" value="<?=$product[0]['id']?>" name="productid" style="display: none;" required>
                                <input type="number" value="1" min="1" max="<?=$product[0]['quantity']?>" class="form-control" name="quantity" required>
                                <button class="btn btn-outline-primary" type="submit" name="action" value="add">Hinzufügen</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                <p class="ctext"><?=$product[0]['desc']?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<!--
Ein Wird oft zusammen gekauf fehlt noch
SQL ABFRAGE:
SELECT *, (SELECT img From product_images WHERE product_images.product_id=products.id ORDER BY id LIMIT 1) AS image, COUNT(*) as counter FROM product_list, products WHERE product_list.list_id IN (SELECT product_list.list_id FROM product_list WHERE product_list.product_id = 1) AND NOT product_list.product_id = 1 and product_list.product_id = products.id GROUP BY product_list.product_id ORDER BY counter DESC LIMIT 3;
-->
<?php
include_once("templates/footer.php")
?>

<?php 
require_once("php/functions.php");
if ($_GET['e'] == null) {
    print("<script>location.href='/index.php'</script>");
}

$stmt = $pdo->prepare("SELECT * from fest where url = ?");
$stmt->bindValue(1, $_GET['e']);
$stmt->execute();
$fest = $stmt->fetch(PDO::FETCH_ASSOC);
if ($stmt->rowCount() == 0) {
    // print("<script>location.href='/index.php'</script>");
} elseif ($stmt->rowCount() != 1) {
    error("Error while pulling fest data", pdo_debugStrParams($stmt));
}

$stmt = $pdo->prepare("SELECT * from fest_food_cat");
$result = $stmt->execute();
if (!$result) {
    error('Datenbank Fehler!', pdo_debugStrParams($stmt));
}
$fest_food_cats = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT * from fest_food where fest_id = ?");
$stmt->bindValue(1, $fest['fest_id']);
$result0 = $stmt->execute();
if (!$result0) {
    error('Datenbank Fehler!', pdo_debugStrParams($stmt));
}
$all_items = $stmt->fetchAll(PDO::FETCH_ASSOC);


ob_start();
require_once("templates/header.php"); 
$buffer=ob_get_contents();
ob_end_clean();


$title = "Kolpingjugend Schorndorf - ".$fest['name'];
$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
echo $buffer;
?>
<div class="container py-3">
    <div style="min-height: 80vh;">
        <h1 class="display-3 text-center text-kolping-orange"><?=$fest['name']?></h1>
        <div class="row mx-auto my-3">
            <?php foreach ($fest_food_cats as $fest_food_cat): 
                $stmt = $pdo->prepare("SELECT * from fest_food where fest_food_cat_id = ? and fest_id = ?");
                $stmt->bindValue(1, $fest_food_cat['fest_food_cat_id']);
                $stmt->bindValue(2, $fest['fest_id']);
                $result0 = $stmt->execute();
                if (!$result0) {
                    error('Datenbank Fehler!', pdo_debugStrParams($stmt));
                }
                $total_items = $stmt->rowCount();
                $items = $stmt->fetchAll(PDO::FETCH_ASSOC); ?>
                <div class="p-0 mb-3">
                    <h3 class="text-center ctext mb-1"><?=$fest_food_cat['cat_name']?>:</h3>
                    <?php foreach ($items as $item): ?>
                        <div class="row px-0 m-0">
                            <a class="col-7 px-0 text-kolping-orange" data-bs-toggle="modal" data-bs-target="#itemModal<?=$item['fest_food_id']?>"><?=$item['name']?></a>
                            <a class="col-5 text-end px-0 text-kolping-orange" data-bs-toggle="modal" data-bs-target="#itemModal<?=$item['fest_food_id']?>"><?php if ($item['liters'] != null) print($item['liters']." | ");?><?=$item['price']?> &euro;</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php foreach ($all_items as $item): ?>
<div class="modal fade" id="itemModal<?=$item['fest_food_id']?>" tabindex="-1" aria-labelledby="itemModalLabel<?=$item['fest_food_id']?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header cbg pb-0" <?php if (check_style() == "dark") print ('data-bs-theme="dark"');?>>
                <h1 class="modal-title fs-5 ctext" id="itemModalLabel<?=$item['fest_food_id']?>"><?=$item['name']?></h1>
                <button type="button" class="btn-close ctext" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body cbg px-3 py-3 <?php if ($item['text'] == null) print("rounded-bottom");?>">
                <img src="<?=$item['img_path']?>" alt="" class="img-thumbnail">
                <div class="d-flex justify-content-between pt-3">
                    <h3 class="ctext mb-0"><?php if ($item['liters'] != null) print($item['liters']);?></h3>
                    <h3 class="ctext mb-0"><?=$item['price']?>&euro;</h3>
                </div>
                
            </div>
            <?php if ($item['text'] != null) print('<div class="modal-footer cbg pt-0 justify-content-between px-3 pb-3"><span class="m-0">'.$item['text'].'</span></div>') ?>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php require_once("templates/footer.php"); ?>
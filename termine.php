<?php require_once("templates/header.php"); 

$stmt = $pdo->prepare('SELECT * FROM events WHERE visible = 1 ORDER BY date desc');
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="container py-3">
    <div style="min-height: 80vh;">
        <h1 class="display-3 text-center mb-3 text-kolping-orange">Termine</h1>
        <div class="row row-cols-<?php if (!isMobile()) print("3"); else print("1");?> gx-3">
            <?php foreach ($events as $event): ?>
                <div class="col p-2">
                    <div class="card cbg2 shadow1" style="height: 100% !important;">
                        <div class="card-body ctext">
                            <h3 class="card-title text-center"><?=$event['title']?></h3>
                            <div class="col-md-2 mx-auto">
                                <div class="card cbg text-size-larger py-3 px-3 align-items-center text-center">
                                    <div>
                                        <span>
                                            <?=date('d', strtotime($event['date']))?>
                                            <br>
                                            <?=date('M', strtotime($event['date']))?>
                                        </span>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <a href="/termin.php?id=<?=$event['events_id']?>" class="stretched-link"></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php require_once("templates/footer.php"); ?>



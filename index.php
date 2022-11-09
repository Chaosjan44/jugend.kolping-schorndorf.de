<?php require_once("templates/header.php"); 
$stmt = $pdo->prepare('SELECT * ,(SELECT source From blog_images WHERE blog_images.blog_entrys_id=blog_entrys.blog_entrys_id AND prev_img=1) AS source,(SELECT alt From blog_images WHERE blog_images.blog_entrys_id=blog_entrys.blog_entrys_id AND prev_img=1) AS alt FROM blog_entrys where visible = 1 ORDER BY created_at desc LIMIT 6;');
$stmt->execute();
$blogentrys = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM events where visible = 1 AND date > DATE_SUB(NOW(), INTERVAL 1 DAY) ORDER BY date asc LIMIT 6;');
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container-fluid px-0 pt-0 pb-3">
    <div class="mb-3" style="min-height: <?php if (!isMobile()) print("33"); else print("38"); ?>vh;">
        <div id="carouselExampleFade" class="carousel <?php if (check_style() == "dark") { print("carousel-dark "); }?>slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- <div class="carousel-item active">
                    <picture data-bs-interval="5000">
                        <source type="image/webp" srcset="/media/indexpic1<?php #if (isMobile()) {print("_mobile");}?>.webp" class="d-block w-100 index-img">
                        <source type="image/jpeg" srcset="/media/indexpic1<?php #if (isMobile()) {print("_mobile");}?>.jpg" class="d-block w-100 index-img">
                        <img src="/media/indexpic1<?php #if (isMobile()) {print("_mobile");}?>.jpg" class="d-block w-100 index-img" alt="Vorschaubild">
                    </picture>
                </div>
                <div class="carousel-item">
                    <picture data-bs-interval="5000">
                        <source type="image/webp" srcset="/media/indexpic2<?php #if (isMobile()) {print("_mobile");}?>.webp" class="d-block w-100 index-img">
                        <source type="image/jpeg" srcset="/media/indexpic2<?php #if (isMobile()) {print("_mobile");}?>.jpg" class="d-block w-100 index-img">
                        <img src="/media/indexpic2<?php #if (isMobile()) {print("_mobile");}?>.jpg" class="d-block w-100 index-img" alt="Vorschaubild">
                    </picture>
                </div> -->
                <div class="carousel-item active">
                    <picture data-bs-interval="5000">
                        <source type="image/webp" srcset="/media/indexpic3<?php if (isMobile()) {print("_mobile");}?>.webp" class="d-block w-100 index-img">
                        <source type="image/jpeg" srcset="/media/indexpic3<?php if (isMobile()) {print("_mobile");}?>.jpg" class="d-block w-100 index-img">
                        <img src="/media/indexpic3<?php if (isMobile()) {print("_mobile");}?>.jpg" class="d-block w-100 index-img" alt="Vorschaubild">
                    </picture>
                </div>
                <div class="carousel-item">
                    <picture data-bs-interval="5000">
                        <source type="image/webp" srcset="/media/indexpic4<?php if (isMobile()) {print("_mobile");}?>.webp" class="d-block w-100 index-img">
                        <source type="image/jpeg" srcset="/media/indexpic4<?php if (isMobile()) {print("_mobile");}?>.jpg" class="d-block w-100 index-img">
                        <img src="/media/indexpic4<?php if (isMobile()) {print("_mobile");}?>.jpg" class="d-block w-100 index-img" alt="Vorschaubild">
                    </picture>
                </div>
                <div class="carousel-item">
                    <picture data-bs-interval="5000">
                        <source type="image/webp" srcset="/media/indexpic5<?php if (isMobile()) {print("_mobile");}?>.webp" class="d-block w-100 index-img">
                        <source type="image/jpeg" srcset="/media/indexpic5<?php if (isMobile()) {print("_mobile");}?>.jpg" class="d-block w-100 index-img">
                        <img src="/media/indexpic5<?php if (isMobile()) {print("_mobile");}?>.jpg" class="d-block w-100 index-img" alt="Vorschaubild">
                    </picture>
                </div>
            </div>
            <button class="carousel-control-prev justify-content-start px-3" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" style="width: 60px; height: 60px" aria-hidden="true"></span>
            <span class="visually-hidden">Letztes</span>
            </button>
            <button class="carousel-control-next justify-content-end px-3" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
            <span class="carousel-control-next-icon" style="width: 60px; height: 60px" aria-hidden="true"></span>
            <span class="visually-hidden">Nächstes</span>
            </button>
        </div>
    </div>
    <?php if (!isMobile()): ?>
    <div class="container-xxl">
        <div class="row ctext">
            <h1 class="display-4 text-center">Kolpingjugend Schorndorf</h1>
            <span class="text-center text-size-larger">
                Die Kolpingjugend Schorndorf ist eine Jugendgruppe der <a href="https://kolping-schorndorf.de/" target="_blank" class="text-size-large link">Kolpingsfamilie Schorndorf</a>.<br>
                Wir treffen uns jeden 2. Samstag zu Gruppenstunden.<br>
                Wenn du zwischen 11 und 25 Jahre alt bist und Lust hast auch zu unserer Gruppenstunde zu kommen, melde dich gerne bei uns unter:<br>
                E-Mail: <a href="mailto:jugend@kolping-schorndorf.de" class="text-size-large link">jugend@kolping-schorndorf.de</a><br>
                Wir freuen uns auf dich!
        </span>
        </div>
        <div class="row gx-5 pt-3">
            <div class="col justify-content-center">
                <div class="row row-cols-1">
                    <h2 class="col display-6 ctext text-center">Termine</h2>
                    <div class="col">
                        <div class="row row-cols-1">
                            <?php foreach ($events as $event): ?> 
                                <div class="col mb-3">
                                    <div class="card cbg2 py-3 px-3 shadow1">
                                        <div class="row g-0">
                                            <div class="col-md-2 d-flex justify-content-start align-items-center">
                                                <div class="card cbg text-size-larger py-3 px-3 align-items-center text-center">
                                                    <?=$datedd->format(strtotime($event['date']))?>
                                                    <br>
                                                    <?=$dateMMM->format(strtotime($event['date']))?>
                                                </div>
                                            </div>
                                            <div class="col-md-10 d-flex justify-content-start align-items-center">
                                                <div class="card-body ctext align-items-center">
                                                    <h3 class="card-title mb-0 align-center"><?=$event['title']?></h3>
                                                </div>
                                            </div>
                                            <a href="/termin.php?id=<?=$event['events_id']?>" class="stretched-link"></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col justify-content-center">
                <div class="row row-cols-1">
                    <h2 class="col display-6 ctext text-center">Nachrichten</h2>
                    <div class="col">
                        <div class="row row-cols-1">
                            <?php foreach ($blogentrys as $blogentry): ?> 
                                <div class="col card cbg2 mb-3 px-0 shadow1">
                                    <div class="row g-0">
                                        <div class="col-md-4">
                                            <?php if(isset($blogentry['source'])): ?>
                                                <picture>
                                                    <source type="image/webp" srcset="<?=$blogentry['source']?>.webp" class="img-fluid rounded-start">
                                                    <source type="image/jpeg" srcset="<?=$blogentry['source']?>" class="img-fluid rounded-start">
                                                    <img src="<?=$blogentry['source']?>" class="img-fluid rounded-start" alt="<?=$blogentry['alt']?>">
                                                </picture>
                                            <?php endif;?>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body ctext">
                                                <h3 class="card-title"><?=$blogentry['name']?></h3>
                                                <span id="text-<?=$blogentry['blog_entrys_id']?>"><?=$blogentry['prev_text']?></span>
                                                <script>unMarkToSpan("text-<?=$blogentry['blog_entrys_id']?>")</script>
                                            </div>
                                        </div>
                                        <a href="/nachricht.php?id=<?=$blogentry['blog_entrys_id']?>" class="stretched-link"></a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="container">
        <div class="row ctext">
            <h1 class="display-4 text-center">Kolpingjugend Schorndorf</h1>
            <span class="text-center text-size-larger">
                Die Kolpingjugend Schorndorf ist eine Jugendgruppe der<br>
                <a href="https://kolping-schorndorf.de/" target="_blank" class="text-size-large link">Kolpingsfamilie Schorndorf</a>.<br>
                Wir treffen uns jeden 2. Samstag zu Gruppenstunden.<br>
                Wenn du zwischen 11 und 25 Jahren alt bist und Lust hast auch zu unserer Gruppenstunde zu kommen, melde dich gerne bei uns unter:<br>
                E-Mail: <a href="mailto:jugend@kolping-schorndorf.de" class="text-size-large link">jugend@kolping-schorndorf.de</a><br>
                Wir freuen uns auf dich!
        </span>
        </div>
        <div class="row gx-5 pt-3 justify-content-center">
            <div class="col-11">
                <div class="col justify-content-center my-2">
                    <div class="row row-cols-1">
                        <h2 class="col display-6 ctext text-center">Termine</h2>
                        <div class="col">
                            <div class="row row-cols-1">
                            <?php foreach ($events as $event): ?> 
                                <div class="col mb-3 card cbg2 py-3 px-3 shadow1"> 
                                    <div class="row g-0 row-cols-2">
                                        <div class="col-3 d-flex justify-content-start align-items-center">
                                            <div class="card cbg text-size-larger py-3 px-3 align-items-center text-center">
                                                <?=$datedd->format(strtotime($event['date']))?>
                                                <br>
                                                <?=$dateMMM->format(strtotime($event['date']))?>
                                            </div>
                                        </div>
                                        <div class="col-9 d-flex justify-content-start align-items-center">
                                            <div class="card-body ctext align-items-center">
                                                <h5 class="card-title align-center mb-0 text-break"><?=$event['title']?></h5>
                                            </div>
                                        </div>
                                        <a href="/termin.php?id=<?=$event['events_id']?>" class="stretched-link"></a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col justify-content-center mt-2">
                    <div class="row row-cols-1">
                        <h2 class="col display-6 ctext text-center">Nachrichten</h2>
                        <div class="col">
                            <div class="row row-cols-1">
                                <?php foreach ($blogentrys as $blogentry): ?> 
                                    <div class="col card cbg2 mb-3 px-0 shadow1">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <?php if(isset($blogentry['source'])): ?>
                                                    <picture>
                                                        <source type="image/webp" srcset="<?=$blogentry['source']?>.webp" class="img-fluid rounded-top">
                                                        <source type="image/jpeg" srcset="<?=$blogentry['source']?>" class="img-fluid rounded-top">
                                                        <img src="<?=$blogentry['source']?>" class="img-fluid rounded-top" alt="<?=$blogentry['alt']?>">
                                                    </picture>
                                                <?php endif;?>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body ctext">
                                                    <h3 class="card-title"><?=$blogentry['name']?></h3>
                                                    <span id="text-<?=$blogentry['blog_entrys_id']?>"><?=$blogentry['prev_text']?></span>
                                                    <script>unMarkToSpan("text-<?=$blogentry['blog_entrys_id']?>")</script>
                                                </div>
                                            </div>
                                            <a href="/nachricht.php?id=<?=$blogentry['blog_entrys_id']?>" class="stretched-link"></a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <?php endif; ?>
</div>





<?php require_once("templates/footer.php"); ?>
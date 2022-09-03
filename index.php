<?php require_once("templates/header.php"); 

$stmt = $pdo->prepare('SELECT * ,(SELECT source From blog_images WHERE blog_images.blog_entrys_id=blog_entrys.blog_entrys_id AND prev_img=1) AS source,(SELECT alt From blog_images WHERE blog_images.blog_entrys_id=blog_entrys.blog_entrys_id AND prev_img=1) AS alt FROM blog_entrys where visible = 1 ORDER BY created_at desc');
$stmt->execute();
$blogentrys = $stmt->fetchAll(PDO::FETCH_ASSOC);
#print_r($blogentrys);

$stmt = $pdo->prepare('SELECT * FROM events where visible = 1 ORDER BY date asc');
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container-fluid px-0 pt-0 pb-3">
    <div class="mb-3">
        <div id="carouselExampleFade" class="carousel <?php if (check_style() == "dark") { print("carousel-dark "); }?>slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/media/wirn.jpg" class="d-block w-100" style="height: <?php if (!isMobile()) {print("50vh");} else {print("40vh");}?>; object-fit: cover; object-position: 50% 40%;" data-bs-interval="5000">
                </div>
                <div class="carousel-item">
                    <img src="/media/Vorgarten.jpg" class="d-block w-100" style="height: <?php if (!isMobile()) {print("50vh");} else {print("40vh");}?>; object-fit: cover; object-position: 50% 38%;" data-bs-interval="5000">
                </div>
                <div class="carousel-item">
                    <img src="/media/jummysoup.jpg" class="d-block w-100" style="height: <?php if (!isMobile()) {print("50vh");} else {print("40vh");}?>; object-fit: cover; object-position: 50% 48%;" data-bs-interval="5000">
                </div>
                <div class="carousel-item">
                    <img src="/media/karten1.jpg" class="d-block w-100" style="height: <?php if (!isMobile()) {print("50vh");} else {print("40vh");}?>; object-fit: cover; object-position: 50% 22%;" data-bs-interval="5000">
                </div>
                <div class="carousel-item">
                    <img src="/media/karten2.jpg" class="d-block w-100" style="height: <?php if (!isMobile()) {print("50vh");} else {print("40vh");}?>; object-fit: cover; object-position: 50% 65%;" data-bs-interval="5000">
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
            <span class="text-center text-size-larger">Die Kolpingjugend Schorndorf ist eine Jugendgruppe der <a href="https://kolping-schorndorf.de/" class="text-size-larger link">Kolpingsfamilie Schorndorf</a>,<br>wir treffen uns jeden 2. Samstag zu Gruppenstunden.</span>
        </div>
        <div class="row gx-5 pt-3">
            <div class="col justify-content-center">
                <div class="row row-cols-1">
                    <h2 class="col display-6 ctext text-center">Termine</h2>
                    <div class="col d-flex justify-content-center">
                        <div class="row row-cols-1">
                            <?php foreach ($events as $event): ?> 
                                <div class="col mb-3">
                                    <div class="card cbg2 py-3 px-3">
                                        <div class="row g-0">
                                            <div class="col-md-2 d-flex justify-content-start align-items-center">
                                                <div class="card cbg text-size-larger py-3 px-3 align-items-center text-center">
                                                    <?=date('d', strtotime($event['date']))?>
                                                    <br>
                                                    <?=date('M', strtotime($event['date']))?>
                                                </div>
                                            </div>
                                            <div class="col-md-10 d-flex justify-content-start align-items-center">
                                                <div class="card-body ctext align-items-center">
                                                    <h3 class="card-title align-center"><?=$event['title']?></h3>
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
                    <div class="col d-flex justify-content-center">
                        <div class="row row-cols-1">
                            <?php foreach ($blogentrys as $blogentry): ?> 
                                <div class="col card cbg2 mb-3 px-0">
                                    <div class="row g-0">
                                        <div class="col-md-4 my-auto">
                                            <img src="<?=$blogentry['source']?>" class="img-fluid rounded-start" alt="<?=$blogentry['alt']?>">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="card-body ctext">
                                                <h3 class="card-title"><?=$blogentry['name']?></h3>
                                                <span id="text-<?=$blogentry['blog_entrys_id']?>"><?=$blogentry['prev_text']?></span>
                                                <script>unMarkToSpan("text-<?=$blogentry['blog_entrys_id']?>")</script>
                                            </div>
                                        </div>
                                        <a href="/blog.php?id=<?=$blogentry['blog_entrys_id']?>" class="stretched-link"></a>
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
            <span class="text-center text-size-larger">Die Kolpingjugend Schorndorf ist eine Jugendgruppe der <a href="https://kolping-schorndorf.de/" class="text-size-larger link">Kolpingsfamilie Schorndorf</a>,<br>wir treffen uns jeden 2. Samstag zu Gruppenstunden.</span>
        </div>
        <div class="row gx-5 pt-3 justify-content-center">
            <div class="col-11">
                <div class="col justify-content-center my-2">
                    <div class="row row-cols-1">
                        <h2 class="col display-6 ctext text-center">Termine</h2>
                        <div class="col d-flex justify-content-center">
                            <div class="row row-cols-1">
                            <?php foreach ($events as $event): ?> 
                                <div class="col mb-3 card cbg2 py-3 px-3"> 
                                    <div class="row g-0 row-cols-2">
                                        <div class="col-3 d-flex justify-content-start align-items-center">
                                            <div class="card cbg text-size-larger py-3 px-3 align-items-center text-center">
                                                <?=date('d', strtotime($event['date']))?>
                                                <br>
                                                <?=date('M', strtotime($event['date']))?>
                                            </div>
                                        </div>
                                        <div class="col-9 d-flex justify-content-start align-items-center">
                                            <div class="card-body ctext align-items-center">
                                                <h5 class="card-title align-center text-break"><?=$event['title']?></h5>
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
                        <div class="col d-flex justify-content-center">
                            <div class="row row-cols-1">
                                <?php foreach ($blogentrys as $blogentry): ?> 
                                    <div class="col card cbg2 mb-3 px-0">
                                        <div class="row g-0">
                                            <div class="col-md-4 my-auto">
                                                <img src="<?=$blogentry['source']?>" class="img-fluid rounded-start" alt="<?=$blogentry['alt']?>">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body ctext">
                                                    <h3 class="card-title"><?=$blogentry['name']?></h3>
                                                    <span id="text-<?=$blogentry['blog_entrys_id']?>"><?=$blogentry['prev_text']?></span>
                                                    <script>unMarkToSpan("text-<?=$blogentry['blog_entrys_id']?>")</script>
                                                </div>
                                            </div>
                                            <a href="/blog.php?id=<?=$blogentry['blog_entrys_id']?>" class="stretched-link"></a>
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
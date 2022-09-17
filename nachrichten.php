<?php require_once("templates/header.php"); 

$stmt = $pdo->prepare('SELECT * ,(SELECT source From blog_images WHERE blog_images.blog_entrys_id=blog_entrys.blog_entrys_id AND prev_img=1) AS source,(SELECT alt From blog_images WHERE blog_images.blog_entrys_id=blog_entrys.blog_entrys_id AND prev_img=1) AS alt FROM blog_entrys where visible = 1 ORDER BY created_at desc');
$stmt->execute();
$blogentrys = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="container py-3">
    <div style="min-height: 80vh;">
        <h1 class="display-3 text-center mb-3 text-kolping-orange">Blogs</h1>
        <div class="row row-cols-<?php if (!isMobile()) print("4"); else print("1");?> gx-3">
            <?php foreach ($blogentrys as $blogentry): ?>
                <div class="col p-2">
                    <div class="card cbg2" style="height: 100% !important;">
                        <picture>
                            <source type="image/webp" srcset="<?=$blogentry['source']?>.webp" class="card-img-top img-fluid rounded">
                            <source type="image/jpeg" srcset="<?=$blogentry['source']?>" class="card-img-top img-fluid rounded">
                            <img src="<?=$blogentry['source']?>" class="card-img-top img-fluid rounded" alt="<?=$blogentry['alt']?>">
                        </picture>
                        <div class="card-body ctext">
                            <h3 class="card-title text-center"><?=$blogentry['name']?></h3>
                            <span id="text-<?=$blogentry['blog_entrys_id']?>"><?=$blogentry['prev_text']?></span>
                            <script>unMarkToSpan("text-<?=$blogentry['blog_entrys_id']?>")</script>
                        </div>
                        <a href="/nachricht.php?id=<?=$blogentry['blog_entrys_id']?>" class="stretched-link"></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php require_once("templates/footer.php"); ?>



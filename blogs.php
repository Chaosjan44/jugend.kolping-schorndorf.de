<?php require_once("templates/header.php"); 

$stmt = $pdo->prepare('SELECT * ,(SELECT source From blog_images WHERE blog_images.blog_entrys_id=blog_entrys.blog_entrys_id AND prev_img=1) AS source,(SELECT alt From blog_images WHERE blog_images.blog_entrys_id=blog_entrys.blog_entrys_id AND prev_img=1) AS alt FROM blog_entrys where visible = 1 ORDER BY created_at desc');
$stmt->execute();
$blogentrys = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="container py-3">
    <div style="min-height: 80vh;">
        <h1 class="display-3 text-center mb-3 text-kolping-orange">Blogs</h1>
        <div class="row row-cols-2">
            <?php foreach ($blogentrys as $blogentry): ?>
                <div class="col card cbg2 mb-3">
                    <img src="<?=$blogentry['source']?>" class="card-img-top img-fluid rounded-start" alt="<?=$blogentry['alt']?>">
                    <div class="card-body ctext">
                        <h3 class="card-title"><?=$blogentry['name']?></h3>
                        <?=$blogentry['prev_text']?>
                    </div>
                    <a href="/blog.php?id=<?=$blogentry['blog_entrys_id']?>" class="stretched-link"></a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php require_once("templates/footer.php"); ?>
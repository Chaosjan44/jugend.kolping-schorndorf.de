<?php
chdir ($_SERVER['DOCUMENT_ROOT']);
require_once("php/functions.php");
$user = check_user();
if (!isset($user)) {
    print("<script>location.href='/login.php'</script>");
    exit;
}

$stmt = $pdo->prepare('SELECT * ,(SELECT source From blog_images WHERE blog_images.blog_entrys_id=blog_entrys.blog_entrys_id AND prev_img=1) AS source,(SELECT alt From blog_images WHERE blog_images.blog_entrys_id=blog_entrys.blog_entrys_id AND prev_img=1) AS alt FROM blog_entrys where visible = 1 ORDER BY created_at desc');
$stmt->execute();
$blogentrys = $stmt->fetchAll(PDO::FETCH_ASSOC);




if (isset($_POST['action'])) {
    error_log("action");
    if ($_POST['action'] == 'save') {
        if ($user['admin'] != 1) {
            error('Unzureichende Berechtigungen!');
        }




        exit;
    }

    $blog_entrys_id = $_POST['blog_entrys_id'];
    $stmt = $pdo->prepare('SELECT * FROM blog_entrys where blog_entrys_id  = ?');
    // bindValue will allow us to use integer in the SQL statement, we need to use for LIMIT
    $stmt->bindValue(1, $blog_entrys_id, PDO::PARAM_INT);
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
    if ($_POST['action'] == 'add' || $_POST['action'] == 'mod') {
        error_log("action add/mod");
        if ($user['admin'] != 1) {
            error('Unzureichende Berechtigungen!');
        }
        
        require_once("templates/header.php"); ?>
        <script src="https://kit.fontawesome.com/0ba9bd5158.js" crossorigin="anonymous"></script> <!-- Needed -->
        <div class="container-xxl py-3" style="min-height: 80vh;">
            <script src="/js/markdown_mark.js"></script>
            <script src="/js/markdown_unmark.js"></script>
            <div class="row row-cols-1 m-4 p-2 cbg2 rounded">
                <div class="col p-2 rounded">
                    <textarea class="form-control cbg ctext" name="titleinput" id="titleinput" style="max-height: 20px;"><?=$entry[0]["name"]?></textarea>
                </div>
                <div class="col p-2 rounded d-flex">
                    <div class="input-group justify-content-start">
                        <button class="btn btn-kolping ctext px-3" onclick="makeBold(textinput)"><b>B</b></button>
                        <button class="btn btn-kolping ctext px-3" onclick="makeItalic(textinput)"><i>I</i></button>
                        <button class="btn btn-kolping ctext px-3" onclick="makeStrikethrough(textinput)"><del>Text</del></button>
                        <button class="btn btn-kolping ctext px-3" onclick="makeUnderline(textinput)"><ins>Text</ins></button>
                        <button class="btn btn-kolping ctext px-3" onclick="makeHeading(textinput)"><span>Überschrift</span></button>
                        <button class="btn btn-kolping ctext px-3" onclick="makeLink(textinput)"><a><i class="fa-solid fa-link"></i></a></button>
                        <button class="btn btn-kolping ctext px-3" onclick="makeList(textinput)"><a><i class="fa-solid fa-list"></i></a></button>
                        <button class="btn btn-kolping ctext px-3" onclick="unMarkPrev(textinput)"><i class="fa-solid fa-magnifying-glass"></i><span class="ms-2">Vorschau</span></button>
                        <button class="btn btn-kolping ctext px-3" data-bs-toggle="modal" data-bs-target="#explainModal"><i class="fa-solid fa-circle-question"></i></button>
                    </div>
                    <div class="justify-content-end">
                        <button class="btn btn-kolping ctext px-3" onclick="saveFrom(textinput)"><span>Speichern</span></button>
                    </div>
                </div>
                <div class="col p-2 rounded">
                    <textarea class="form-control cbg ctext" name="textinput" id="textinput" rows="3"><?=$entry[0]["prev_text"]?></textarea>
                </div>
                <div class="col p-2 rounded">
                    <textarea class="form-control cbg ctext" name="textinput" id="textinput" rows="10"><?=$entry[0]["text"]?></textarea>
                </div>
                <div class="col p-2 rounded">
                    <div class="input-group cbg ctext">
                        <input type="file" class="form-control" id="PicUpload">
                        <label class="input-group-text " for="PicUpload">Bilder Hochladen</label>
                    </div>
                </div>
            </div>

            <!-- explanation modal -->
            <div class="modal fade" id="explainModal" tabindex="-1" aria-labelledby="explainModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content cbg ctext">
                        <div class="modal-header">
                            <h5 class="modal-title" id="explainModalLabel">How to Markdown</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span>
                                Text <b>fett</b> machen: **Text/**<br>
                                Text <i>italic</i> machen: *_Text/*_<br>
                                Text <del>durchstreichen</del>: ~~Text/~~<br>
                                Text <ins>unterstreichen</ins>: __Text/__<br>
                                Text zu einer <h4 style="padding: 0px; display: inline-block;">Überschrift</h4> machen: ##Text<br>
                                Text zu einem Link machen: [Titel](https://example.com)<br>
                                Text zu einer Liste machen: - Text<br>
                            </span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-kolping" data-bs-dismiss="modal">Schließen</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- preview modal -->
            <div class="modal fade" id="prevModal" tabindex="-1" aria-labelledby="prevModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content cbg ctext">
                        <div class="modal-header">
                            <h5 class="modal-title" id="prevModalLabel">Vorschau</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <span id="prevModalText">
                            </span>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-kolping" data-bs-dismiss="modal">Schließen</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include_once("templates/footer.php");
        exit;
    }
}

require_once("templates/header.php"); 
?>
<div class="container py-3">
    <div style="min-height: 80vh;">
        <h1 class="display-3 text-center mb-3 text-kolping-orange">Blogs Editieren</h1>
        <form action="blog.php" method="post" enctype="multipart/form-data" class="d-flex justify-content-end">
            <button type="submit" name="action" class="btn btn-kolping" value="add">Blog Hinzufügen</button>
        </form>
        
        <div class="row row-cols-5 gx-3">
            <?php foreach ($blogentrys as $blogentry): ?>
                <div class="col p-2">
                    <div class="card cbg2" style="height: 100% !important;">
                        <img src="<?=$blogentry['source']?>" class="card-img-top img-fluid rounded-start" alt="<?=$blogentry['alt']?>">
                        <div class="card-body ctext">
                            <h3 class="card-title text-center"><?=$blogentry['name']?></h3>
                            <?=$blogentry['prev_text']?>
                        </div>
                        <form action="blog.php" method="post" enctype="multipart/form-data" class="p-2 d-flex justify-content-between">
                            <input type="number" value="<?=$blogentry['blog_entrys_id']?>" name="blog_entrys_id" style="display: none;" required>
                            <button type="submit" name="action" class="btn btn-kolping" value="mod">Editieren</button>
                            <button type="submit" name="action" class="btn btn-danger" value="del">Löschen</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php
include_once("templates/footer.php")
?>

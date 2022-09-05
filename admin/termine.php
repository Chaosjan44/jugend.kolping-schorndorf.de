<?php
chdir ($_SERVER['DOCUMENT_ROOT']);
require_once("php/functions.php");
$user = check_user();
if (!isset($user)) {
    print("<script>location.href='/login.php'</script>");
    exit;
}

$stmt = $pdo->prepare('SELECT * ,(SELECT source From blog_images WHERE blog_images.blog_entrys_id=blog_entrys.blog_entrys_id AND prev_img=1) AS source,(SELECT alt From blog_images WHERE blog_images.blog_entrys_id=blog_entrys.blog_entrys_id AND prev_img=1) AS alt FROM blog_entrys ORDER BY created_at desc');
$stmt->execute();
$blogentrys = $stmt->fetchAll(PDO::FETCH_ASSOC);




if (isset($_POST['action'])) {

    // Save article
    if ($_POST['action'] == 'save') {
        if ($user['loginperms'] != 1) {
            error('Unzureichende Berechtigungen!');
        }
        // if there is no blog_entrys_id, this happens if we save a new article which is just being created
        if (!isset($_POST['blog_entrys_id'])) {
            $stmt = $pdo->prepare('INSERT INTO blog_entrys (name, prev_text, text, visible, created_by, created_at, updated_at) VALUE (?, ?, ?, ?, ?, now(), now())');
            $stmt->bindValue(1, $_POST['titleinput']);
            $stmt->bindValue(2, $_POST['previnput']);
            $stmt->bindValue(3, $_POST['textinput']);
            $stmt->bindValue(4, (isset($_POST['visible']) ? "1" : "0"), PDO::PARAM_INT);
            $stmt->bindValue(5, $user['user_id'],PDO::PARAM_INT);
            $result = $stmt->execute();
            if (!$result) {
                error('Datenbank Fehler!', pdo_debugStrParams($stmt));
            }
            // Abfrage des Produkts um die ID zu bekommen
            $stmt = $pdo->prepare('SELECT * FROM blog_entrys where name = ? and `text` = ? order by blog_entrys_id desc');
            $stmt->bindValue(1, $_POST['titleinput']);
            $stmt->bindValue(2, $_POST['textinput']);
            $result = $stmt->execute();
            if (!$result) {
                error('Datenbank Fehler!', pdo_debugStrParams($stmt));
            }            
            $blog_id = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            $blog_entrys_id = $blog_id[0]['blog_entrys_id'];
        // Wenn der Artikel bereits existiert
        } else {
            $blog_entrys_id = $_POST['blog_entrys_id'];
            $stmt = $pdo->prepare('UPDATE blog_entrys SET name = ?, prev_text = ?, text = ?, visible = ?, updated_at = now() WHERE blog_entrys_id = ?');
            $stmt->bindValue(1, $_POST['titleinput']);
            $stmt->bindValue(2, $_POST['previnput']);
            $stmt->bindValue(3, $_POST['textinput']);
            $stmt->bindValue(4, (isset($_POST['visible']) ? "1" : "0"), PDO::PARAM_INT);
            $stmt->bindValue(5, $blog_entrys_id, PDO::PARAM_INT);
            $result = $stmt->execute();
            if (!$result) {
                error('Datenbank Fehler!', pdo_debugStrParams($stmt));
            }
        }
        print("<script>location.href='blog.php'</script>");
        exit;
    }

    // Modify an existing article
    if ($_POST['action'] == 'mod') {
        if ($user['loginperms'] != 1) {
            error('Unzureichende Berechtigungen!');
        }
        $blog_entrys_id = $_POST['blog_entrys_id'];
        $stmt = $pdo->prepare('SELECT * FROM blog_entrys where blog_entrys_id  = ?');
        // bindValue will allow us to use integer in the SQL statement, we need to use for LIMIT
        $stmt->bindValue(1, $blog_entrys_id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() != 1) {
            error_log($stmt->rowCount());
            header("location: blog.php");
            exit;
        }
        require_once("templates/header.php"); 
        ?>
        <script src="https://kit.fontawesome.com/0ba9bd5158.js" crossorigin="anonymous"></script>
        <div class="container-xxl py-3" style="min-height: 80vh;">
            <script src="/js/markdown_mark.js"></script>
            <div class="row row-cols-1 m-4 p-2 cbg2 rounded">
                <form action="blog.php" method="post" enctype="multipart/form-data">
                    <div class="col p-2 rounded">
                        <textarea class="form-control cbg ctext" name="titleinput" id="titleinput" style="max-height: 20px;"><?=$entry[0]["name"]?></textarea>
                    </div>
                    <div class="col p-2 rounded d-flex">
                        <div class="input-group justify-content-start">
                            <button type="button" class="btn btn-kolping ctext px-3" onclick="makeBold(textinput)"><b>B</b></button>
                            <button type="button" class="btn btn-kolping ctext px-3" onclick="makeItalic(textinput)"><i>I</i></button>
                            <button type="button" class="btn btn-kolping ctext px-3" onclick="makeStrikethrough(textinput)"><del>Text</del></button>
                            <button type="button" class="btn btn-kolping ctext px-3" onclick="makeUnderline(textinput)"><ins>Text</ins></button>
                            <button type="button" class="btn btn-kolping ctext px-3" onclick="makeHeading(textinput)"><span>Überschrift</span></button>
                            <button type="button" class="btn btn-kolping ctext px-3" onclick="makeLink(textinput)"><a><i class="fa-solid fa-link"></i></a></button>
                            <button type="button" class="btn btn-kolping ctext px-3" onclick="makeList(textinput)"><a><i class="fa-solid fa-list"></i></a></button>
                            <button type="button" class="btn btn-kolping ctext px-3" onclick="unMarkPrev(textinput)"><i class="fa-solid fa-magnifying-glass"></i><span class="ms-2">Vorschau</span></button>
                            <button type="button" class="btn btn-kolping ctext px-3" data-bs-toggle="modal" data-bs-target="#explainModal"><i class="fa-solid fa-circle-question"></i></button>
                        </div>
                        <div class="justify-content-end d-flex">
                            <div class="input-group flex-nowrap ctext me-2">
                                <span class="input-group-text" for="inputVisible">Visible</span>
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0 checkbox-kolping" type="checkbox" id="inputVisible" name="visible" <?=($entry[0]['visible']==1 ? 'checked':'')?>>
                                </div>                            
                            </div>
                            <input type="number" value="<?=$blog_entrys_id?>" name="blog_entrys_id" style="display: none;" required>
                            <button type="submit" class="btn btn-success ctext mx-2" name="action" value="save"><span>Speichern</span></button>
                            <button type="button" class="btn btn-danger ctext ms-2" onclick="window.location.href = '/admin/blog.php';">Abbrechen</button>
                        </div>
                    </div>
                    <div class="col p-2 rounded">
                        <textarea class="form-control cbg ctext" name="previnput" id="precinput" rows="3"><?=$entry[0]["prev_text"]?></textarea>
                    </div>
                    <div class="col p-2 rounded">
                        <textarea class="form-control cbg ctext" name="textinput" id="textinput" rows="10"><?=$entry[0]["text"]?></textarea>
                    </div>
                </form>
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
                                Text <b>fett</b> machen: **Text**<br>
                                Text <i>italic</i> machen: ___Text___<br>
                                Text <del>durchstreichen</del>: ~~Text~~<br>
                                Text <ins>unterstreichen</ins>: __Text__<br>
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
    if ($_POST['action'] == 'add') {
        require_once("templates/header.php"); 
        if ($user['loginperms'] != 1) {
            error('Unzureichende Berechtigungen!');
        }
        ?>
        <script src="https://kit.fontawesome.com/0ba9bd5158.js" crossorigin="anonymous"></script>
        <div class="container-xxl py-3" style="min-height: 80vh;">
            <script src="/js/markdown_mark.js"></script>
            <div class="row row-cols-1 m-4 p-2 cbg2 rounded">
                <form action="blog.php" method="post" enctype="multipart/form-data">
                    <div class="col p-2 rounded">
                        <textarea class="form-control cbg ctext" name="titleinput" id="titleinput" placeholder="Titel" style="max-height: 20px;"></textarea>
                    </div>
                    <div class="col p-2 rounded d-flex">
                        <div class="input-group justify-content-start">
                            <button type="button" class="btn btn-kolping ctext px-3" onclick="makeBold(textinput)"><b>B</b></button>
                            <button type="button" class="btn btn-kolping ctext px-3" onclick="makeItalic(textinput)"><i>I</i></button>
                            <button type="button" class="btn btn-kolping ctext px-3" onclick="makeStrikethrough(textinput)"><del>Text</del></button>
                            <button type="button" class="btn btn-kolping ctext px-3" onclick="makeUnderline(textinput)"><ins>Text</ins></button>
                            <button type="button" class="btn btn-kolping ctext px-3" onclick="makeHeading(textinput)"><span>Überschrift</span></button>
                            <button type="button" class="btn btn-kolping ctext px-3" onclick="makeLink(textinput)"><a><i class="fa-solid fa-link"></i></a></button>
                            <button type="button" class="btn btn-kolping ctext px-3" onclick="makeList(textinput)"><a><i class="fa-solid fa-list"></i></a></button>
                            <button type="button" class="btn btn-kolping ctext px-3" onclick="unMarkPrev(textinput)"><i class="fa-solid fa-magnifying-glass"></i><span class="ms-2">Vorschau</span></button>
                            <button type="button" class="btn btn-kolping ctext px-3" data-bs-toggle="modal" data-bs-target="#explainModal"><i class="fa-solid fa-circle-question"></i></button>
                        </div>
                        <div class="justify-content-end d-flex">
                            <div class="input-group flex-nowrap ctext me-2">
                                <span class="input-group-text" for="inputVisible">Visible</span>
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0 checkbox-kolping" type="checkbox" id="inputVisible" name="visible" checked>
                                </div>                            
                            </div>
                            <button type="submit" class="btn btn-success ctext mx-2" name="action" value="save"><span>Speichern</span></button>
                            <button type="button" class="btn btn-danger ctext ms-2" onclick="window.location.href = '/admin/blog.php';">Abbrechen</button>
                        </div>
                    </div>
                    <div class="col p-2 rounded">
                        <textarea class="form-control cbg ctext" name="previnput" id="precinput" rows="3" placeholder="Vorschau Text"></textarea>
                    </div>
                    <div class="col p-2 rounded">
                        <textarea class="form-control cbg ctext" name="textinput" id="textinput" rows="10" placeholder="Artikel Text"></textarea>
                    </div>
                </form>
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
        <?php 
        include_once("templates/footer.php");
        exit;
    }

    if ($_POST['action'] == 'del') {
        require_once("templates/header.php"); 
        if ($user['loginperms'] != 1) {
            error('Unzureichende Berechtigungen!');
        }
        $blog_entrys_id = $_POST['blog_entrys_id'];

        // Delete Blog Post
        delBlogPost($blog_entrys_id);
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
                        <div class="card-body ctext">
                            <h3 class="card-title text-center"><?=$blogentry['name']?></h3>
                            <span id="text-<?=$blogentry['blog_entrys_id']?>"><?=$blogentry['prev_text']?></span>
                            <script>unMarkToSpan("text-<?=$blogentry['blog_entrys_id']?>")</script>
                        </div>
                        <form action="blog.php" method="post" enctype="multipart/form-data" class="p-2 d-flex justify-content-between">
                            <input type="number" value="<?=$blogentry['blog_entrys_id']?>" name="blog_entrys_id" style="display: none;" required>
                            <button type="submit" name="action" class="btn btn-kolping" value="mod">Editieren</button>
                            <button class="btn btn-danger" type="button" data-bs-toggle="offcanvas" data-bs-target="#deleteCanvas-<?=$blogentry['blog_entrys_id']?>" aria-controls="deleteCanvas-<?=$blogentry['blog_entrys_id']?>">Löschen</button>
                            <div class="offcanvas offcanvas-end ctext cbg" data-bs-backdrop="static" tabindex="-1" id="deleteCanvas-<?=$blogentry['blog_entrys_id']?>" aria-labelledby="deleteCanvasLable-<?=$blogentry['blog_entrys_id']?>">
                                <div class="offcanvas-header cbg">
                                    <h5 class="offcanvas-title ctext" id="deleteCanvasLable-<?=$blogentry['blog_entrys_id']?>">Wirklich Löschen?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body cbg ctext">
                                    <div class="col d-flex justify-content-center">
                                        <input type="number" value="<?=$blogentry['blog_entrys_id']?>" name="blog_entrys_id" style="display: none;" required>
                                        <button type="submit" name="action" class="btn btn-danger mx-2" value="del">Löschen</button>
                                        <button type="button" class="btn btn-kolping mx-2" data-bs-dismiss="offcanvas" aria-label="Close">Abbrechen</button>
                                    </div>
                                </div>
                            </div>
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

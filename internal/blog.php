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
        if ($user['perm_blog'] != 1) {
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

            $stmt = $pdo->prepare('SELECT * FROM blog_images where blog_entrys_id = ?');
            $stmt->bindValue(1, $blog_entrys_id, PDO::PARAM_INT);
            $result = $stmt->execute();
            if (!$result) {
                error('Datenbank Fehler!', pdo_debugStrParams($stmt));
            }
            $imgs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // DelImgs
            for ($x = 0; $x < count($imgs); $x++) {
                $var = 'delImage-'.$x;
                if (isset($_POST[$var])) {
                    #del
                    $stmt = $pdo->prepare('SELECT source, blog_images_id FROM blog_images where blog_images_id = ? and blog_entrys_id = ?');
                    $stmt->bindValue(1, $_POST[$var], PDO::PARAM_INT);
                    $stmt->bindValue(2, $blog_entrys_id, PDO::PARAM_INT);
                    $result = $stmt->execute();
                    if (!$result) {
                        error('Datenbank Fehler!', pdo_debugStrParams($stmt));
                    }   
                    $delImgs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    delBlogImages($delImgs);            
                }
            }
            $stmt = $pdo->prepare('UPDATE blog_images SET prev_img= 0 where blog_entrys_id = ?');
            $stmt->bindValue(1, $blog_entrys_id, PDO::PARAM_INT);
            $result = $stmt->execute();
            if (!$result) {
                error('Datenbank Fehler!', pdo_debugStrParams($stmt));
            }     
            $setprev = false;
            for ($x = 0; $x < count($imgs); $x++) {
                $var = 'prevImg-'.$x;
                if (isset($_POST[$var]) && !$setprev) {
                    #del
                    $stmt = $pdo->prepare('UPDATE blog_images SET prev_img= 1 where blog_images_id = ? and blog_entrys_id = ?');
                    $stmt->bindValue(1, $_POST[$var], PDO::PARAM_INT);
                    $stmt->bindValue(2, $blog_entrys_id, PDO::PARAM_INT);
                    $result = $stmt->execute();
                    if (!$result) {
                        error('Datenbank Fehler!', pdo_debugStrParams($stmt));
                    }            
                    $setprev = true;    
                }
            }
            for ($x = 0; $x < count($imgs); $x++) {
                $imgOwner = 'imgOwner-'.$x;
                $imgAlt = 'imgAlt-'.$x;
                $id = 'blog_image_id-'.$x;
                $stmt = $pdo->prepare('UPDATE blog_images SET `owner` = ?, alt = ? where blog_images_id = ? and blog_entrys_id = ?');
                $stmt->bindValue(1, $_POST[$imgOwner]);
                $stmt->bindValue(2, $_POST[$imgAlt]);
                $stmt->bindValue(3, $_POST[$id], PDO::PARAM_INT);
                $stmt->bindValue(4, $blog_entrys_id, PDO::PARAM_INT);
                $result = $stmt->execute();
                if (!$result) {
                    error('Datenbank Fehler!', pdo_debugStrParams($stmt));
                }            
            }
        }
        // File Upload
        if (!empty($_FILES["file"]["name"][0])){
            $allowTypes = array('jpg','png','jpeg','gif','JPG','PNG','JPEG','GIF');
            $fileCount = count($_FILES['file']['name']);
            // für jedes Bild
            for($i = 0; $i < $fileCount; $i++){
                // Bild wird zum Abspeichern mit einer Einmaligen ID + Uhrsprungsame versehen
                $fileName = uniqid('image_') . '_' . basename($_FILES["file"]["name"][$i]);
                $fileName = str_replace(str_split(' '), '_', $fileName);
                // $fileName = $fileName.str_replace(" ", "_", $fileName);
                $targetFilePath = "blog_imgs/" . $fileName;
                if(in_array(pathinfo($targetFilePath,PATHINFO_EXTENSION), $allowTypes)){
                    // Hochladen der Bilder
                    if(move_uploaded_file($_FILES["file"]["tmp_name"][$i], $targetFilePath)){
                        // Einpflegen der Bilder in die Datenbank
                        $hash = md5_file("blog_imgs/" . $fileName);
                        $imgAlt = 'imgAlt-'.$hash;
                        if (isset($_POST[$imgAlt])) {
                            $imgAlt = $_POST[$imgAlt];
                        } else {
                            $imgAlt = $blog_entrys_id;
                        }
                        $imgOwner = 'imgOwner-'.$hash;
                        if (isset($_POST[$imgOwner])) {
                            $imgOwner = $_POST[$imgOwner];
                        } else {
                            $imgOwner = $blog_entrys_id;
                        }
                        $prev_img = 'prevImg-'.$hash;
                        if (isset($_POST[$prev_img])) {
                            $prev_img = 1;
                            $stmt = $pdo->prepare('UPDATE blog_images SET prev_img= 0 where blog_entrys_id = ?');
                            $stmt->bindValue(1, $blog_entrys_id, PDO::PARAM_INT);
                            $result = $stmt->execute();
                            if (!$result) {
                                error('Datenbank Fehler!', pdo_debugStrParams($stmt));
                            }
                        } else {
                            $prev_img = 0;
                        }
                        $stmt = $pdo->prepare("INSERT into blog_images (blog_entrys_id, source, alt, owner, prev_img) VALUES ( ? , ? , ? , ? , ? )");
                        $stmt->bindValue(1, $blog_entrys_id);
                        $stmt->bindValue(2, "/blog_imgs/" . $fileName);
                        $stmt->bindValue(3, $imgAlt);
                        $stmt->bindValue(4, $imgOwner);
                        $stmt->bindValue(5, $prev_img);
                        $result = $stmt->execute();
                        if (!$result) {
                            error_log(print_r($stmt, true));
                            error('Datenbank Fehler!', pdo_debugStrParams($stmt));
                        } else {
                            convertToWEBP($targetFilePath);
                        }
                        if (!$stmt) {
                            error("Hochladen Fehlgeschlagen");
                        } 
                    } else {
                        error("Hochladen Fehlgeschlagen (2)");
                    }
                } else {
                    error('Wir unterstützen nur JPG, JPEG, PNG & GIF Dateien.');
                }
            }
        }
        print("<script>location.href='blog.php'</script>");
        exit;
    }

    // Modify an existing article
    if ($_POST['action'] == 'mod') {
        if ($user['perm_blog'] != 1) {
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
        $entry = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $pdo->prepare('SELECT * FROM blog_images where blog_entrys_id = ?');
        $stmt->bindValue(1, $entry[0]['blog_entrys_id'], PDO::PARAM_INT);
        $stmt->execute();
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ob_start();
        require_once("templates/header.php"); 
        $buffer=ob_get_contents();
        ob_end_clean();

        $title = "ADMIN - Kolpingjugend Schorndorf";
        $buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
        echo $buffer;
        ?>
        <script>
            blocker = true;
        </script>
        <div class="container-xxl py-3" style="min-height: 80vh;">
            <script src='/js/md5.js'></script>
            <script src="/js/markdown_mark.js"></script>
            <div class="row row-cols-1 m-4 p-2 cbg3 rounded">
                <form action="blog.php" method="post" enctype="multipart/form-data">
                    <div class="col p-2 rounded">
                        <textarea class="form-control cbg ctext" name="titleinput" id="titleinput" style="max-height: 20px;"><?=$entry[0]["name"]?></textarea>
                    </div>
                    <?php if (!isMobile()):?>
                        <div class="col p-2 rounded d-flex">
                            <div class="input-group justify-content-start">
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeBold(textinput)"><b>B</b></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeItalic(textinput)"><i>I</i></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeStrikethrough(textinput)"><del>Text</del></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeUnderline(textinput)"><ins>Text</ins></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeHeading(textinput)"><span>Überschrift</span></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeLink(textinput)"><a><i class="bi bi-link-45deg"></i></a></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeList(textinput)"><a><i class="bi bi-list-ul"></i></a></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="unMarkPrev(textinput)"><i class="bi bi-search"></i><span class="ms-2">Vorschau</span></button>
                                <button type="button" class="btn btn-kolping ctext px-3" data-bs-toggle="modal" data-bs-target="#explainModal"><i class="bi bi-question-circle-fill"></i></button>
                            </div>
                            <div class="justify-content-end d-flex">
                                <div class="input-group flex-nowrap ctext me-2">
                                    <span class="input-group-text" for="inputVisible">Sichtbar</span>
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0 checkbox-kolping" type="checkbox" id="inputVisible" name="visible" <?=($entry[0]['visible']==1 ? 'checked':'')?>>
                                    </div>                            
                                </div>
                                <input type="number" value="<?=$blog_entrys_id?>" name="blog_entrys_id" style="display: none;" required>
                                <button type="submit" class="btn btn-success ctext mx-2" name="action" value="save" onclick="blocker = false;"><span>Speichern</span></button>
                                <button type="button" class="btn btn-danger ctext ms-2" onclick="blocker = false; window.location.href = '/internal/blog.php';">Abbrechen</button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col p-2 rounded">
                            <div class="input-group justify-content-center">
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeBold(textinput)"><b>B</b></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeItalic(textinput)"><i>I</i></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeStrikethrough(textinput)"><del>Text</del></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeUnderline(textinput)"><ins>Text</ins></button>
                            </div>
                        </div>
                        <div class="col p-2 rounded">
                            <div class="input-group justify-content-center">
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeHeading(textinput)"><span>Überschrift</span></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeLink(textinput)"><a><i class="bi bi-link-45deg"></i></a></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeList(textinput)"><a><i class="bi bi-list-ul"></i></a></button>
                            </div>
                        </div>
                        <div class="col p-2 rounded">
                            <div class="input-group justify-content-center">
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="unMarkPrev(textinput)"><i class="bi bi-search"></i><span class="ms-2">Vorschau</span></button>
                                <button type="button" class="btn btn-kolping ctext px-3" data-bs-toggle="modal" data-bs-target="#explainModal"><i class="bi bi-question-circle-fill"></i></button>
                            </div>
                        </div>
                        <div class="col p-2 rounded">
                            <div>
                                <div class="input-group flex-nowrap justify-content-center ctext">
                                    <span class="input-group-text" for="inputVisible">Sichtbar</span>
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0 checkbox-kolping" type="checkbox" id="inputVisible" name="visible" <?=($entry[0]['visible']==1 ? 'checked':'')?>>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                        <div class="col p-2 rounded d-flex justify-content-between">
                            <input type="number" value="<?=$blog_entrys_id?>" name="blog_entrys_id" style="display: none;" required>
                            <button type="submit" class="btn btn-success ctext" name="action" value="save" onclick="blocker = false;"><span>Speichern</span></button>
                            <button type="button" class="btn btn-danger ctext" onclick="blocker = false; window.location.href = '/internal/blog.php';">Abbrechen</button>
                        </div>
                    <?php endif; ?>
                    <div class="col p-2 rounded">
                        <textarea class="form-control cbg ctext" name="previnput" id="precinput" rows="3" placeholder="Vorschau Text"><?=$entry[0]["prev_text"];?></textarea>
                    </div>
                    <div class="col p-2 rounded">
                        <textarea class="form-control cbg ctext" name="textinput" id="textinput" rows="10" placeholder="Text"><?=$entry[0]["text"]?></textarea>
                    </div>
                    <div class="col p-2 rounded d-flex">
                        <div class="input-group cbg ctext">
                            <input type="file" class="form-control" id="PicUpload" name="file[]" accept="image/png, image/gif, image/jpeg" multiple onchange="showPreview(event);">
                            <label class="input-group-text " for="PicUpload">Bilder Hochladen</label>
                        </div>
                    </div>
                    <div class="col p-2 rounded">
                        <h2>Diese Bilder werden Hinzugefügt:</h2>
                        <div class="row row-cols-<?php if (!isMobile()) print("4"); else print("1"); ?> row-cols-md-4 g-4 py-2" id="preview">
                        </div>
                        <h2>Diese Bilder sind aktuell vorhanden:</h2>
                        <div class="row row-cols-<?php if (!isMobile()) print("4"); else print("1"); ?> row-cols-md-4 g-4 py-2">
                            <?php for ($x = 0; $x < count($images); $x++) :?>
                                <div class="col">
                                    <div class="card prodcard cbg">
                                        <img src="<?=$images[$x]['source']?>" class="card-img-top img-fluid rounded" alt="<?=$images[$x]['alt']?>">
                                        <div class="card-body">
                                        <input type="number" value="<?=$images[$x]['blog_images_id']?>" name="<?='blog_image_id-'.$x?>" style="display: none;" required>
                                            <div class="input-group pb-2">
                                                <span class="input-group-text" id="basic-addon1">Quelle</span>
                                                <input type="text" class="form-control" placeholder="Quelle" value="<?=$images[$x]['owner']?>" name="<?='imgOwner-'.$x?>">
                                            </div>
                                            <div class="input-group py-2">
                                                <span class="input-group-text" id="basic-addon1">Text</span>
                                                <input type="text" class="form-control" placeholder="Text" value="<?=$images[$x]['alt']?>" name="<?='imgAlt-'.$x?>">
                                            </div>
                                            <div class="input-group py-2 d-flex justify-content-center">
                                                <span class="input-group-text" for="inputVisible">Löschen?</span>
                                                <div class="input-group-text">
                                                    <input type="checkbox" class="form-check-input checkbox-kolping" value="<?=$images[$x]['blog_images_id']?>" name="<?='delImage-'.$x?>">
                                                </div>
                                            </div>
                                            <div class="input-group pt-2 d-flex justify-content-center">
                                                <span class="input-group-text" for="inputVisible">Vorschau Bild</span>
                                                <div class="input-group-text">
                                                    <input type="checkbox" class="form-check-input checkbox-kolping" value="<?=$images[$x]['blog_images_id']?>" name="<?='prevImg-'.$x?>" <?=($images[$x]['prev_img']==1 ? 'checked':'')?>>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor;?>
                        </div>
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
        <script>
            window.onbeforeunload = function() { 
                if (blocker == true) {
                    return "Achtung, deine Arbeit wird eventuell nicht gespeichert!"; 
                    blocker = true;
                }
            }
        </script>
        <?php include_once("templates/footer.php");
        exit;
    }
    if ($_POST['action'] == 'add') {
        ob_start();
        require_once("templates/header.php"); 
        $buffer=ob_get_contents();
        ob_end_clean();

        $title = "ADMIN - Kolpingjugend Schorndorf";
        $buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
        echo $buffer;
        if ($user['perm_blog'] != 1) {
            error('Unzureichende Berechtigungen!');
        }
        ?>
        <script>
            let blocker = true;
        </script>
        <div class="container-xxl py-3" style="min-height: 80vh;">
            <script src='/js/md5.js'></script>
            <script src="/js/markdown_mark.js"></script>
            <div class="row row-cols-1 m-4 p-2 cbg3 rounded">
                <form action="blog.php" method="post" enctype="multipart/form-data">
                    <div class="col p-2 rounded">
                        <textarea class="form-control cbg ctext" name="titleinput" id="titleinput" placeholder="Titel" style="max-height: 20px;"></textarea>
                    </div>
                    <?php if (!isMobile()):?>
                        <div class="col p-2 rounded d-flex">
                            <div class="input-group justify-content-start">
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeBold(textinput)"><b>B</b></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeItalic(textinput)"><i>I</i></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeStrikethrough(textinput)"><del>Text</del></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeUnderline(textinput)"><ins>Text</ins></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeHeading(textinput)"><span>Überschrift</span></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeLink(textinput)"><a><i class="bi bi-link-45deg"></i></a></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeList(textinput)"><a><i class="bi bi-list-ul"></i></a></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="unMarkPrev(textinput)"><i class="bi bi-search"></i><span class="ms-2">Vorschau</span></button>
                                <button type="button" class="btn btn-kolping ctext px-3" data-bs-toggle="modal" data-bs-target="#explainModal"><i class="bi bi-question-circle-fill"></i></button>
                            </div>
                            <div class="justify-content-end d-flex">
                                <div class="input-group flex-nowrap ctext me-2">
                                    <span class="input-group-text" for="inputVisible">Sichtbar</span>
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0 checkbox-kolping" type="checkbox" id="inputVisible" name="visible">
                                    </div>                            
                                </div>
                                <button type="submit" class="btn btn-success ctext mx-2" name="action" value="save" onclick="blocker = false;"><span>Speichern</span></button>
                                <button type="button" class="btn btn-danger ctext ms-2" onclick="blocker = false; window.location.href = '/internal/blog.php';">Abbrechen</button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col p-2 rounded">
                            <div class="input-group justify-content-center">
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeBold(textinput)"><b>B</b></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeItalic(textinput)"><i>I</i></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeStrikethrough(textinput)"><del>Text</del></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeUnderline(textinput)"><ins>Text</ins></button>
                            </div>
                        </div>
                        <div class="col p-2 rounded">
                            <div class="input-group justify-content-center">
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeHeading(textinput)"><span>Überschrift</span></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeLink(textinput)"><a><i class="bi bi-link-45deg"></i></a></button>
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="makeList(textinput)"><a><i class="bi bi-list-ul"></i></a></button>
                            </div>
                        </div>
                        <div class="col p-2 rounded">
                            <div class="input-group justify-content-center">
                                <button type="button" class="btn btn-kolping ctext px-3" onclick="unMarkPrev(textinput)"><i class="bi bi-search"></i><span class="ms-2">Vorschau</span></button>
                                <button type="button" class="btn btn-kolping ctext px-3" data-bs-toggle="modal" data-bs-target="#explainModal"><i class="bi bi-question-circle-fill"></i></button>
                            </div>
                        </div>
                        <div class="col p-2 rounded">
                            <div>
                                <div class="input-group flex-nowrap justify-content-center ctext">
                                    <span class="input-group-text" for="inputVisible">Sichtbar</span>
                                    <div class="input-group-text">
                                        <input class="form-check-input mt-0 checkbox-kolping" type="checkbox" id="inputVisible" name="visible" checked>
                                    </div>                            
                                </div>
                            </div>
                        </div>
                        <div class="col p-2 rounded d-flex justify-content-between">
                            <button type="submit" class="btn btn-success ctext" name="action" value="save" onclick="blocker = false;"><span>Speichern</span></button>
                            <button type="button" class="btn btn-danger ctext" onclick="blocker = false; window.location.href = '/internal/blog.php';">Abbrechen</button>
                        </div>
                    <?php endif; ?>
                    <div class="col p-2 rounded">
                        <textarea class="form-control cbg ctext" name="previnput" id="precinput" rows="3" placeholder="Vorschau Text"></textarea>
                    </div>
                    <div class="col p-2 rounded">
                        <textarea class="form-control cbg ctext" name="textinput" id="textinput" rows="10" placeholder="Artikel Text"></textarea>
                    </div>
                    <div class="col p-2 rounded d-flex">
                        <div class="input-group cbg ctext">
                            <input type="file" class="form-control" id="PicUpload" name="file[]" accept="image/png, image/gif, image/jpeg" multiple onchange="showPreview(event);">
                            <label class="input-group-text " for="PicUpload">Bilder Hochladen</label>
                        </div>
                    </div>
                    <div class="col p-2 rounded">
                        <h2>Diese Bilder werden Hinzugefügt:</h2>
                        <div class="row row-cols-<?php if (!isMobile()) print("4"); else print("1"); ?> row-cols-md-4 g-4 py-2" id="preview">
                        </div>
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
        <script>
            window.onbeforeunload = function() { 
                if (blocker == true) {
                    return "Achtung, deine Arbeit wird eventuell nicht gespeichert!"; 
                    blocker = true;
                }
            }
        </script>
        <?php 
        include_once("templates/footer.php");
        exit;
    }

    if ($_POST['action'] == 'del') {
        ob_start();
        require_once("templates/header.php"); 
        $buffer=ob_get_contents();
        ob_end_clean();

        $title = "ADMIN - Kolpingjugend Schorndorf";
        $buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
        echo $buffer;
        if ($user['perm_blog'] != 1) {
            error('Unzureichende Berechtigungen!');
        }
        $blog_entrys_id = $_POST['blog_entrys_id'];

        // Delete all Images the blog post uses
        $stmt = $pdo->prepare('SELECT source, blog_images_id FROM blog_images where blog_entrys_id = ?');
		$stmt->bindValue(1, $blog_entrys_id, PDO::PARAM_INT);
		$result = $stmt->execute();
		if (!$result) {
			error('Datenbank Fehler!', pdo_debugStrParams($stmt));
		}
		$delImgs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        delBlogImages($delImgs);

        // Delete Blog Post
        delBlogPost($blog_entrys_id);
        exit;
    }
}

ob_start();
require_once("templates/header.php"); 
$buffer=ob_get_contents();
ob_end_clean();

$title = "ADMIN - Kolpingjugend Schorndorf";
$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
echo $buffer; 
?>
<div class="container py-3">
    <div style="min-height: 80vh;">
        <h1 class="display-3 text-center mb-3 text-kolping-orange">Nachrichten Editieren</h1>
        <form action="blog.php" method="post" enctype="multipart/form-data" class="d-flex justify-content-end">
            <button type="submit" name="action" class="btn btn-kolping" value="add">Nachricht Hinzufügen</button>
        </form>
        
        <div class="row row-cols-<?php if (!isMobile()) print("3"); else print("1");?> gx-3">
            <?php foreach ($blogentrys as $blogentry): ?>
                <div class="col p-2">
                    <div class="card shadow1" style="height: 100% !important;">
                        <?php if(isset($blogentry['source'])): ?>
                            <picture>
                                <source type="image/webp" srcset="<?=$blogentry['source']?>.webp" class="card-img-top img-fluid rounded">
                                <source type="image/jpeg" srcset="<?=$blogentry['source']?>" class="card-img-top img-fluid rounded">
                                <img src="<?=$blogentry['source']?>" class="card-img-top img-fluid rounded" alt="<?=$blogentry['alt']?>">
                            </picture>
                        <?php endif; ?>
                        <div class="card-body ctext">
                            <h3 class="card-title text-center"><?=$blogentry['name']?></h3>
                            <span id="text-<?=$blogentry['blog_entrys_id']?>"><?=$blogentry['prev_text']?></span>
                            <script>unMarkToSpan("text-<?=$blogentry['blog_entrys_id']?>")</script>
                        </div>
                        <div class="px-2">
                            <span>Aufrufe: <?=$blogentry['views']?></span>
                        </div>
                        <form action="blog.php" method="post" enctype="multipart/form-data" class="p-2 d-flex justify-content-between">
                            <input type="number" value="<?=$blogentry['blog_entrys_id']?>" name="blog_entrys_id" style="display: none;" required>
                            <button type="submit" name="action" class="btn btn-kolping" value="mod">Editieren</button>
                            <div class="input-group flex-nowrap justify-content-center ctext">
                                <span class="input-group-text" for="inputVisible">Sichtbar</span>
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0 checkbox-kolping" type="checkbox" id="inputVisible" name="visible" <?=($blogentry['visible']==1 ? 'checked':'')?> disabled>
                                </div>   
                            </div>                           
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

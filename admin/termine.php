<?php
chdir ($_SERVER['DOCUMENT_ROOT']);
require_once("php/functions.php");
$user = check_user();
if (!isset($user)) {
    print("<script>location.href='/login.php'</script>");
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM events ORDER BY created_at desc');
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);




if (isset($_POST['action'])) {

    // Save article
    if ($_POST['action'] == 'save') {
        if ($user['loginperms'] != 1) {
            error('Unzureichende Berechtigungen!');
        }
        // if there is no blog_entrys_id, this happens if we save a new article which is just being created
        if (!isset($_POST['events_id'])) {
            $stmt = $pdo->prepare('INSERT INTO events (title, text, date, datetime_from, datetime_to, visible, created_by, created_at, updated_at) VALUE (?, ?, ?, ?, ?, ?, ?, now(), now())');
            $stmt->bindValue(1, $_POST['titleinput']);
            $stmt->bindValue(2, $_POST['textinput']);
            $stmt->bindValue(3, $_POST['date']);
            $stmt->bindValue(4, $_POST['datetime-from']);
            $stmt->bindValue(5, $_POST['datetime-till']);
            $stmt->bindValue(6, (isset($_POST['visible']) ? "1" : "0"), PDO::PARAM_INT);
            $stmt->bindValue(7, $user['user_id'],PDO::PARAM_INT);
            $result = $stmt->execute();
            if (!$result) {
                error('Datenbank Fehler!', pdo_debugStrParams($stmt));
            }
            // Abfrage des Produkts um die ID zu bekommen
            $stmt = $pdo->prepare('SELECT * FROM events where title = ? and `text` = ? order by events_id desc');
            $stmt->bindValue(1, $_POST['titleinput']);
            $stmt->bindValue(2, $_POST['textinput']);
            $result = $stmt->execute();
            if (!$result) {
                error('Datenbank Fehler!', pdo_debugStrParams($stmt));
            }            
            $event_id = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            $events_id = $event_id[0]['events_id'];
        // Wenn der Artikel bereits existiert
        } else {
            $events_id = $_POST['events_id'];
            $stmt = $pdo->prepare('UPDATE blog_entrys SET title = ?, text = ?, date = ?, datetime_from = ?, datetime_to = ?, visible = ?, updated_at = now() WHERE events_id = ?');
            $stmt->bindValue(1, $_POST['titleinput']);
            $stmt->bindValue(2, $_POST['textinput']);
            $stmt->bindValue(3, $_POST['date']);
            $stmt->bindValue(4, $_POST['datetime-from']);
            $stmt->bindValue(5, $_POST['datetime-till']);
            $stmt->bindValue(6, (isset($_POST['visible']) ? "1" : "0"), PDO::PARAM_INT);
            $stmt->bindValue(7, $events_id, PDO::PARAM_INT);
            $result = $stmt->execute();
            if (!$result) {
                error('Datenbank Fehler!', pdo_debugStrParams($stmt));
            }
        }
        print("<script>location.href='termine.php'</script>");
        exit;
    }

    // Modify an existing article
    if ($_POST['action'] == 'mod') {
        if ($user['loginperms'] != 1) {
            error('Unzureichende Berechtigungen!');
        }
        $events_id = $_POST['events_id'];
        $stmt = $pdo->prepare('SELECT * FROM events where events_id  = ?');
        // bindValue will allow us to use integer in the SQL statement, we need to use for LIMIT
        $stmt->bindValue(1, $events_id, PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->rowCount() != 1) {
            error_log($stmt->rowCount());
            header("location: termine.php");
            exit;
        }
        $event = $stmt->fetchAll(PDO::FETCH_ASSOC); 
        require_once("templates/header.php"); 
        error_log($events_id)
        ?>
        <script src="https://kit.fontawesome.com/0ba9bd5158.js" crossorigin="anonymous"></script>
        <div class="container-xxl py-3" style="min-height: 80vh;">
            <script src="/js/markdown_mark.js"></script>
            <div class="row row-cols-1 m-4 p-2 cbg2 rounded">
                <form action="termine.php" method="post" enctype="multipart/form-data">
                    <div class="col p-2 rounded">
                        <textarea class="form-control cbg ctext" name="titleinput" id="titleinput" style="max-height: 20px;"><?=$event[0]["title"]?></textarea>
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
                                    <input class="form-check-input mt-0 checkbox-kolping" type="checkbox" id="inputVisible" name="visible" <?=($event[0]['visible']==1 ? 'checked':'')?>>
                                </div>                            
                            </div>
                            <input type="number" value="<?=$events_id?>" name="events_id" style="display: none;" required>
                            <button type="submit" class="btn btn-success ctext mx-2" name="action" value="save"><span>Speichern</span></button>
                            <button type="button" class="btn btn-danger ctext ms-2" onclick="window.location.href = '/admin/termine.php';">Abbrechen</button>
                        </div>
                    </div>
                    <div class="col p-2 rounded">
                        <div class="input-group flex-nowrap ctext me-2">
                            <span class="input-group-text" for="date">Datum</span>
                            <div class="input-group-text">
                                <input type="date" name="date" id="date" class="mt-0 form-control" value="<?=$event[0]['date']?>">
                            </div>                            
                        </div>
                        <div class="input-group flex-nowrap ctext me-2">
                            <span class="input-group-text" for="datetime-from">Datum von</span>
                            <div class="input-group-text">
                                <input type="datetime" name="datetime-from" id="datetime-from" class="mt-0 form-control" value="<?=$event[0]['datetime-from']?>">
                            </div>                            
                        </div>
                        <div class="input-group flex-nowrap ctext me-2">
                            <span class="input-group-text" for="date">Datum bis</span>
                            <div class="input-group-text">
                                <input type="datetime" name="datetime-till" id="datetime-till" class="mt-0 form-control" value="<?=$event[0]['datetime-to']?>">
                            </div>                            
                        </div>
                    </div>
                    <div class="col p-2 rounded">
                        <textarea class="form-control cbg ctext" name="textinput" id="textinput" rows="10"><?=$event[0]["text"]?></textarea>
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
                            <button type="button" class="btn btn-danger ctext ms-2" onclick="window.location.href = '/admin/termine.php';">Abbrechen</button>
                        </div>
                    </div>
                    <div class="col p-2 rounded">
                        <div class="input-group flex-nowrap ctext me-2">
                            <span class="input-group-text" for="date">Datum</span>
                            <div class="input-group-text">
                                <input type="date" name="date" id="date" class="mt-0 form-control">
                            </div>                            
                        </div>
                        <div class="input-group flex-nowrap ctext me-2">
                            <span class="input-group-text" for="datetime-from">Datum von</span>
                            <div class="input-group-text">
                                <input type="datetime" name="datetime-from" id="datetime-from" class="mt-0 form-control">
                            </div>                            
                        </div>
                        <div class="input-group flex-nowrap ctext me-2">
                            <span class="input-group-text" for="date">Datum bis</span>
                            <div class="input-group-text">
                                <input type="datetime" name="datetime-till" id="datetime-till" class="mt-0 form-control">
                            </div>                            
                        </div>
                    </div>
                    <div class="col p-2 rounded">
                        <textarea class="form-control cbg ctext" name="textinput" id="textinput" rows="10" placeholder="Termin Text"></textarea>
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
        $events_id = $_POST['events_id'];

        // Delete Blog Post
        delEvent($events_id);
        exit;
    }
}

require_once("templates/header.php"); 
?>
<div class="container py-3">
    <div style="min-height: 80vh;">
        <h1 class="display-3 text-center mb-3 text-kolping-orange">Blogs Editieren</h1>
        <form action="termine.php" method="post" enctype="multipart/form-data" class="d-flex justify-content-end">
            <button type="submit" name="action" class="btn btn-kolping" value="add">Blog Hinzufügen</button>
        </form>
        
        <div class="row row-cols-5 gx-3">
            <?php foreach ($events as $event): ?>
                <div class="col p-2">
                    <div class="card cbg2" style="height: 100% !important;">
                        <div class="card-body ctext">
                            <h3 class="card-title text-center"><?=$event['title']?></h3>
                        </div>
                        <form action="termine.php" method="post" enctype="multipart/form-data" class="p-2 d-flex justify-content-between">
                            <input type="number" value="<?=$event['events_id']?>" name="events_id" style="display: none;" required>
                            <button type="submit" name="action" class="btn btn-kolping" value="mod">Editieren</button>
                            <button class="btn btn-danger" type="button" data-bs-toggle="offcanvas" data-bs-target="#deleteCanvas-<?=$event['events_id']?>" aria-controls="deleteCanvas-<?=$event['events_id']?>">Löschen</button>
                            <div class="offcanvas offcanvas-end ctext cbg" data-bs-backdrop="static" tabindex="-1" id="deleteCanvas-<?=$event['events_id']?>" aria-labelledby="deleteCanvasLable-<?=$event['events_id']?>">
                                <div class="offcanvas-header cbg">
                                    <h5 class="offcanvas-title ctext" id="deleteCanvasLable-<?=$event['events_id']?>">Wirklich Löschen?</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body cbg ctext">
                                    <div class="col d-flex justify-content-center">
                                        <input type="number" value="<?=$event['events_id']?>" name="events_id" style="display: none;" required>
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

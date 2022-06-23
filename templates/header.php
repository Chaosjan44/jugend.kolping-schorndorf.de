<?php
require_once("php/functions.php");
session_start();
?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="/css/styles.css?v=<?php print(date("Y.m.d.H.i.s")); ?>">
    <link rel="stylesheet" href="/css/dark.css" disabled>
    <link rel="stylesheet" href="/css/light.css" disabled>
    <script src="/js/custom.js"></script>
    <script src="/js/markdown_unmark.js"></script>
    <link rel="icon" type="image/svg" href="/favicon.svg" sizes="1024x1024" />
    <title>Kolpingjugend Schorndorf</title>
</head>
<body>

<nav class="navbar header-header navbar-expand-lg cbg ctext sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <img src="/favicon.svg" class="navbar-icon_light align-text-bottom pe-2">
            <img src="/favicon_dark.svg" class="navbar-icon_dark align-text-bottom pe-2">
                <span class="d-inline-block">Kolpingjugend<br>Schorndorf</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse cbg" tabindex="-1" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item text-size-x-large">
                    <a class="nav-link clink" aria-current="page" href="/termine.php">Termine</a>
                </li>
                <li class="nav-item text-size-x-large">
                    <a class="nav-link clink" href="/blog.php">Nachrichten</a>
                </li>
                <li class="nav-item text-size-x-large">
                    <a class="nav-link clink clink" href="/wir.php">Wir</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="modal fade" id="cookieModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cookieModalLabel" aria-hidden="true">
    <div class="modal-dialog cbg2">
        <div class="modal-content cbg">
            <div class="modal-header cbg">
                <h4 class="modal-title ctext fw-bold" id="cookieModalLabel">Mhhh Lecker &#x1F36A;!</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body ctext cbg fw-normal">
                <div class="px-2">
                    <p>Wir nutzen Cookies auf unserer Website.<br>
                    Alle Cookies welche auf dieser Webseite verwendet werden sind für die Funktion der Webseite nötig. <br>
                    Die Cookies werden nicht ausgewertet.
                    </p>
                </div>
            </div>
            <div class="modal-footer ctext cbg fw-bold">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" onclick='setCookie("acceptCookies", "false", 365)'>Ablehnen</button>
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick='setCookie("acceptCookies", "true", 365)'>Akzeptieren</button>
            </div>
        </div>
    </div>
</div>

<?php 
if (!check_cookie()):
?>
<script type="text/javascript">
    const myModal = new bootstrap.Modal('#cookieModal');
    const modalToggle = document.getElementById('cookieModal');
    myModal.show(modalToggle);
</script>
<?php endif; ?>
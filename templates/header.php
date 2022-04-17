<?php
require_once("php/functions.php");
?>


<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/css/styles.css?v=<?php print(date("Y.m.d.H.i.s")); ?>">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/dark.css" disabled>
    <link rel="stylesheet" href="/css/light.css" disabled>
    <script src="/js/custom.js"></script>
    <link rel="icon" type="image/svg" href="favicon.svg" sizes="1024x1024" />
    <link rel="stylesheet" href="/css/bootstrap-icons.css">
    <!-- <link rel="stylesheet" href="/css/cookiebanner.css"> -->
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
                <li class="nav-item">
                    <a class="nav-link hctext" aria-current="page" href="/termine.php">Termine</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle hctext" id="offcanvasNavbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Über uns</a>
                    <ul class="dropdown-menu cbg2" aria-labelledby="offcanvasNavbarDropdown">
                        <li><a class="dropdown-item hctext" href="/about_us/wir.php">Wir</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link hctext" href="/angebote.php">Angebote</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link hctext" href="/blog.php">Nachrichten/Blog</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="modal fade" id="cookieModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cookieModalLabel" aria-hidden="true">
    <div class="modal-dialog cbg">
        <div class="modal-content cbg">
            <div class="modal-header cbg">
                <h4 class="modal-title ctext fw-bold" id="cookieModalLabel">Mhhh Lecker &#x1F36A;!</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body ctext cbg fw-normal">
                <div class="px-2">
                    <p>Wir nutzen Cookies auf unserer Website.<br>
                    Einige von ihnen sind essenziell für den Betrieb der Seite,<br>
                    während andere uns helfen, diese Website und die Nutzererfahrung zu verbessern (Tracking Cookies).<br>
                    Sie können selbst entscheiden, ob Sie die Cookies zulassen möchten.<br>
                    Bitte beachten Sie, dass bei einer Ablehnung womöglich nicht mehr alle Funktionalitäten der Seite zur Verfügung stehen.</p>
                </div>
            </div>
            <div class="modal-footer ctext cbg fw-bold">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Ablehnen</button>
                <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="allow_cookie()">Akzeptieren</button>
            </div>
        </div>
    </div>
</div>

<?php 
if (!check_cookie()):
?>
<script type="text/javascript">
    $(window).on('load', function() {
        $('#cookieModal').modal('show');
    });
</script>
<?php endif; ?>
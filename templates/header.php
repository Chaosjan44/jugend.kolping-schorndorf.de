<?php
require_once("php/functions.php");
setlocale (LC_ALL, 'de_DE.UTF-8', 'de_DE@euro', 'de_DE', 'de', 'ge', 'de_DE.ISO_8859-1', 'German_Germany');
session_start();
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Homepage der Kolpingjugend Schorndorf. Hier veröffentlichen wir unsere Termine und immer wieder aktuelle Nachrichten der Kolpingjugend Schorndorf">
    <meta name="author" content="Developed by Jan">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js" integrity="sha384-IDwe1+LCz02ROU9k972gdyvl+AESN10+x7tBKgc9I5HFtuNz0wWnPclzo6p9vxnk" crossorigin="anonymous"></script>
    <!-- remember to remove "defer" incase I want stuff opening the second the page loads -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <script defer data-domain="jugend.kolping-schorndorf.de" src="https://plausible.schniebs.dev/js/script.js"></script>
    <link rel="stylesheet" href="/css/styles.css">          <!-- Link Stylesheet -->
    <link rel="stylesheet" href="/css/dark.css" disabled>   <!-- Link Dark Stylesheet and disable it -->
    <link rel="stylesheet" href="/css/light.css" disabled>  <!-- Link Light Stylesheet and disable it -->
    <script src="/js/custom.js"></script>
    <script src="/js/markdown_unmark.js"></script>
    <link rel="icon" type="image/png" href="/favicon.png" sizes="1024x1024" />
    <link rel="apple-touch-icon" href="/favicon.png"/>
    <title>Kolpingjugend Schorndorf</title>
</head>


<header class="sticky-top">
    <nav class="navbar navbar-expand-lg cbg ctext">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="/images/Kolpingjugend_light.svg" class="navbar-icon_light" alt="Navbar Logo">
                <img src="/images/Kolpingjugend_dark.svg" class="navbar-icon_dark" alt="Navbar Logo">
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
                        <a class="nav-link clink" href="/nachricht.php">Nachrichten</a>
                    </li>
                    <li class="nav-item text-size-x-large">
                        <a class="nav-link clink clink" href="/wir.php">Wir</a>
                    </li>
                    <li class="nav-item text-size-x-large <?php if (!isMobile()) print("ps-2 pe-2 pb-2"); else print("pb-2");?>">
                        <a href="https://kolping-schorndorf.de" target="_blank">
                            <img src="/images/KolpingK.jpg" class="navbar-icon_light_k" alt="Navbar Logo">
                            <img src="/images/KolpingK_dark.png" class="navbar-icon_dark_k" alt="Navbar Logo">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="header-line text-end">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-at-fill" viewBox="0 0 16 16">
            <path d="M2 2A2 2 0 0 0 .05 3.555L8 8.414l7.95-4.859A2 2 0 0 0 14 2H2Zm-2 9.8V4.698l5.803 3.546L0 11.801Zm6.761-2.97-6.57 4.026A2 2 0 0 0 2 14h6.256A4.493 4.493 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586l-1.239-.757ZM16 9.671V4.697l-5.803 3.546.338.208A4.482 4.482 0 0 1 12.5 8c1.414 0 2.675.652 3.5 1.671Z"/>
            <path d="M15.834 12.244c0 1.168-.577 2.025-1.587 2.025-.503 0-1.002-.228-1.12-.648h-.043c-.118.416-.543.643-1.015.643-.77 0-1.259-.542-1.259-1.434v-.529c0-.844.481-1.4 1.26-1.4.585 0 .87.333.953.63h.03v-.568h.905v2.19c0 .272.18.42.411.42.315 0 .639-.415.639-1.39v-.118c0-1.277-.95-2.326-2.484-2.326h-.04c-1.582 0-2.64 1.067-2.64 2.724v.157c0 1.867 1.237 2.654 2.57 2.654h.045c.507 0 .935-.07 1.18-.18v.731c-.219.1-.643.175-1.237.175h-.044C10.438 16 9 14.82 9 12.646v-.214C9 10.36 10.421 9 12.485 9h.035c2.12 0 3.314 1.43 3.314 3.034v.21Zm-4.04.21v.227c0 .586.227.8.581.8.31 0 .564-.17.564-.743v-.367c0-.516-.275-.708-.572-.708-.346 0-.573.245-.573.791Z"/>
        </svg>
        <a href="mailto:jugend@kolping-schorndorf.de" class="ctext pe-1">jugend@kolping-schorndorf.de</a>
    </div>
</header>

<body>
<div class="modal fade" id="cookieModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cookieModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content cbg">
            <div class="modal-header cbg">
                <h4 class="modal-title ctext fw-bold" id="cookieModalLabel">Mhhh Lecker &#x1F36A;!</h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body ctext cbg fw-normal">
                <div class="px-2">
                    <p>Wir nutzen Cookies auf unserer Webseite.<br>
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
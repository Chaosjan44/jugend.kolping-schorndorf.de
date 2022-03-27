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
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <script src="/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="/css/dark.css" disabled>
    <link rel="stylesheet" href="/css/light.css" disabled>
    <script src="/js/custom.js"></script>
    <link rel="icon" type="image/svg" href="favicon.svg" sizes="1024x1024" />
    <link rel="stylesheet" href="/css/bootstrap-icons.css">
    <!-- <link rel="stylesheet" href="/css/cookiebanner.css"> -->
    <title>Kolpingjugend Schorndorf</title>
</head>
<body style="padding-right: 0px !important;">

<nav class="navbar header-header navbar-expand-lg navbar-<?php print(check_style());?> cbg ctext sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <img src="/favicon<?php if (check_style() == "dark") { print("_dark"); }?>.svg" class="navbar-icon d-inline-block align-text-center pe-2">
            <?php if (!isMobile()) {print("Kolpingjugend Schorndorf");} else {print("KJ Schorndorf");}?>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse cbg" tabindex="-1" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link ctext" aria-current="page" href="/termine.php">Termine</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle ctext" id="offcanvasNavbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Über uns</a>
                    <ul class="dropdown-menu cbg2" aria-labelledby="offcanvasNavbarDropdown">
                        <li><a class="dropdown-item ctext" href="/about_us/wir.php">Wir</a></li>
                        <li><a class="dropdown-item ctext" href="/about_us/aktionen.php">Aktionen</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link ctext" href="#">Angebote</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="kolping-orange" style="height: 10px;"></div>
</nav>

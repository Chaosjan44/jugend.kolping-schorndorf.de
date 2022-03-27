<?php require_once("templates/header.php"); ?>
<script src="/js/slider.js"></script>
<div class="container-fluid px-0">
    <div id="carouselExampleFade" class="carousel carousel-dark slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="/media/wir.jpg" class="d-block w-100" style="height: 450px; object-fit: cover; object-position: 50% 50%;" data-bs-interval="5000">
            </div>
            <div class="carousel-item">
                <img src="/media/Vorgarten.jpg" class="d-block w-100" style="height: 450px; object-fit: cover; object-position: 50% 40%;" data-bs-interval="5000">
            </div>
            <div class="carousel-item">
                <img src="/media/jummysoup.jpg" class="d-block w-100" style="height: 450px; object-fit: cover; object-position: 50% 40%;" data-bs-interval="5000">
            </div>
        </div>
        <button class="carousel-control-prev justify-content-start px-3" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Letztes</span>
        </button>
        <button class="carousel-control-next justify-content-end px-3" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Nächstes</span>
        </button>
    </div>

</div>



<h1 class="text-center">Test</h1>





<?php require_once("templates/footer.php"); ?>
<?php require_once("templates/header.php"); ?>
<script src="/js/slider.js"></script>

<div class="container-fluid px-0 pt-0 pb-3">
    <div class="mb-3">
        <div id="carouselExampleFade" class="carousel <?php if (check_style() == "dark") { print("carousel-dark "); }?>slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="/media/wirn.jpg" class="d-block w-100" style="height: 450px; object-fit: cover; object-position: 50% 40%;" data-bs-interval="5000">
                </div>
                <div class="carousel-item">
                    <img src="/media/Vorgarten.jpg" class="d-block w-100" style="height: 450px; object-fit: cover; object-position: 50% 38%;" data-bs-interval="5000">
                </div>
                <div class="carousel-item">
                    <img src="/media/jummysoup.jpg" class="d-block w-100" style="height: 450px; object-fit: cover; object-position: 50% 48%;" data-bs-interval="5000">
                </div>
                <div class="carousel-item">
                    <img src="/media/karten1.jpg" class="d-block w-100" style="height: 450px; object-fit: cover; object-position: 50% 22%;" data-bs-interval="5000">
                </div>
                <div class="carousel-item">
                    <img src="/media/karten2.jpg" class="d-block w-100" style="height: 450px; object-fit: cover; object-position: 50% 65%;" data-bs-interval="5000">
                </div>
            </div>
            <button class="carousel-control-prev justify-content-start px-3" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" style="width: 60px; height: 60px" aria-hidden="true"></span>
            <span class="visually-hidden">Letztes</span>
            </button>
            <button class="carousel-control-next justify-content-end px-3" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
            <span class="carousel-control-next-icon" style="width: 60px; height: 60px" aria-hidden="true"></span>
            <span class="visually-hidden">Nächstes</span>
            </button>
        </div>
    </div>
    <div class="container-xxl">
        <div class="row gx-5">
            <div class="col d-flex justify-content-center">
            One of two columns
            </div>
            <div class="col d-flex justify-content-center">
            One of two columns
            </div>
        </div>
    </div>
</div>





<?php require_once("templates/footer.php"); ?>



<?php
$vernum = "0.0.1";
# Like this, cause we want the Date the current Version was developed, not the current dates
$verdate ="24.03.2022";
#$verdate = date("d.m.Y");
?>



<footer class="container-fluid sticky-bottom footer py-3 kolping-orange text-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-2">
                <ul>
                    <li>
                        <a>> Start</a>
                    </li>
                    <li>
                        <a>> Termine</a>
                    </li>
                    <li>
                        <a>> Angebote</a>
                    </li>
                </ul>
            </div>
            <div class="col-2">
                <ul>
                    <li>
                        <a></a>
                    </li>
                    <li>
                        <a>> Über uns</a>
                    </li>
                </ul>
            </div>
            <div class="col-2">
                <ul>
                    <li>
                        <a>> Datenschutz</a>
                    </li>
                    <li>
                        <a>> Impressum</a>
                    </li>
                    <li>
                        <a>> Kontakt</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row justify-content-end">
        <div class="col-4 text-center">&copy; 2020 Kolpingfamilie Schorndorf e.V.</div>
        <div class="col-4 d-flex justify-content-end">
                <input onchange="toggleStyle()" class="styleswitcher" type="checkbox" name="switch" id="style_switch" <?php if (check_style() == "dark") {print("checked");}?>>
                <label class="styleswitcherlabel" for="style_switch"></label>
            </div>
    </div>
</footer>



<!-- Bootstrap JS Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
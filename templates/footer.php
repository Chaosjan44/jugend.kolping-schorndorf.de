


<?php
$vernum = "0.0.1";
# Like this, cause we want the Date the current Version was developed, not the current dates
$verdate ="24.03.2022";
#$verdate = date("d.m.Y");
?>



<footer class="container-fluid sticky-bottom footer py-3 kolping-orange text-white">
    <div class="row">
        <div class="col">

        </div>
        <div class="col">

        </div>
        <div class="col">
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
    <div class="row">
        <span class="text-center">&copy; 2020 Kolpingfamilie Schorndorf e.V.</span>
        <div class="">
            <input onchange="toggleStyle()" class="styleswitcher" type="checkbox" name="switch" id="style_switch" <?php if (check_style() == "dark") {print("checked");}?>>
            <label class="styleswitcherlabel" for="style_switch"></label>
        </div>
    </div>
</footer>



<!-- Bootstrap JS Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
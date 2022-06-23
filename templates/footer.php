<?php 
$crdate = "2022";
?>

<?php if (!isMobile()): ?>
    <footer class="container-fluid cbg2 footer py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-2 text-center">
                    <ul class="px-0">
                        <li>
                            <a href="/" class="link ctext">Start</a>
                        </li>
                        <li>
                            <a href="/termine.php" class="link ctext">Termine</a>
                        </li>
                        <li>
                            <a href="/blogs.php" class="link ctext">Nachrichten</a>
                        </li>
                    </ul>
                </div>
                <div class="col-2 text-center">
                    <ul class="px-0">
                        <li>
                            <a href="/disclaimer.php" class="link ctext">Disclaimer</a>
                        </li>
                        <li>
                            <a href="/impressum.php" class="link ctext">Impressum</a> 
                        </li>
                        <li>
                            <a href="/datenschutz.php" class="link ctext">Datenschutz</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row justify-content-end align-items-center">
            <div class="col-4 text-center ctext light"><a href="/admin.php" class="text-center ctext light">&copy; <?=$crdate?> Kolpingfamilie Schorndorf e.V.</a></div>
            <div class="col-4 d-flex justify-content-end">
                <input onchange="toggleStyle()" class="styleswitcher" type="checkbox" name="switch" id="style_switch" <?php if (check_style() == "dark"): print("checked"); endif; ?> >
                <label class="styleswitcherlabel" for="style_switch"></label>
            </div>
        </div>
    </footer>
<?php else: ?>
    <footer class="container-fluid cbg2 footer py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col text-center px-0">
                    <ul class="px-0">
                        <li>
                            <a href="/" class="link ctext">Start</a>
                        </li>
                        <li>
                            <a href="/termine.php" class="link ctext">Termine</a>
                        </li>
                        <li>
                            <a href="/blogs.php" class="link ctext">Nachrichten</a>
                        </li>
                        <li>
                            <a href="/datenschutz.php" class="link ctext">Datenschutz</a>
                        </li>
                        <li>
                            <a href="/disclaimer.php" class="link ctext">Disclaimer</a>
                        </li>
                        <li>
                            <a href="/impressum.php" class="link ctext">Impressum</a> 
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row justify-content-end align-items-center">
            <div class="col-4 text-center ctext light"><a href="/admin.php" class="text-center ctext light">&copy; <?=$crdate?><br>Kolpingfamilie<br>Schorndorf e.V.</a></div>
            <div class="col-4 d-flex justify-content-end">
            <input onchange="toggleStyle()" class="styleswitcher" type="checkbox" name="switch" id="style_switch" <?php if (check_style() == "dark"): print("checked"); endif; ?> >
                <label class="styleswitcherlabel" for="style_switch"></label>
            </div>
        </div>
    </footer>
<?php endif; ?>

<script src="/js/custom.js"></script>
</body>
</html>
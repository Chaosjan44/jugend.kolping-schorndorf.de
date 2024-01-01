<?php 
$crdate = "2023";
?>
</body>
<?php if (!isMobile()): ?>
    <footer class="container-fluid cbg3 py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-2 text-start">
                    <ul class="px-0">
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="/" class="link ctext ps-2">Start</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="/termine.php" class="link ctext ps-2">Termine</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="/nachrichten.php" class="link ctext ps-2">Nachrichten</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="/wir.php" class="link ctext ps-2">Wir</a>
                        </li>
                    </ul>
                </div>
                <div class="col-2 text-start">
                    <ul class="px-0">
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="/disclaimer.php" class="link ctext ps-2">Disclaimer</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="/impressum.php" class="link ctext ps-2">Impressum</a> 
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="/datenschutz.php" class="link ctext ps-2">Datenschutz</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="/internal.php" class="link ctext ps-2">Intern</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-4 text-start"><a href="https://github.com/chaosjan44/jugend.kolping-schorndorf.de" target="#" class="me-2"><i class="bi ctext text-size-x-large bi-github"></i></a><a href="https://www.instagram.com/kolpingjugend.schorndorf/" target="#" class="ms-2"><i class="bi ctext text-size-x-large bi-instagram"></i></a></div>
            <div class="col-4 text-center ctext light"><a href="/internal.php" class="text-center ctext light">&copy; <?=$crdate?> Kolpingsfamilie Schorndorf e.V.</a></div>
            <div class="col-4 d-flex justify-content-end">
                <input onchange="toggleStyle()" class="styleswitcher" type="checkbox" name="switch" id="style_switch" <?php if (check_style() == "dark"): print("checked"); endif; ?> >
                <label class="styleswitcherlabel" for="style_switch"></label>
            </div>
        </div>
    </footer>
<?php else: ?>
    <footer class="container-fluid cbg3 py-3">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-6 text-start ps-2">
                    <ul class="px-0">
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="/" class="link ctext ps-2">Start</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="/termine.php" class="link ctext ps-2">Termine</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="/nachrichten.php" class="link ctext ps-2">Nachrichten</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="/wir.php" class="link ctext ps-2">Wir</a>
                        </li>
                    </ul>
                </div>
                <div class="col-6 text-start ps-2">
                    <ul class="px-0">
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="/disclaimer.php" class="link ctext ps-2">Disclaimer</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="/impressum.php" class="link ctext ps-2">Impressum</a> 
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="/datenschutz.php" class="link ctext ps-2">Datenschutz</a>
                        </li>
                        <li>
                            <i class="bi bi-chevron-right"></i>
                            <a href="/internal.php" class="link ctext ps-2">Intern</a>
                        </li>
                    </ul>
                </div>
                <div class="row justify-content-between align-items-center">
                    <div class="col-3 text-start px-0"><a href="https://github.com/chaosjan44/jugend.kolping-schorndorf.de" target="#" class="me-2"><i class="bi ctext text-size-x-large bi-github"></i></a><a href="https://www.instagram.com/kolpingjugend.schorndorf/" target="#" class="ms-2"><i class="bi ctext text-size-x-large bi-instagram"></i></a></div>
                    <div class="col-6 ctext text-start light ps-0"><a href="/internal.php" class="ctext light">&copy; <?=$crdate?> Kolpingsfamilie Schorndorf e.V.</a></div>
                    <div class="col-3 d-flex justify-content-end">
                        <input onchange="toggleStyle()" class="styleswitcher" type="checkbox" name="switch" id="style_switch" <?php if (check_style() == "dark"): print("checked"); endif; ?> >
                        <label class="styleswitcherlabel" for="style_switch"></label>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<?php endif; ?>

<script src="/js/custom.js"></script>

</html>
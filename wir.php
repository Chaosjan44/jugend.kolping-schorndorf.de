<?php 
chdir ($_SERVER['DOCUMENT_ROOT']);
ob_start();
require_once("templates/header.php"); 
$buffer=ob_get_contents();
ob_end_clean();

$title = "Kolpingjugend Schorndorf - Wir sind die Kolpingjugend Schorndorf";
$buffer = preg_replace('/(<title>)(.*?)(<\/title>)/i', '$1' . $title . '$3', $buffer);
echo $buffer;
?>
<div class="container py-3">
    <div style="min-height: 80vh;">
        <h1 class="display-3 text-center mb-3 text-kolping-orange">Wir</h1>
        <div class="text-start">
            <span class="text-size-larger">
                Die Kolpingjugend Schorndorf wurde 2019 von uns Gruppenleitenden wiedergegründet.<br>
                Seitdem treffen wir uns in regelmäßigen Abständen als Jugendgruppe in unserem Kolpinghaus in Schorndorf.<br>
                In unseren Gruppenstunden spielen wir Spiele, kochen zusammen, backen zusammen, machen Ausflüge und haben einfach eine Menge Spaß.<br>
                Seit unserer Wiedergründung renovieren wir zudem unseren Jugendraum und machen ihn <a href="https://www.instagram.com/explore/tags/kolpingfresh/" class="text-size-large link">#kolpingfresh</a>.<br>
                <br>
                Zusätzlich zu unseren Gruppenstunden nehmen wir auch an Aktivitäten der <a href="https://jugend.kolping-dvrs.de" class="text-size-large link"> Kolpingjugend Diözesanverband Rottenburg-Stuttgart</a> teil.<br>
                Dort treffen wir andere freshe Kolpingjugenden, lernen Neues und haben natürlich auch viel Spaß.<br>
                Mehrere Jugendliche unserer Kolpingjugend sind außerdem ehrenamtlich auf Diözesanebene tätig und planen Aktionen wie das Jugendfestival mit.<br>
                <!-- Bild von uns allen -->
            </span>
        </div>
    </div>
</div>
<?php require_once("templates/footer.php"); ?>
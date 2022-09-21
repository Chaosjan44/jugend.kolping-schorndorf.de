<?php 
require_once("php/functions.php");
$user = check_user();
if ($user == false) {
    print("<script>location.href='login.php'</script>");
}
// error_log(print_r($user,true));
require_once("templates/header.php"); ?>

<div class="container py-3">
    <div style="min-height: 80vh;">
        <div class="card cbg2 my-3 py-3 px-3">
            <div class="card-body text-center">
                <h1 class="card-title display-3 text-center mb-4 text-kolping-orange">Interner Bereich</h1>
                <?php if (!isMobile()): ?>
                    <div class="card-text">
                        <?php if ($user['perm_admin'] == "1"): ?>
                            <button class="btn btn-kolping mx-1" type="button" onclick="window.location.href = '/internal/user.php';">User</button>
                        <?php endif; ?>
                        <?php if ($user['perm_blog'] == "1"): ?>
                            <button class="btn btn-kolping mx-1" type="button" onclick="window.location.href = '/internal/blog.php';">Blogs</button>
                        <?php endif; ?>
                        <?php if ($user['perm_event'] == "1"): ?>
                            <button class="btn btn-kolping mx-1 my-2" type="button" onclick="window.location.href = '/internal/termine.php';">Termine</button>
                        <?php endif; ?>
                        <button class="btn btn-kolping mx-1 my-2" type="button" onclick="window.location.href = 'https://cloud.kolping-schorndorf.de';">Cloud (Bilder usw.)</button>
                        <button class="btn btn-kolping mx-1 my-2" type="button" onclick="window.location.href = '/internal/settings.php';">Einstellungen</button>
                        <button class="btn btn-kolping mx-1 my-2" type="button" onclick="window.location.href = '/internal/logout.php';">Logout</button>
                    </div>
                <?php else: ?>
                    <div class="card-text my-2">
                        <?php if ($user['perm_admin'] == "1"): ?>
                            <button class="btn btn-kolping mx-1" type="button" onclick="window.location.href = '/internal/user.php';">User</button>
                        <?php endif; ?>
                        <?php if ($user['perm_blog'] == "1"): ?>
                            <button class="btn btn-kolping mx-1" type="button" onclick="window.location.href = '/internal/blog.php';">Blogs</button>
                        <?php endif; ?>
                    </div>
                    <?php if ($user['perm_event'] == "1"): ?>
                        <button class="btn btn-kolping mx-1 my-2" type="button" onclick="window.location.href = '/internal/termine.php';">Termine</button>
                    <?php endif; ?>
                        <button class="btn btn-kolping mx-1 my-2" type="button" onclick="window.location.href = 'https://cloud.kolping-schorndorf.de';">Cloud (Bilder usw.)</button>
                    <div class="card-text">
                        <button class="btn btn-kolping mx-1 my-2" type="button" onclick="window.location.href = '/internal/settings.php';">Einstellungen</button>
                        <button class="btn btn-kolping mx-1 my-2" type="button" onclick="window.location.href = '/internal/logout.php';">Logout</button>
                    </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>





<?php require_once("templates/footer.php"); ?>
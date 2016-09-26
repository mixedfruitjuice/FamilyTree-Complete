<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>control panel</title>
    </head>
    <body>
        <?php
        include "navbar.php";
        ?>
        <div class="container">
            <div class="col-md-12">
                <p>Welkom op het admin panel</p>
            </div>
        </div>
    </body>
</html>



<?php include "bootstrap.php"; ?>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">

        <a href="index.php" class="navbar-brand">Control panel</a>

        <button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <div class="collapse navbar-collapse navHeaderCollapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="family.php">Familie leden</a></li>
                <li><a href="verhalen.php">Verhalen</a></li>
                <li><a href="logout.php" name="logout" value="logout">log uit</a></li>
            </ul>
        </div>
    </div>
</nav>

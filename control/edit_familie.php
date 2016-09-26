<?php
ob_start();
include "../includes/db.php";
include "is_logged_in.php";

$sql = "SELECT * FROM persoon WHERE id = ?";
$prepare = $handler->prepare($sql);
$prepare->execute(array($_GET['familie_id']));

$sql4 = "SELECT * FROM persoon";
$personen = $handler->prepare($sql4);
$personen->execute();
$fetch = $personen->fetchAll();


?>

<!DOCTYPE html>
<html>
    <head>
        <title>Bewerk familie lid</title>
    </head>
    <body>
        <?php include "navbar.php"; ?>
        <div class="container">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <form method="post">
                        <div id="add_form" class="form-group">
                            <?php foreach ($prepare->fetchAll() as $familie):
                            $sql5 = "SELECT * FROM persoon WHERE id = ?";
                            $fetch2 = $handler->prepare($sql5);
                            $fetch2->execute(array($familie['vader_id']));
                            $vader = $fetch2->fetchAll();

                            $fetch2->execute(array($familie['moeder_id']));
                            $moeder = $fetch2->fetchAll();

                            $fetch2->execute(array($familie['partner_id']));
                            $partner = $fetch2->fetchAll();

                                ?>
                                <div class="form-group">
                                    <label>Voornaam:</label>
                                    <input type="text" class="form-control" id="voornaam" value="<?= $familie['voornaam']; ?>" name="voornaam" placeholder="Voornaam">
                                </div>
                                <div class="form-group">
                                    <label>Achternaam:</label>
                                    <input type="text" class="form-control" id="achternaam" value="<?= $familie['achternaam']; ?>" name="achternaam" placeholder="Achternaam">
                                </div>
                                <div class="form-group">
                                    <label>Geboortedatum:</label>
                                    <input type="text" class="form-control" id="geboorte" value="<?= $familie['geboortedatum']; ?>" name="geboortedatum" placeholder="Geboortedatum (dd-mm-jjjj) (niet verplicht)">
                                </div>
                                <div class="form-group">
                                    <label>Moeder:</label>
                                    <select id="moeder" class="form-control" name="moeder">
                                        <?php foreach ($moeder as $familie3) { ?>
                                            <option value="<?= $familie3['id'] ?>" selected><?= $familie3['voornaam'] . " " . $familie3['achternaam']; ?></option>
                                        <?php } ?>
                                        <?php foreach ($fetch as $persoon) { ?>
                                            <option value="<?= $persoon['id'] ?>"><?= $persoon['voornaam'] . " " . $persoon['achternaam']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Vader</label>
                                    <select id="vader" class="form-control" name="vader">
                                        <?php foreach ($vader as $familie3) { ?>
                                            <option value="<?= $familie3['id'] ?>" selected><?= $familie3['voornaam'] . " " . $familie3['achternaam']; ?></option>
                                        <?php } ?>
                                        <?php foreach ($fetch as $persoon2) { ?>
                                            <option value="<?= $persoon2['id'] ?>"><?= $persoon2['voornaam'] . " " . $persoon2['achternaam']; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                                <div class="form-group">
                                    <label>Partner</label>
                                    <select id="partner" class="form-control" name="partner">
                                        <?php foreach ($partner as $familie3) { ?>
                                            <option value="<?= $familie3['id'] ?>" selected><?= $familie3['voornaam'] . " " . $familie3['achternaam']; ?></option>
                                        <?php } ?>
                                        <?php foreach ($fetch as $persoon2) { ?>
                                            <option value="<?= $persoon2['id'] ?>"><?= $persoon2['voornaam'] . " " . $persoon2['achternaam']; ?></option>
                                        <?php } ?>
                                    </select>

                                </div>
                            <?php endforeach; ?>
                            <button class="btn btn-default" type="submit" name="opslaan">Wijzigingen opslaan</button>
                            <button class="btn btn-danger" type="submit" name="verwijderen">Verwijderen</button>
                        </div>
                    </form>
                    <?php


                    if (isset($_POST['opslaan'])) {
                        $voornaam = $_POST['voornaam'];
                        $achternaam = $_POST['achternaam'];
                        $geboorte = $_POST['geboortedatum'];

                        $sql2 = "UPDATE persoon SET voornaam = :voornaam, achternaam = :achternaam, geboortedatum = :geboortedatum, vader_id = :vader_id, moeder_id = :moeder_id, partner_id = :partner_id WHERE id = :persoon_id";
                        $pd = $handler->prepare($sql2);
                        $pd->execute(array(':voornaam' => $voornaam,
                                           ':achternaam' => $achternaam,
                                           ':geboortedatum' => $geboorte,
                                           ':vader_id' => $_POST['vader'],
                                           ':moeder_id' => $_POST['moeder'],
                                           ':partner_id' => $_POST['partner'],
                                           ':persoon_id' => $_GET['familie_id']
                                       ));
                        header("Location: family.php");

                    }

                    if (isset($_POST['verwijderen'])) {
                        $sql3 = "DELETE FROM persoon WHERE id = ?";
                        $pd = $handler->prepare($sql3);
                        $pd->execute(array($_GET['familie_id']));
                        header('Location: family.php');
                    }
                    ?>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </body>
</html>

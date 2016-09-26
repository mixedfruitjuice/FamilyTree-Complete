<?php
    include "../includes/db.php";
    include "is_logged_in.php";
    if (isset($_POST['voegtoe'])) {
        $voornaam = $_POST["voornaam"];
        $achternaam = $_POST["achternaam"];
        $geboortedatum = $_POST["geboortedatum"];
        if (empty($voornaam) || empty($achternaam)) {
?>
    <h4 id="error">voer aub alle benodigde velden in</h4>
<?php
        } else {
            $sql = $handler->prepare('INSERT INTO persoon (voornaam, achternaam, geboortedatum, vader_id, moeder_id, partner_id) VALUES (?, ?, ?, ?, ?, ?)');
            $sql->execute(array($voornaam, $achternaam, $geboortedatum, $_POST['vader'], $_POST['moeder'], $_POST['partner']));
            header("Location: family.php");
        }
    }

    $sql2 = "SELECT * FROM persoon";
    $fetch = $handler->prepare($sql2);
    $fetch->execute();
    $fetch2 = $fetch->fetchAll();

 ?>
<!DOCTYPE html>
<html>
    <head>
        <title>voeg familielid toe</title>
    </head>
    <body>
    <?php include "navbar.php"; ?>
        <div class="add_form">
            <form method="post">
                <div id="add_form" class="form-group">
                    <label>Voornaam:</label>
                    <input type="text" class="form-control" id="Voornaam" name="voornaam">
                    <label>Achternaam:</label>
                    <input type="text" class="form-control" id="Voornaam" name="achternaam">
                    <label>Geboortejaar - Sterftejaar:</label>
                    <input type="text" class="form-control" id="Voornaam" name="geboortedatum">
                    <label>Vader:</label>
                    <select id="vader" class="form-control" name="vader">
                        <option disabled selected hidden></option>
                        <?php foreach ($fetch2 as $persoon2) { ?>
                            <option value="<?= $persoon2['id'] ?>"><?= $persoon2['voornaam'] . " " . $persoon2['achternaam']; ?></option>
                        <?php } ?>
                    </select>
                    <label>Moeder:</label>
                    <select id="moeder" class="form-control" name="moeder">
                        <option disabled selected hidden></option>
                        <?php foreach ($fetch2 as $persoon2) { ?>
                            <option value="<?= $persoon2['id'] ?>"><?= $persoon2['voornaam'] . " " . $persoon2['achternaam']; ?></option>
                        <?php } ?>
                    </select>

                    <label>Partner:</label>
                    <select id="partner" class="form-control" name="partner">
                        <option disabled selected hidden></option>
                        <?php foreach ($fetch2 as $persoon2) { ?>
                            <option value="<?= $persoon2['id'] ?>"><?= $persoon2['voornaam'] . " " . $persoon2['achternaam']; ?></option>
                        <?php } ?>
                    </select>
                    <br />
                    <button id="voegtoe" class="btn btn-default" type="submit" name="voegtoe">voeg toe</button>
                </div>
            </form>
        </div>
        <style>
            .add_form {
                width: 500px;
                margin: auto;
            }
            #voegtoe {
                text-align: center;
            }
            #error {
                text-align: center;
            }
        </style>
    </body>
</html>

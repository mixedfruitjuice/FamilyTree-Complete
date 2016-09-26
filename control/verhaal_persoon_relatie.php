<?php
include "is_logged_in.php";

include '../includes/db.php';

try {
    $verhalen = getVerhaalen();
    $personen = getPersonen();
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['verhaal']) && isset($_POST['persoon'])) {
            $form_type = 'insert';
            $sql = "INSERT INTO verhaal_persoon_relatie (verhaal_id, persoon_id) VALUES (?, ?)";
            $stmt = $handler->prepare($sql);
            $stmt->execute(array($_POST['verhaal'], $_POST['persoon']));
            $count = $stmt->rowCount();
        } elseif (isset($_POST['vpr_id'])) {
            $form_type = 'delete';
            $sql = "DELETE FROM verhaal_persoon_relatie WHERE id=?";
            $stmt = $handler->prepare($sql);
            $stmt->execute(array($_POST['vpr_id']));
            $count = $stmt->rowCount();
        }
    }
} catch (PDOException $e) {

}

function getVerhaalen() {
    global $handler;
    $sql = "SELECT * FROM verhaal";
    $stmt = $handler->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}

function getPersonen() {
    global $handler;
    $sql = "SELECT * FROM persoon";
    $stmt = $handler->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Verhaal persoon relatie</title>
        <script src="../js/jquery.js"></script>
        <script>
            $(document).ready(function () {
                $("#persoon_select").change(function () {
                    var persoon_id = $("#persoon_select option:selected").val();
                    $.post( "../ajax_pages/persoon_verhalen.php", { persoon_id: persoon_id }, function( data ) {
                        console.log(data);
                        if (data.success == true) {
                            $("#verhalen_van_titel").html("Verhalen van een specifieke persoon: <b>" + data.content[0].voornaam + " " + data.content[0].achternaam + "</b>");
                            var $result = $("#vpr_result");
                            $result.html("");
                            for(var i = 0; i < data.content.length; i++) {
                                if (data.content[i].id != null) {
                                    $result.append("<tr>" +
                                        "<td>" + data.content[i].titel + "</td>" +
                                        "<td>" + data.content[i].tekst + "</td>" +
                                        "<td><form action='' method='post'>" +
                                        "<input type='hidden' name='vpr_id' value='" + data.content[i].id + "'>" +
                                        "<input type='submit' class='btn btn-danger btn-sm' value='Verwijderen'>" +
                                        "</form></td>" +
                                        "</tr>");
                                }
                            }
                        }
                    }, 'json');
                });
            });
        </script>
    </head>
    <body>
        <?php include "navbar.php"; ?>
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-lg-8">
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            if (isset($form_type)) {
                                switch ($form_type) {
                                    case 'insert':
                                        if (isset($count) && $count >= 1) {
                                            ?>
                                            <div class="alert alert-success">
                                                <p>Succesvol de database geupdate!</p>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="alert alert-danger">
                                                <p>Kon de verhaal persoon relatie niet in de database zetten!</p>
                                            </div>
                                            <?php
                                        }
                                        break;

                                    case 'delete':
                                        if (isset($count) && $count >= 1) {
                                            ?>
                                            <div class="alert alert-success">
                                                <p>Succesvol de relatie tussen verhaal en persoon verwijderd</p>
                                            </div>
                                            <?php
                                        } else {
                                            ?>
                                            <div class="alert alert-danger">
                                                <p>Kon de relate tussen verhaal en persoon niet verwijderen</p>
                                            </div>
                                            <?php
                                        }
                                        break;
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="row">
                        <form action="" method="post">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Verhaal titel:</label>
                                    <select id="verhaal_select" class="form-control" name="verhaal">
                                        <option disabled selected hidden>Verhaal</option>
                                        <?php foreach ($verhalen as $verhaal) { ?>
                                            <option value="<?= $verhaal['id'] ?>"><?= $verhaal['titel'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Persoon:</label>
                                    <select id="persoon_select" class="form-control" name="persoon">
                                        <option disabled selected hidden>Persoon</option>
                                        <?php foreach ($personen as $persoon) { ?>
                                            <option value="<?= $persoon['id'] ?>"><?= $persoon['voornaam']. ' ' .$persoon['achternaam'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input type="submit" class="btn btn-primary" value="Toevoegen">
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4 id="verhalen_van_titel">Verhalen van een specifieke persoon:</h4>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Titel</th>
                                        <th>Tekst</th>
                                        <th>Verwijderen</th>
                                    </tr>
                                </thead>
                                <tbody id="vpr_result">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </body>
</html>

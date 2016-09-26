<?php
    include "../includes/db.php";
    include "is_logged_in.php";

    $sql = "SELECT * FROM persoon";
    $pd = $handler->prepare($sql);
    $pd->execute();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Familieleden</title>
    </head>
    <body>
        <?php include "navbar.php"; ?>
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <td>Voornaam</td>
                            <td>Achternaam</td>
                            <td>Geboortejaar - Sterftejaar</td>
                            <td>Bewerken</td>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach($pd->fetchAll() as $persoon): ?>
                                <tr>
                                    <td><?= $persoon['voornaam']; ?></td>
                                    <td><?= $persoon['achternaam']; ?></td>
                                    <td><?= $persoon['geboortedatum']; ?></td>
                                    <td>
                                        <a href="edit_familie.php?familie_id=<?= $persoon['id']?>" class="btn btn-default">Bewerken</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2">
                    <a href="add_familie.php" class="btn btn-primary" type="submit">Voeg een familie lid toe</a>
                </div>
            </div>
        </div>
    </body>
</html>

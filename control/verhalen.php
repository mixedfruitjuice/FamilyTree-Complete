<?php
include "is_logged_in.php";

include '../includes/db.php';

try {
    $sql = "SELECT * FROM verhaal";
    $stmt = $handler->prepare($sql);
    $stmt->execute();
    $verhalen = $stmt->fetchAll();
} catch (PDOException $e) {
    echo 'Something went wrong...';
    die;
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Verhalen</title>
    </head>

    <body>
        <?php
        include "navbar.php";
        ?>
        <div class="container">
            <div class="row">
                <div class="col-md-10">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>Id</th>
                            <th>Title</th>
                            <th>Aanpassen</th>
                        </tr>
                        <?php foreach ($verhalen as $verhaal) { ?>
                            <tr>
                                <td><?= $verhaal['id'] ?></td>
                                <td><?= $verhaal['titel'] ?></td>
                                <td><a class="btn btn-default" href="edit_verhaal.php?verhaal_id=<?= $verhaal['id'] ?>">Aanpassen</a></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                <div class="col-md-2">
                    <p>
                        <a href="add_verhaal.php" class="btn btn-primary">
                            Verhaal toevoegen
                        </a>
                    </p>
                    <p>
                        Verhaal aan personen linken kan <a href="verhaal_persoon_relatie.php">hier</a>.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>

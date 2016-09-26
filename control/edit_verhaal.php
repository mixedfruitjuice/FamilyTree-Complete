<?php
include "is_logged_in.php";

include '../includes/db.php';

try {

    if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['verhaal_id'])) {
        $verhaal = getVerhaal($_GET['verhaal_id']);
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verhaal_id'])) {
        if (isset($_POST['verhaal_titel']) && isset($_POST['verhaal_text'])) {
            $sql = "UPDATE verhaal SET titel=?, tekst=? WHERE id=?";
            $stmt = $handler->prepare($sql);
            $stmt->execute([
                $_POST['verhaal_titel'],
                $_POST['verhaal_text'],
                $_POST['verhaal_id']
            ]);
            $verhaal = getVerhaal($_POST['verhaal_id']);
        } else {
            $verhaal = array();
        }
    } else {
        $verhaal = array();
    }
} catch (PDOException $e) {
    echo 'Something went wrong...';
    die;
}

function getVerhaal($verhaal_id) {
    global $handler;
    $sql = "SELECT * FROM verhaal WHERE id=?";
    $stmt = $handler->prepare($sql);
    $stmt->execute([$verhaal_id]);
    return $stmt->fetchAll();
}

if (isset($_POST['verwijderen'])) {
    $sql3 = "DELETE FROM verhaal WHERE id = ?";
    $pd = $handler->prepare($sql3);
    $pd->execute(array($_GET['verhaal_id']));
    header('Location: verhalen.php');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Verhaal aanpassen</title>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/jquery.js"></script>
        <script src="../js/tinymce_dev/tinymce/js/tinymce/tinymce.min.js"></script>
        <script>
            tinymce.init({
                selector: "#verhaal_text",
                height: 150,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality emoticons template paste textcolor"
                ],
                //content_css: "../css/forum.css",
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
                style_formats: [
                    {title: 'Bold text', inline: 'b'},
                    {title: 'Red text', inline: 'span', styles: {color: '#ff0000'} },
                    {title: 'Red header', block: 'h1', styles: {color: '#ff0000'} },
                    {title: 'Example 1', inline: 'span', classes: 'example1'},
                    {title: 'Example 2', inline: 'span', classes: 'example2'},
                    {title: 'Table styles'},
                    {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
                ]
            });
        </script>
    </head>
    <body>
        <?php include "navbar.php"; ?>
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <?php
                    if (count($verhaal) == 0) { ?>
                        <div class="alert alert-danger">
                            <p>Verhaal not found. Ga terug naar <a href="login.php">control panel</a>.</p>
                        </div>
                        <?php
                    } else {
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            ?>
                            <div class="alert alert-success">
                                <p>Successvol dit verhaal aangepast!</p>
                            </div>
                            <?php
                        }
                        ?>
                        <form action="" method="post">
                            <input type="hidden" name="verhaal_id" value="<?= $verhaal[0]['id'] ?>">
                            <div class="form-group">
                                <label for="verhaalTitel">Verhaal titel</label>
                                <input type="text" class="form-control" id="verhaalTitel" name="verhaal_titel" placeholder="Titel" value="<?= $verhaal[0]['titel']; ?>">
                            </div>
                            <textarea id="verhaal_text" title="verhaal_text" name="verhaal_text">
                                <?= $verhaal[0]['tekst']; ?>
                            </textarea>
                            <input type="submit" class="btn btn-default" value="Verhaal opslaan">
                            <button class="btn btn-danger" type="submit" name="verwijderen">Verwijderen</button>
                        </form>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </body>
</html>

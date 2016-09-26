<?php
include "is_logged_in.php";
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        include '../includes/db.php';

        if (isset($_POST['verhaal_titel']) && isset($_POST['verhaal_text']) && !empty($_POST['verhaal_titel']) && !empty($_POST['verhaal_text'])) {
            $sql = 'INSERT INTO verhaal (titel, tekst) VALUES (?, ?)';
            $stmt = $handler->prepare($sql);

            if ($stmt->execute(array($_POST['verhaal_titel'], $_POST['verhaal_text']))) {
                $success_message = true;
            } else {
                $error_messages = array('Iets ging mis bij het toevoegen van het verhaal');
            }
        } else {
            $error_messages = array();
            if (empty($_POST['verhaal_titel'])) {
                $error_messages[] = 'Een verhaal titel is verplicht';
            }
            if (empty($_POST['verhaal_text'])) {
                $error_messages[] = 'Een verhaal text is verplicht';
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>FamilyTree login</title>
        <link href="../css/bootstrap/bootstrap.min.css" rel="stylesheet">
<!--        <link href="../css/style.css" rel="stylesheet">-->
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
        <style>
            .verhaal-form {
                margin-top: 50px;
            }
        </style>
    </head>
    <body>
        <?php include "navbar.php"; ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-center">Voeg een verhaal toe!</h1>
                    <p class="text-center">Of ga terug naar het <a href="login.php">control panel</a>.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8 verhaal-form">
                    <form action="" method="post">
                        <?php if (isset($error_messages)) { ?>
                            <div class="alert alert-danger">
                            <?php
                                foreach ($error_messages as $message) {
                                echo '<p>'.$message.'</p>';
                            }
                            ?>
                            </div>
                        <?php } ?>
                        <?php if (isset($success_message) && $success_message === true) {?>
                            <div class="alert alert-success">
                                De post is succesvol in de database gezet!
                            </div>
                        <?php
                        }
                        unset($_POST['verhaal_text']);
                        unset($_POST['verhaal_titel']);
                        ?>
                        <div class="form-group">
                            <label for="verhaalTitel">Verhaal titel</label>
                            <?php if (isset($_POST['verhaal_titel'])) { ?>
                                <input type="text" class="form-control" id="verhaalTitel" name="verhaal_titel" placeholder="Titel" value="<?= $_POST['verhaal_titel'] ?>">
                            <?php } else { ?>
                                <input type="text" class="form-control" id="verhaalTitel" name="verhaal_titel" placeholder="Titel">
                            <?php }?>
                        </div>
                        <textarea id="verhaal_text" title="verhaal_text" name="verhaal_text">
                            <?php if (isset($_POST['verhaal_text'])) {
                                echo $_POST['verhaal_text'];
                            }?>
                        </textarea>
                        <input type="submit" class="btn btn-default" value="Verhaal opslaan">
                    </form>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </body>
</html>

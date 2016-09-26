<?php
    include "includes/db.php";

    $sql = "SELECT verhaal.id, verhaal.titel, verhaal.tekst FROM verhaal WHERE NOT EXISTS (SELECT * FROM verhaal_persoon_relatie WHERE verhaal.id = verhaal_persoon_relatie.verhaal_id) LIMIT 2";
    $stmt = $handler->query($sql);
    $QueryData = $stmt->fetchAll();

	if (isset($_GET['zoek'])) {
        $searchvalue = $_GET['zoek'];

        $query3 = $handler->query("SELECT * FROM verhaal WHERE titel = '{$searchvalue}'");
        $QueryData3 = $query3->fetchAll();

        foreach($QueryData3 as $verhaal) {
            header("Location: verhalen.php?verhaal={$verhaal['id']}");
        }
    }
    ?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="keywords" content="FamilyTree, Gjalt, Reidsma, Gjalt Reidsma, Stamboom, Familie">
		<meta name="description" content="Gjalt Reidsma Stamboom">
		<meta name="author" content="Yannick Forget">
		<meta charset="utf-8">
        <link type="text/css" rel="stylesheet" href="css/style.css"/>
        <link rel="stylesheet" href="css/materialize.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script type="text/javascript" src="js/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
        <title>Gjalt Reidsma Verhalen</title>
    </head>
    <body>
        <div class="NavBar text-center">
            <p style="text-align: center !important;"class="Top"><a href="index.php">Gjalt Reidsma</a></p>
        </div>
        <img id="background" src="images/IMG1.jpg" />
        <div class="row">
        <div class="input-field col l5 s12 m12 zoeken2">
            <form method="get">
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">search</i>
                        <input type="text" autocomplete="off" name="zoek" id="autocomplete-input" class="autocomplete">
                        <label for="autocomplete-input">Zoek verhalen</label>

                    </div>
                </div>
				<button class="btn" name="zoeken">Zoek</button>
            </form>
            <?php
                $query2 = $handler->query("SELECT * FROM verhaal");
                $searchdata = $query2->fetchAll();
                ?>
            <script>
                $(document).ready(function(){
                	$('.autocomplete').autocomplete({
                	  data: {
                		'<?php foreach ($searchdata as $verhaal): ?>' : null,
                		'<?php echo $verhaal['titel']; ?> ': null,
                		'<?php endforeach; ?>' : null
                		},
                	});
                });
            </script>
        </div>
        <?php if($QueryData) { ?>
        <div class="col s12 m12 l5">
            <?php foreach($QueryData as $key => $verhaal) { ?>
            <div class="vak2">
                <div class="card purple darken-4">
                    <div class="card-content white-text">
                        <span class="card-title"><?= $verhaal['titel']; ?></span> <br />
                        <?= substr($verhaal['tekst'],0,300); ?>
                    </div>
                    <div class="card-action">
                        <a class="btn" href="verhalen.php?verhaal=<?= $verhaal['id']?>">Lees meer</a>
                    </div>
                </div>
            </div>
            <?php } } else { ?>
            <?php } ?>

			<?php
			if (isset($_GET['verhaal'])) {
				$verhaalid = $_GET['verhaal'];
				$stmt2 = $handler->prepare("SELECT * FROM verhaal WHERE id = ?");
				$stmt2->execute(array($verhaalid));
				$QueryData2 = $stmt2->fetchAll();?>
			<script>
			$(document).ready(function(){
				$('#verhaal').openModal();
			});
			</script>
			<?php foreach($QueryData2 as $verhaal2):  ?>
            <div id="verhaal" class="modal">
                <div class="modal-content">
                    <h4><?= $verhaal2['titel'] ?></h4>
                    <p><?= $verhaal2['tekst'] ?></p>
                </div>
                <div class="modal-footer">
                    <a href="#" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
                </div>
            </div>
		<?php endforeach; } ?>
        </div>
    </body>
</html>

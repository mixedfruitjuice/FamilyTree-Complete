

<?php
    if ($_SERVER['REQUEST_METHOD'] != 'GET') {
        die('You can only view this page with a GET request.');
    }

    include 'includes/db.php';
    include 'includes/stamboom.php';

    if (array_key_exists('persoon', $_GET)) {
        $persoon_id = $_GET['persoon'];
        $stamboom = new Stamboom($handler);
        if ($stamboom->personExists($persoon_id)) {
            $persoon = $stamboom->getPerson($persoon_id);
            $persoon_verhalen = $stamboom->getStories($persoon_id);

            $vader = $stamboom->getPerson($persoon['vader_id']);
            if ($vader !== false) {
                $vader_verhalen = $stamboom->getStories($vader['id']);
            } else {
                $vader_verhalen = array();
            }

            $moeder = $stamboom->getPerson($persoon['moeder_id']);
            if ($moeder !== false) {
                $moeder_verhalen = $stamboom->getStories($moeder['id']);
            } else {
                $moeder_verhalen = array();
            }
        } else {
            $error_message = 'Persoon niet gevonden!';
        }

        $persoon_sql = $handler->query("SELECT * FROM persoon WHERE partner_id = '{$persoon_id}' LIMIT 1");
        $persoon_partner = $persoon_sql->fetchAll();
    }

    if (isset($_GET['zoeken'])) {
        $searchvalue = $_GET['zoek'];
        $arr = explode(' ',trim($searchvalue));
        $FirstWord = $arr[0];
        $LastWord = $arr[count($arr)-1];

        $query3 = $handler->query("SELECT * FROM persoon WHERE voornaam LIKE '{$FirstWord}%' AND achternaam LIKE '%{$LastWord}%'");
        $QueryData = $query3->fetchAll();

        foreach($QueryData as $persoon) {
            header("Location: stamboom.php?persoon={$persoon['id']}");
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
        <title>Stamboom</title>
        <link rel="stylesheet" href="css/materialize.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script type="text/javascript" src="js/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
    </head>
    <body>
        <div class="NavBar center-align">
            <p class="Top"><a href="index.php">Gjalt Reidsma</a></p>
        </div>
        <img id="background" src="images/IMG1.jpg" />
        <div class="row">
            <div class="stamboom">
                <div class="input-field col l5 s12 m12 zoeken">
                    <div id="error_box">
                        <?php if (isset($error_message)) { ?>
                        <?= $error_message ?>
                        <?php } ?>
                    </div>
                    <form method="get">
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">search</i>
                                <input type="text" autocomplete="off" name="zoek" id="autocomplete-input" class="autocomplete">
                                <label for="autocomplete-input">Zoeken</label>
                            </div>
                        </div>
                        <button class="btn" name="zoeken">Zoek</button>
                    </form>
                </div>
                <?php
                    $query2 = $handler->query("SELECT * FROM persoon");
                    $searchdata = $query2->fetchAll();

                    ?>
                <script>
                    $(document).ready(function(){
                        $('.autocomplete').autocomplete({
                          data: {
                            '<?php foreach ($searchdata as $naam): ?>' : null,
                            '<?php echo $naam['voornaam'] . ' ' . $naam['achternaam']; ?> ': null,
                            '<?php endforeach; ?>' : null
                            },
                        });

                    });
                </script>
                <div class="col s12 m12 l3">
                    <div class="vak1">
                        <?php if (isset($persoon)) { ?>
                        <div class="card purple darken-4">
                            <div class="card-content white-text">
                                <span class="card-title"><?= $persoon['voornaam']. ' '. $persoon['achternaam'] ?></span>
                                <p><?= $persoon['geboortedatum']; ?> <br />
                                    Partner: <?php foreach($persoon_partner as $partner) { ?> <a href="stamboom.php?persoon=<?=$partner['id']; ?>"> <?= $partner['voornaam']. " " . $partner['achternaam']; ?> </a><?php } ?>
                                </p>
                            </div>
                            <div class="card-action">
                                <?php foreach ($persoon_verhalen as $verhaal) { ?>
                                <a class="btn" href="#" onclick="$('#verhaal_<?= $verhaal['id'] ?>').openModal()">Verhaal</a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="card purple darken-4">
                            <div class="card-content white-text">
                                <span class="card-title">Onbekend persoon</span>
                                <p>I am a very simple card. I am good at containing small bits of information.
                                    I am convenient because I require little markup to use effectively.
                                </p>
                            </div>
                            <div class="card-action">
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col s12 m12 l3">
                    <div class="vak3">
                        <?php if (isset($persoon) && isset($vader) && $vader !== false) { ?>
                        <div class="card purple darken-4">
                            <div class="card-content white-text">
                                <span class="card-title">
                                Vader: <a href="stamboom.php?persoon=<?= $vader['id'] ?>">
                                <?= $vader['voornaam']. ' ' .$vader['achternaam']?>
                                </a>
                                </span>

                                <?php
                                    $vader_sql = $handler->query("SELECT * FROM persoon WHERE partner_id = '{$vader['id']}' LIMIT 1");
                                    $vader_partner = $vader_sql->fetchAll();
                                 ?>
                                <p><?= $vader['geboortedatum'] ?> <br />
                                    Partner: <?php foreach($vader_partner as $partner) { ?> <a href="stamboom.php?persoon=<?=$partner['id']; ?>"> <?= $partner['voornaam']. " " . $partner['achternaam']; ?> </a><?php } ?>

                                </p>
                            </div>
                            <div class="card-action">
                                <?php foreach ($vader_verhalen as $verhaal) { ?>
                                <a class="btn" href="#" onclick="$('#verhaal_<?= $verhaal['id'] ?>').openModal()">Verhaal</a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="card purple darken-4">
                            <div class="card-content white-text">
                                <span class="card-title">Vader: Onbekend</span>
                                <p>I am a very simple card. I am good at containing small bits of information.
                                    I am convenient because I require little markup to use effectively.
                                </p>
                            </div>
                            <div class="card-action">
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="vak2">
                        <?php if (isset($persoon) && isset($moeder) && $moeder !== false) { ?>
                        <div class="card purple darken-4">
                            <div class="card-content white-text">
                                <span class="card-title">
                                Moeder: <a href="stamboom.php?persoon=<?= $moeder['id'] ?>">
                                <?= $moeder['voornaam']. ' ' .$moeder['achternaam']?>
                                </a>
                                </span>
                                <?php
                                    $moeder_sql = $handler->query("SELECT * FROM persoon WHERE partner_id = '{$moeder['id']}' LIMIT 1");
                                    $moeder_partner = $moeder_sql->fetchAll();
                                ?>
                                <p><?= $moeder['geboortedatum']?><br />
                                    Partner: <?php foreach($moeder_partner as $partner) { ?> <a href="stamboom.php?persoon=<?=$partner['id']; ?>"> <?= $partner['voornaam']. " " . $partner['achternaam']; ?> </a><?php } ?>
                                </p>

                            </div>
                            <div class="card-action">
                                <?php foreach ($moeder_verhalen as $verhaal) { ?>
                                <a class="btn" href="#" onclick="$('#verhaal_<?= $verhaal['id'] ?>').openModal()">Verhaal</a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } else { ?>
                        <div class="card purple darken-4">
                            <div class="card-content white-text">
                                <span class="card-title">Moeder: Onbekend</span>
                                <p>I am a very simple card. I am good at containing small bits of information.
                                    I am convenient because I require little markup to use effectively.
                                </p>
                            </div>
                            <div class="card-action">
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <?php
            if (isset($persoon)) {
                foreach ($persoon_verhalen as $verhaal) { ?>
        <div id="verhaal_<?= $verhaal['id'] ?>" class="modal">
            <div class="modal-content">
                <h4><?= $verhaal['titel'] ?></h4>
                <p><?= $verhaal['tekst'] ?></p>
            </div>
            <div class="modal-footer">
                <a href="#" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
            </div>
        </div>
        <?php
            }
            foreach ($moeder_verhalen as $verhaal) { ?>
        <div id="verhaal_<?= $verhaal['id'] ?>" class="modal">
            <div class="modal-content">
                <h4><?= $verhaal['titel'] ?></h4>

                <p><?= $verhaal['tekst'] ?></p>
            </div>
            <div class="modal-footer">
                <a href="#" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
            </div>
        </div>
        <?php
            }
            foreach ($vader_verhalen as $verhaal) { ?>
        <div id="verhaal_<?= $verhaal['id'] ?>" class="modal">
            <div class="modal-content">
                <h4><?= $verhaal['titel'] ?></h4>
                <p><?= $verhaal['tekst'] ?></p>
            </div>
            <div class="modal-footer">
                <a href="#" class=" modal-action modal-close waves-effect waves-green btn-flat">Close</a>
            </div>
        </div>
        <?php
            }
            } ?>
    </body>
</html>

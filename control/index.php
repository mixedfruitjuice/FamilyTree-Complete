<?php

session_start();
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {
    header('Location: login.php');
}

if (isset($_POST['submit'])) {
    $username = $_POST['username']; // TODO: filter
    $password = $_POST['password']; // TODO filter and password hashing using password_hash, for more info see; http://php.net/manual/en/function.password-hash.php

    try {
        $handler = new PDO("mysql:host=127.0.0.1;dbname=familytree", "root", "");
        $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
        $query = $handler->prepare($sql);

        $query->execute(array($username, $password));
        $records = $query->fetch();

        if (!empty($records)) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $username;
            header('Location: login.php');
        } else {
            $show_error = true;
        }

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>

<html>
    <head>
        <title>FamilyTree login</title>
        <link href="../css/bootstrap/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/jquery.js"></script>
    </head>
    <body>
        <div class="container">
            <div class="wrapper">
                <form action="" method="post" name="login_form" class="form-signin">
                    <?php if (isset($show_error)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <h4>verkeerde wachtwoord of gebruikersnaam</h4>
                    </div>
                    <?php } ?>
                    <h3 class="form-signin-heading">Welcome Back! Please Sign In</h3>
                    <hr class="colorgraph"><br>

                    <input type="text" class="form-control" name="username" placeholder="Username" required="" autofocus="" />
                    <input type="password" class="form-control" name="password" placeholder="Password" required=""/>

                    <button class="btn btn-lg btn-primary btn-block"  name="submit" value="Login" type="submit">Login</button>
                </form>
            </div>
        </div>
    </body>
</html>

<?php

require_once "Utils/User.php";
require_once "Utils/FileStorage.php";
require_once "Utils/UserRepository.php";

session_start();
$error = null;

$repo = new UserRepository("Data/users.json");
// exemples d'utilisation :
// $user = $repo->get($login);
// $users = $repo->getAll();


if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if ($_POST['username'] != "" || $_POST['password'] != "") {
        try {
            $user = $repo->get($_POST["username"]);
            if ($user != null && password_verify($_POST["password"], $user->password_hash)) {
                $_SESSION["user"]["login"] = $user->login;
            } else {
                $error = "Le login ou le mot de passe est incorrect";
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    } else {
        $error = "Veuillez saisir le nom d'utilisateur et le password";
    }


}


?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Public/style.css">

    <title>Connexion</title>
</head>
<body>
    <?php if (empty($_SESSION)) { ?>
        <form action="" method="POST" class="loginForm">
            <h2>Connexion</h2>
            <input type="text" name="username" placeholder="Nom d'utilisateur" required autocomplete="off">
            <input type="password" name="password" placeholder="Mot de passe" required autocomplete="off">

            <?php if (!empty($error)) { ?>
                <div class="error"><?php echo $error; ?></div>
            <?php } ?>

            <button type="submit">Se connecter</button>
        </form>
    <?php } else { ?>
        <h1>Welcome <?php echo $_SESSION['user']['login'] ?> !</h1>
        <a href="dashboard.php">Vers la Ferme</a>
    <?php } ?>


</body>
</html>
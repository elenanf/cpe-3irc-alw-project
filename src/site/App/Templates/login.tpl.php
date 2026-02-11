<!DOCTYPE html>
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
    <a href="/dashboard">Vers la Ferme</a>
<?php } ?>


</body>
</html>
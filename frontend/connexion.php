<?php require_once('config.php') ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/script_connexion.js"></script>
</head>
<body>
    <?php require_once('header_template_creation.php') ?>
    <div class="container">
        <h2>Connexion</h2>
        <form id="connexionForm" action="" onsubmit="onFormSubmit(event)">
            <div class="form-group">
                <label for="login">Nom d'utilisateur :</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Se connecter</button>
        </form>
    </div>
    <div class="container" style="margin-top: 20px;">
        <p>Vous n'avez pas de compte ? <a href="inscription.php">Créer un compte</a></p>
    </div>
</body>
</html>
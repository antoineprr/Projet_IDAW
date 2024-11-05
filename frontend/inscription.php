<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/script_inscription.js"></script>
</head>
<body>
    <?php
    require_once('header_template_creation.php');
    require_once('config.php');
    ?>
    <div class="container">
        <h2>Créer un compte</h2>
        <form id="inscriptionForm" action="" onsubmit="onFormSubmit(event)">
            <div class="form-group">
                <label for="login">Nom d'utilisateur :</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="date_naissance">Date de naissance :</label>
                <input type="date" id="date_naissance" name="date_naissance" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <label for="tranche_age">Tranche d'âge :</label>
                <select id="tranche_age" name="tranche_age" class="form-control" required>
                    <option value="">Sélectionnez votre tranche d'âge</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pratique_sport">Pratique sportive :</label>
                <select id="pratique_sport" name="pratique_sport" class="form-control" required>
                    <option value="">Sélectionnez votre degré de pratique sportive</option>
                </select>
            </div>
            <div class="form-group">
                <label for="sexe">Sexe :</label>
                <select id="sexe" name="sexe" class="form-control" required>
                    <option value="">Sélectionnez votre sexe</option>
                </select>
            </div>
            <button type="submit">Créer un compte</button>
        </form>
    </div>
    <div class="container" style="margin-top: 20px;">
        <p>Vous avez déjà un compte ? <a href="connexion.php">Se connecter</a></p>
    </div>
</body>
</html>
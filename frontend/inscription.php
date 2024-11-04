<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php require_once('header_template_creation.php') ?>
    <div class="container">
        <h2>Créer un compte</h2>
        <form id="inscriptionForm" action="" onsubmit="onFormSubmit(event)">
            <div class="form-group">
                <label for="login">Nom d'utilisateur :</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit">Créer un compte</button>
        </form>
    </div>
    <div class="container" style="margin-top: 20px;">
        <p>Vous avez déjà un compte ? <a href="connexion.php">Se connecter</a></p>
    </div>

    <script>
        const prefix_api = 'http://localhost/PROJET_IDAW/backend/API/'; // a mettre dans config.php
        function onFormSubmit(event) {
            // prevent the form to be sent to the server
            event.preventDefault();
            let login = $("#login").val();
            let password = $("#password").val();
            let confirmPassword = $("#confirm_password").val();

            if (password !== confirmPassword) {
                alert("Les mots de passe ne correspondent pas.");
                return;
            }

            let form = document.getElementById('inscriptionForm');
            form.reset();
            $.ajax({
                type: 'POST',
                url: prefix_api + '/utilisateurs.php',
                dataType: 'json',
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify({
                    login: login,
                    password: password
                }),
                success: function(response) {
                    console.log(response);
                    if(response.status === "success") {
                        // Ouvrir une session et stocker le login
                        $.ajax({
                            type: 'POST',
                            url: 'session.php',
                            data: { login: login },
                            success: function() {
                                window.location.href = "index.php";
                            }
                        });
                    } else {
                        alert(response.message || "Erreur lors de la création du compte");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Une erreur s'est produite lors de la création du compte.");
                }
            });
        }
    </script>
</body>
</html>
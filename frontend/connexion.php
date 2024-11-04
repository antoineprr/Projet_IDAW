<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
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

    <script>
        const prefix_api = 'http://localhost/PROJET_IDAW/backend/API/'; // a mettre dans config
        function onFormSubmit(event) {
            // prevent the form to be sent to the server
            event.preventDefault();
            let login = $("#login").val();
            let mdp = $("#password").val();
            let form = document.getElementById('connexionForm');
            form.reset();
            $.ajax({
                type: 'POST',
                url: `${prefix_api}/utilisateurs.php`,
                dataType: 'json',
                contentType: "application/json; charset=utf-8",
                data: JSON.stringify({
                    login: login,
                    password: mdp
                }),
                success: function(response) {
                    console.log(response);
                    if(response.status === "success") {
                        $.ajax({
                            type: 'POST',
                            url: 'connected.php',
                            data: { login: login },
                            success: function() {
                                window.location.href = "index.php";
                            }
                        });
                    } else {
                        alert(response.message || "Erreur de login/password");
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 401) {
                        alert("Problème de login/mot de passe.");
                    } else {
                        console.error(xhr.responseText);
                        alert("Une erreur s'est produite lors de la connexion.");
                    }
                }
            });
        }
    </script>
</body>
</html>
const prefix_api = 'http://localhost/PROJET_IDAW/backend/API/'; // a mettre dans config.php
function onFormSubmit(event) {
    // prevent the form to be sent to the server
    event.preventDefault();
    let login = $("#login").val();
    let mdp = $("#password").val();
    let form = document.getElementById('connexionForm');
    form.reset();
    $.ajax({
        type: 'POST',
        url: prefix_api + '/utilisateurs.php',
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
                    url: '../backend/connected.php',
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
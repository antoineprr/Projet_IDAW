const prefix_api = 'http://localhost/PROJET_IDAW/backend/API';
$(document).ready(function() {
    chargerDonnees();
    console.log(login);
});

function chargerDonnees() {
    $.ajax({
        type: 'GET',
        url: `${prefix_api}/utilisateurs/login/${login}`,
        dataType: 'json',
        success: function(data) {
            console.log(data);
            $("#login").val(data.login);
            $("#nom").val(data.nom);
            $("#prenom").val(data.prenom);
            $("#email").val(data.email);
            $("#date_naissance").val(data.date_naissance);
            $("#sexe").val(data.code_sexe);
            $("#pratique_sport").val(data.code_sport);
            $("#tranche_age").val(data.code_age);
        },
        error: function(xhr, status, error) {
            console.error('Erreur lors du chargement des informations :', error);
        }
    });
}

function onFormSubmit(event) {
    // Empêcher le formulaire d'être soumis au serveur
    event.preventDefault();
    let login = $("#login").val();
    let password = $("#password").val();
    let confirmPassword = $("#confirm_password").val();
    let sexe = $("#sexe").val();
    let pratique_sport = $("#pratique_sport").val();
    let tranche_age = $("#tranche_age").val();
    let nom = $("#nom").val();
    let prenom = $("#prenom").val();
    let email = $("#email").val();
    let date_naissance = $("#date_naissance").val();

    if (password !== confirmPassword) {
    alert("Les mots de passe ne correspondent pas.");
    return;
    }

    // Vérifier que les champs requis sont remplis
    if (sexe === "") {
    alert("Veuillez sélectionner votre sexe.");
    return;
    }
    if (pratique_sport === "") {
    alert("Veuillez sélectionner votre degré de pratique sportive.");
    return;
    }
    if (tranche_age === "") {
    alert("Veuillez sélectionner votre tranche d'âge.");
    return;
    }

    $.ajax({
    type: 'POST',
    url: prefix_api + '/utilisateurs.php',
    dataType: 'json',
    contentType: "application/json; charset=utf-8",
    data: JSON.stringify({
        action: 'register',
        login: login,
        mdp: password,
        nom: nom,
        prenom: prenom,
        date_naissance: date_naissance,
        email: email,
        code_sexe: sexe,
        code_sport: pratique_sport,
        code_age: tranche_age
    }),
    success: function(response) {
        console.log(response);
        if (response.status === "success") {
            $.ajax({
                type: 'POST',
                url: '../backend/connected.php',
                data: { login: login },
                success: function() {
                    window.location.href = "index.php";
                }
            });
        } else {
            alert(response.message || "Erreur lors de la création du compte.");
        }
    },
    error: function(xhr, status, error) {
        console.error(xhr.responseText);
        alert("Une erreur s'est produite lors de la création du compte.");
    }
    });
}

$(document).ready(function() {
    let prefix_api = window.prefix_api;
    chargerTranchesAge();
    chargerPratiqueSport();
    chargerSexe();
});

function chargerTranchesAge() {
    $.ajax({
        type: 'GET',
        url: prefix_api+'/tranche-age.php',
        dataType: 'json',
        success: function(data) {
            $('#tranche_age').empty();
            $('#tranche_age').append('<option value="">Sélectionnez votre tranche d\'âge</option>');
            $.each(data, function(index, item) {
                $('#tranche_age').append('<option value="' + item.CODE_AGE + '">' + item.TRANCHE + '</option>');
            });
        },
        error: function(xhr, status, error) {
            console.error('Erreur lors du chargement des tranches d\'âge :', error);
        }
    });
}

function chargerPratiqueSport() {
    $.ajax({
        type: 'GET',
        url: `${prefix_api}/pratique-sport.php`,
        dataType: 'json',
        success: function(data) {
            $('#pratique_sport').empty();
            $('#pratique_sport').append('<option value="">Sélectionnez votre degré de pratique sportive</option>');
            $.each(data, function(index, item) {
                $('#pratique_sport').append('<option value="' + item.CODE_SPORT + '">' + item.NOM_SPORT + '</option>');
            });
        },
        error: function(xhr, status, error) {
            console.error('Erreur lors du chargement des pratiques sportives :', error);
        }
    });
}

function chargerSexe() {
    $.ajax({
        type: 'GET',
        url: `${prefix_api}/sexe.php`,
        dataType: 'json',
        success: function(data) {
            $('#sexe').empty();
            $('#sexe').append('<option value="">Sélectionnez votre sexe</option>');
            $.each(data, function(index, item) {
                $('#sexe').append('<option value="' + item.CODE_SEXE + '">' + item.NOM_SEXE + '</option>');
            });
        },
        error: function(xhr, status, error) {
            console.error('Erreur lors du chargement des sexes :', error);
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
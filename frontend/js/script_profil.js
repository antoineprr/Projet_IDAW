const prefix_api = 'http://localhost/PROJET_IDAW/backend/API';
$(document).ready(function() {
    chargerTranchesAge();
    chargerPratiqueSport();
    chargerSexe();
    chargerDonnees();
    $('input, select').prop('disabled', true);

});

function chargerTranchesAge() {
    $.ajax({
        type: 'GET',
        url: `${prefix_api}/tranche-age.php`,
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

function chargerDonnees() {
    $.ajax({
        type: 'GET',
        url: `${prefix_api}/utilisateurs/login/${login}`,
        dataType: 'json',
        success: function(data) {
            if (data.length > 0) {
                let userData = data[0];
                console.log(userData);
                $("#login").val(userData.LOGIN);
                $("#nom").val(userData.NOM);
                $("#prenom").val(userData.PRENOM);
                $("#email").val(userData.EMAIL);
                $("#date_naissance").val(userData.DATE_NAISSANCE);
                $("#sexe").val(userData.CODE_SEXE);
                $("#pratique_sport").val(userData.CODE_SPORT);
                $("#tranche_age").val(userData.CODE_AGE);
            } else {
                console.error('Aucune donnée utilisateur trouvée.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erreur lors du chargement des informations :', error);
        }
    });
}

function unlockForm(button){
    $('input, select').prop('disabled', false);
    $('#login').prop('disabled', true);
    $(button).hide();
    $('#save').show();
}

function onFormSubmit(event) {
    // Empêcher le formulaire d'être soumis au serveur
    event.preventDefault();
    let sexe = $("#sexe").val();
    let pratique_sport = $("#pratique_sport").val();
    let tranche_age = $("#tranche_age").val();
    let nom = $("#nom").val();
    let prenom = $("#prenom").val();
    let email = $("#email").val();
    let date_naissance = $("#date_naissance").val();

    $.ajax({
    type: 'PUT',
    url: `${prefix_api}/utilisateurs/login/${login}`,
    dataType: 'json',
    contentType: "application/json; charset=utf-8",
    data: JSON.stringify({
        login: login,
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
            console.log('success') ;
        } else {
            alert(response.message || "Erreur lors de la mise à jour du compte.");
        }
    },
    error: function(xhr, status, error) {
        console.error(xhr.responseText);
        alert("Une erreur s'est produite lors de la mise à jour du compte.");
    }
    });
}
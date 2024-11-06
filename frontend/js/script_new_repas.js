let alimentsList = [];

// Récupérer tous les aliments depuis l'API
$.ajax({
    url: prefix_api + "/aliments.php",
    method: "GET",
    dataType: "json",
    success: function(response) {
        alimentsList = response;
    },
    error: function(error) {
        console.error("Erreur lors de la récupération des aliments :", error);
    }
});
// Script pour le bouton de création de repas
$('.create-btn').on('click', function() {
    $('#createMealFormContainer').toggle(); // Afficher ou masquer le formulaire de création de repas
});

// Ajouter un nouvel aliment
$('#addAliment').on('click', function() {
    let alimentHtml = `
        <div class="form-group">
            <div class="input-group mb-3">
                <input type="text" class="form-control col-3 aliment-search" placeholder="Rechercher un aliment" required>
                <input type="hidden" class="form-control aliment-id">
                <input type="number" class="form-control col-3 aliment-quantity" placeholder="Quantité" required>
                <div class="input-group-append">
                    <button class="btn btn-danger remove-aliment" style="margin-left: 10px" type="button">Supprimer</button>
                </div>
                <div class="dropdown-menu aliment-dropdown"></div>
            </div>
        </div>`;
    $('#alimentsContainer').append(alimentHtml);
});

// Supprimer un aliment
$(document).on('click', '.remove-aliment', function() {
    $(this).closest('.form-group').remove();
});

// Rechercher des aliments
$(document).on('input', '.aliment-search', function() {
    let searchQuery = $(this).val().toLowerCase();
    let dropdownMenu = $(this).siblings('.aliment-dropdown');
    dropdownMenu.empty();

    if (searchQuery.length > 0) {
        let filteredAliments = alimentsList.filter(aliment => aliment.NOM_ALIMENT.toLowerCase().includes(searchQuery));
        filteredAliments.forEach(aliment => {
            dropdownMenu.append(`<a class="dropdown-item" href="#" data-id="${aliment.ID_ALIMENT}">${aliment.NOM_ALIMENT}</a>`);
        });
        dropdownMenu.show();
    } else {
        dropdownMenu.hide();
    }
});

// Sélectionner un aliment
$(document).on('click', '.aliment-dropdown .dropdown-item', function(e) {
    e.preventDefault();
    let alimentName = $(this).text();
    let inputGroup = $(this).closest('.input-group');
    let searchInput = inputGroup.find('.aliment-search');


    searchInput.val(alimentName);
    $(this).closest('.aliment-dropdown').hide();
});

// Soumettre le formulaire de création de repas
$('#createMealForm').on('submit', function(e) {
    e.preventDefault();

    let mealDate = $('#mealDate').val();
    let aliments = [];

    $('.aliment-search').each(function() {
        let alimentName = $(this).val();
        let alimentQuantity = $(this).closest('.input-group').find('.aliment-quantity').val();

        if (alimentName && alimentQuantity) {
            aliments.push({
                nom_aliment: alimentName,
                quantite: alimentQuantity
            });
        } else {
            console.error('Nom de l\'aliment ou quantité indéfini pour un aliment.');
        }
    });


    let mealData = {
        login: sessionStorage.getItem('login'),
        date: mealDate
    };

    // Envoyer les données du repas à l'API pour créer le repas
    $.ajax({
        url: prefix_api + "/repas.php",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(mealData),
        success: function(response) {
            let codeRepas = response;

            // Ajouter les aliments au repas
            aliments.forEach(function(aliment) {
                let alimentData = {
                    code_repas: codeRepas,
                    nom_aliment: aliment.nom_aliment,
                    quantite: aliment.quantite
                };


                $.ajax({
                    url: prefix_api + "/repas.php",
                    method: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(alimentData),
                    success: function(response) {
                    },
                    error: function(error) {
                        console.error("Erreur lors de l'ajout de l'aliment :", error);
                    }
                });
            });

            $('#createMealFormContainer').hide();
            location.reload();
        },
        error: function(error) {
            console.error("Erreur lors de la création du repas. Infos : " + JSON.stringify(error));
        }
    });
});
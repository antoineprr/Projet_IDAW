// Script pour le bouton de création de repas
$('.create-btn').on('click', function() {
    $('#createMealFormContainer').toggle(); // Afficher ou masquer le formulaire de création de repas
});

// Ajouter un nouvel aliment
$('#addAliment').on('click', function() {
    let alimentHtml = `
        <div class="form-group">
            <div class="input-group mb-3">
                <input type="text" class="form-control aliment-name" placeholder="Nom de l'aliment" required>
                <input type="number" class="form-control aliment-quantity" placeholder="Quantité" required>
                <div class="input-group-append">
                    <button class="btn btn-danger remove-aliment" type="button">Supprimer</button>
                </div>
            </div>
        </div>`;
    $('#alimentsContainer').append(alimentHtml);
});

// Supprimer un aliment
$(document).on('click', '.remove-aliment', function() {
    $(this).closest('.form-group').remove();
});

// Soumettre le formulaire de création de repas
$('#createMealForm').on('submit', function(e) {
    e.preventDefault();

    let mealDate = $('#mealDate').val();
    let aliments = [];

    $('.aliment-name').each(function(index) {
        let alimentName = $(this).val();
        let alimentQuantity = $('.aliment-quantity').eq(index).val();
        aliments.push({
            name: alimentName,
            quantity: alimentQuantity
        });
    });

    let mealData = {
        login: sessionStorage.getItem('login'),
        date: mealDate
    };

    // Envoyer les données du repas à l'API pour créer le repas
    $.ajax({
        url: prefix_api + "/repas",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify(mealData),
        success: function(response) {
            let codeRepas = response[0].CODE_REPAS; // Supposons que la réponse contient le CODE_REPAS du repas créé

            // Ajouter les aliments au repas
            aliments.forEach(function(aliment) {
                let alimentData = {
                    code_repas: codeRepas,
                    nom_aliment: aliment.name,
                    quantite: aliment.quantity
                };

                $.ajax({
                    url: prefix_api + "/repas",
                    method: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(alimentData),
                    success: function(response) {
                        console.log("Aliment ajouté avec succès :", response);
                    },
                    error: function(error) {
                        console.error("Erreur lors de l'ajout de l'aliment :", error);
                    }
                });
            });

            alert("Repas créé avec succès !");
            $('#createMealFormContainer').hide();
            // Rafraîchir la liste des repas
            location.reload();
        },
        error: function(error) {
            alert("Erreur lors de la création du repas. Infos : " + JSON.stringify(error));
        }
    });
});
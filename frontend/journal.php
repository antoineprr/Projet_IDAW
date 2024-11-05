<?php
require_once('header_template.php');
require_once('config.php');
?>

<div class="container flex-grow-1">
    <h2>Vos repas :</h2>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Aliments</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody id="mealsTableBody">
            <!-- Les données seront insérées ici par JavaScript -->
        </tbody>
    </table>
</div>

<div class="container flex-grow-1 mt-5">
    <h2>Ratios des repas :</h2>
    <div id="ratiosContainer">
        <!-- Les ratios seront affichés ici par JavaScript -->
    </div>
</div>

<!-- Inclure les fichiers JavaScript de jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){
    let login = sessionStorage.getItem('login');
    if (!login) {
        alert("Vous n'êtes pas connecté.");
        window.location.href = "connexion.php";
        return;
    }

    let prefix_api = window.prefix_api;
    $.ajax({
        // L'URL de la requête 
        url: prefix_api + "/utilisateurs.php/repas/" + login,

        // La méthode d'envoi (type de requête)
        method: "GET",

        // Le format de réponse attendu
        dataType : "json",
    })
    // Ce code sera exécuté en cas de succès - La réponse du serveur est passée à done()
    .done(function(response){
        let tableBody = $('#mealsTableBody');
        tableBody.empty(); // Vider le tableau avant d'ajouter les nouvelles données

        // Regrouper les repas par date
        let groupedMeals = {};
        response.forEach(function(item) {
            let date = item.DATE; // Extraire la date sans l'heure
            if (!groupedMeals[date]) {
                groupedMeals[date] = [];
            }
            groupedMeals[date].push(item.NOM_ALIMENT);
        });

        // Insérer les données regroupées dans le tableau
        for (let date in groupedMeals) {
            let aliments = groupedMeals[date].join(', ');
            let row = `<tr>
                <td>${date}</td>
                <td>${aliments}</td>
                <td><button class="btn btn-primary ratio-btn" data-date="${date}">Voir les ratios</button></td>
            </tr>`;
            tableBody.append(row);
        }

        // Ajouter un gestionnaire d'événements pour les boutons "Voir les ratios"
        $('.ratio-btn').on('click', function() {
            let date = $(this).data('date');
            getRatios(date);
        });
    })
    // Ce code sera exécuté en cas d'échec - L'erreur est passée à fail()
    .fail(function(error){
        alert("La requête s'est terminée en échec. Infos : " + JSON.stringify(error));
    })
    // Ce code sera exécuté que la requête soit un succès ou un échec
    .always(function(){
        console.log("Requête effectuée");
    });
});

// Fonction pour obtenir les ratios d'un repas
function getRatios(date) {
    let login = sessionStorage.getItem('login');
    let prefix_api = window.prefix_api;
    $.ajax({
        // L'URL de la requête 
        url: prefix_api + "/utilisateurs.php/ratios_repas/" + login + "/" + date,

        // La méthode d'envoi (type de requête)
        method: "GET",

        // Le format de réponse attendu
        dataType : "json",
    })
    .done(function(response){
        console.log("Réponse de l'API pour les ratios:", response); // Log pour déboguer

        // Vider la section des ratios avant d'ajouter les nouvelles données
        let ratiosContainer = $('#ratiosContainer');
        ratiosContainer.empty();

        // Vérifier la structure de la réponse et afficher les ratios
        response.forEach(function(item) {
            if (item.RATIOS) {
                let table = `<table class="table table-bordered">
                    <thead>
                        <tr>
                            <th colspan="2">${item.NOM_ALIMENT}</th>
                        </tr>
                        <tr>
                            <th>Nom du Ratio</th>
                            <th>Quantité</th>
                        </tr>
                    </thead>
                    <tbody>`;
                
                item.RATIOS.forEach(function(ratio) {
                    table += `<tr>
                        <td>${ratio.NOM_RATIO}</td>
                        <td>${ratio.QUANTITE}</td>
                    </tr>`;
                });

                table += `</tbody></table>`;
                ratiosContainer.append(table);
            } else {
                console.error("RATIOS non définis pour l'élément:", item);
            }
        });
    })
    .fail(function(error){
        alert("La requête pour les ratios s'est terminée en échec. Infos : " + JSON.stringify(error));
    });
}
</script>

<?php
require_once('footer_template.php');
?>
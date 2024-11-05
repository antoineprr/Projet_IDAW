$(document).ready(function(){
    let login = sessionStorage.getItem('login');
    if (!login) {
        alert("Vous n'êtes pas connecté.");
        window.location.href = "connexion.php";
        return;
    }

    let prefix_api = window.prefix_api;
    let itemsPerPage = 10; // Nombre d'éléments par page
    let currentPage = 1; // Page actuelle
    let groupedMeals = []; // Variable pour stocker les repas groupés

    function renderTable(data, page) {
        let tableBody = $('#mealsTableBody');
        tableBody.empty(); // Vider le tableau avant d'ajouter les nouvelles données

        let start = (page - 1) * itemsPerPage;
        let end = start + itemsPerPage;
        let paginatedItems = data.slice(start, end);

        // Insérer les données paginées dans le tableau
        paginatedItems.forEach(function(item) {
            let codeRepas = item.CODE_REPAS;
            let date = item.DATE.split(' ')[0]; // Extraire la date sans l'heure
            let aliments = item.NOM_ALIMENTS.join(', et ');
            let row = `<tr>
                <td>${date}</td>
                <td>${aliments}</td>
                <td><button class="btn btn-primary ratio-btn" data-code-repas="${codeRepas}">Voir les ratios</button></td>
            </tr>`;
            tableBody.append(row);
        });

        // Ajouter un gestionnaire d'événements pour les boutons "Voir les ratios"
        $('.ratio-btn').on('click', function() {
            let codeRepas = $(this).data('code-repas');
            getRatios(codeRepas);
        });
    }

    function renderPagination(totalItems) {
        let paginationContainer = $('#paginationContainer');
        paginationContainer.empty(); // Vider la pagination avant d'ajouter les nouvelles données

        let totalPages = Math.ceil(totalItems / itemsPerPage);

        let pagination = `<ul class="pagination">
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${currentPage - 1}">&laquo;</a>
            </li>`;

        for (let i = 1; i <= totalPages; i++) {
            pagination += `<li class="page-item ${currentPage === i ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>`;
        }

        pagination += `<li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage + 1}">&raquo;</a>
        </li>
        </ul>`;

        paginationContainer.append(pagination);

        // Ajouter un gestionnaire d'événements pour les liens de pagination
        $('.page-link').on('click', function(e) {
            e.preventDefault();
            let page = $(this).data('page');
            if (page >= 1 && page <= totalPages) {
                currentPage = page;
                renderTable(groupedMeals, currentPage);
                renderPagination(totalItems);
            }
        });
    }

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
        // Regrouper les repas par CODE_REPAS
        let groupedMealsMap = {};
        response.forEach(function(item) {
            if (!groupedMealsMap[item.CODE_REPAS]) {
                groupedMealsMap[item.CODE_REPAS] = {
                    CODE_REPAS: item.CODE_REPAS,
                    DATE: item.DATE.split(' ')[0], // Extraire la date sans l'heure
                    NOM_ALIMENTS: []
                };
            }
            groupedMealsMap[item.CODE_REPAS].NOM_ALIMENTS.push(item.NOM_ALIMENT);
        });

        groupedMeals = Object.values(groupedMealsMap);

        let totalItems = groupedMeals.length;
        renderTable(groupedMeals, currentPage);
        renderPagination(totalItems);
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
function getRatios(codeRepas) {
    let login = sessionStorage.getItem('login');
    let prefix_api = window.prefix_api;
    $.ajax({
        // L'URL de la requête 
        url: prefix_api + "/utilisateurs.php/ratios_repas/" + login + "/" + codeRepas,

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

        // Créer des tableaux pour chaque ratio
        let table = `<div class="col-md-12">
        <h2>Ratios du repas :</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nom du Ratio</th>
                        <th>Quantité</th>
                    </tr>
                </thead>
                <tbody>`;
        
        response.forEach(function(ratio) {
            table += `<tr>
                <td>${ratio.NOM_RATIO}</td>
                <td>${ratio.QUANTITE_RATIO}</td>
            </tr>`;
        });

        table += `</tbody></table></div>`;
        ratiosContainer.append(table);
    })
    .fail(function(error){
        alert("La requête pour les ratios s'est terminée en échec. Infos : " + JSON.stringify(error));
    });
}
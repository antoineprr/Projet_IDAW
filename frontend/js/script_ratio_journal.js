$(document).ready(function(){
    let login = sessionStorage.getItem('login');
    if (!login) {
        window.location.href = "connexion.php";
        return;
    }

    let prefix_api = window.prefix_api;
    let itemsPerPage = 5; // Nombre d'éléments par page
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
        url: prefix_api + "/utilisateurs.php/repas/" + login,
        method: "GET",
        dataType : "json",
    })
    .done(function(response){
        // Regrouper les repas par CODE_REPAS
        let groupedMealsMap = {};
        response.forEach(function(item) {
            if (!groupedMealsMap[item.CODE_REPAS]) {
                groupedMealsMap[item.CODE_REPAS] = {
                    CODE_REPAS: item.CODE_REPAS,
                    DATE: item.DATE.split(' ')[0],
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
    .fail(function(error){
        console.error("La requête s'est terminée en échec. Infos : " + JSON.stringify(error));
    })
    .always(function(){
    });
});

// Fonction pour obtenir les ratios d'un repas
function getRatios(codeRepas) {
    let login = sessionStorage.getItem('login');
    let prefix_api = window.prefix_api;
    $.ajax({
        url: prefix_api + "/utilisateurs.php/ratios_repas/" + login + "/" + codeRepas,
        method: "GET",

        dataType : "json",
    })
    .done(function(response){
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
                <td>${Number(ratio.QUANTITE_RATIO).toFixed(1)}</td>
            </tr>`;
        });

        table += `</tbody></table></div>`;
        ratiosContainer.append(table);
    })
    .fail(function(error){
        console.error("La requête pour les ratios s'est terminée en échec. Infos : " + JSON.stringify(error));
    });
}
$(document).ready(function() {
    const prefix_api = window.prefix_api;
    let currentPage = 1;
    let selectedType = '';
    let limit = getLimit();
    let totalPages = loadTotalPages();
    const maxVisiblePages = 5;
    loadTypes(); 


    $('#selectLimit').on('change', function() {
        limitChange();
    });

    $('#typeFilter').on('change', function() {
        selectedType = $(this).val();
        currentPage = 1;
        limitChange();

    });

    setTimeout(function() {
        loadPage(currentPage);
    }, 100);

    function getLimit() {
        return document.getElementById("selectLimit").value;
    }

    function limitChange() {
        limit = getLimit();
        totalPages = loadTotalPages();
        setTimeout(function() {
            if (currentPage > totalPages) {
                currentPage = totalPages;
            }
            loadPage(currentPage);
        }, 100);
    }

    function loadTypes() {
        $.ajax({
            type: 'GET',
            url: `${prefix_api}/type-aliments.php`,
            dataType: 'json',
            success: function(types) {
                let typeFilter = $('#typeFilter');
                types.forEach(function(type) {
                    typeFilter.append(`<option value="${type.CODE_TYPE}">${type.NOM_TYPE.charAt(0).toUpperCase() + type.NOM_TYPE.slice(1)}</option>`);
                });
            },
            error: function(error) {
                console.error("Erreur lors du chargement des types d'aliments :", error);
            }
        });
    }

    function loadTotalPages(){
        let url = `${prefix_api}/aliments.php`;
        if(selectedType !== ''){
            url += `/${selectedType}`;
        }
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            success: function(data) {
                totalPages = Math.ceil(data.length / limit);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function loadPage(page) {
        currentPage = page;
        loadAliments(page);
    }

    function loadAliments(page) {
        $.ajax({
            type: 'GET',
            url: `${prefix_api}/aliments.php`,
            dataType: 'json',
            data: {
                page: page,
                limit: limit,
                
                type: selectedType
            },
            success: function(data) {
                let tbody = $('#aliments-table tbody');
                tbody.empty();
                $.each(data, function(index, aliment) {
                    tbody.append(`<tr>
                        <td class="col-nom-aliment">${aliment.NOM_ALIMENT}</td>
                        <td class="col-nom-type">${aliment.NOM_TYPE.charAt(0).toUpperCase() + aliment.NOM_TYPE.slice(1)}</td>
                        <td class="col-button"><button class="btn btn-primary ratio-btn btn-sm">Voir les ratios</button></td>
                        </tr>`);
                });
                $('.ratio-btn').on('click', function() {
                    let alimentName = $(this).closest('tr').find('.col-nom-aliment').text();
                    console.log(alimentName);
                    getRatios(alimentName);
                });
                updatePagination(data.totalPages);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function getRatios(aliments) {
        $.ajax({
            url: prefix_api + "/ratio.php",
            method: "GET",
            dataType: "json",
            data: { name: aliments },
            success: function(data) {
                let ratiosContainer = $('#ratiosContainer');
                ratiosContainer.empty();

                let table = `<div class="col-md-12">
                    <h2 class="text-center">${aliments}</h2>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nom du Ratio</th>
                                <th>Quantité</th>
                            </tr>
                        </thead>
                        <tbody>`;
                
                data.forEach(function(ratio) {
                    table += `<tr>
                        <td>${ratio.NOM_RATIO}</td>
                        <td>${Number(ratio.QUANTITE_RATIO).toFixed(1)}</td>
                    </tr>`;
                });
        
                table += `</tbody></table></div>`;
                ratiosContainer.append(table);

                // Afficher la fenêtre modale
                let modal = document.getElementById("ratiosModal");
                modal.style.display = "block";

                // Fermer la fenêtre modale
                let closeButtons = document.getElementsByClassName("custom-close");
                for (let i = 0; i < closeButtons.length; i++) {
                    closeButtons[i].onclick = function() {
                        modal.style.display = "none";
                    }
                }

                // Fermer la fenêtre modale en cliquant en dehors de celle-ci
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function updatePagination() {
        let pagination = $('.pagination');
        pagination.empty();

        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        let firstDisabled = currentPage === 1 ? 'disabled' : '';
        let firstButton = $(`<li class="page-item ${firstDisabled}"><a class="page-link" href="#">First</a></li>`);
        firstButton.on('click', function() {
            if (currentPage !== 1) loadPage(1);
        });
        pagination.append(firstButton);

        let prevDisabled = currentPage === 1 ? 'disabled' : '';
        let prevButton = $(`<li class="page-item ${prevDisabled}"><a class="page-link" href="#">&laquo;</a></li>`);
        prevButton.on('click', function() {
            if (currentPage > 1) loadPage(currentPage - 1);
        });
        pagination.append(prevButton);

        for (let i = startPage; i <= endPage; i++) {
            let activeClass = currentPage === i ? 'active' : '';
            let pageButton = $(`<li class="page-item ${activeClass}"><a class="page-link" href="#">${i}</a></li>`);
            pageButton.on('click', function() {
                loadPage(i);
            });
            pagination.append(pageButton);
        }

        let nextDisabled = currentPage === totalPages ? 'disabled' : '';
        let nextButton = $(`<li class="page-item ${nextDisabled}"><a class="page-link" href="#">&raquo;</a></li>`);
        nextButton.on('click', function() {
            if (currentPage < totalPages) loadPage(currentPage + 1);
        });
        pagination.append(nextButton);

        let lastDisabled = currentPage === totalPages ? 'disabled' : '';
        let lastButton = $(`<li class="page-item ${lastDisabled}"><a class="page-link" href="#">Last</a></li>`);
        lastButton.on('click', function() {
            if (currentPage !== totalPages) loadPage(totalPages);
        });
        pagination.append(lastButton);
    }

    $('#aliments-table tbody').on('dblclick', 'tr', function() {
        let alimentName = $(this).find('.col-nom-aliment').text();
        $.ajax({
            url: `${prefix_api}/aliments.php`,
            method: "GET",
            dataType: "json",
            data: { name: alimentName },
            success: function(aliment) {
                $('#alimentNom').val(aliment[0].NOM_ALIMENT);
                $('#alimentCategorie').val(aliment[0].NOM_TYPE);
                $('#deleteAliment').prop('disabled', false);
                $('#alimentModal').show();
            },
            error: function(xhr, status, error) {
                console.error("Erreur lors de la récupération de l'aliment :", error);
            }
        });
    });

    $('.custom-close').on('click', function() {
        $('#alimentModal').hide();
        $('#alimentForm')[0].reset();
        $('#deleteAliment').prop('disabled', true);
    });

    $('#alimentForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: `${prefix_api}/aliments.php`,
            method: "PUT",
            contentType: "application/json",
            data: JSON.stringify({
                name: $('#alimentNom').val(),
                type: $('#alimentCategorie').val(),
            }),
            success: function(response) {
                alert("Aliment mis à jour avec succès.");
                $('#alimentModal').hide();
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error("Erreur lors de la mise à jour de l'aliment :", error);
            }
        });
    });

    $('#deleteAliment').on('click', function() {
        if(confirm("Êtes-vous sûr de vouloir supprimer cet aliment ?")) {
            let alimentNom = $('#alimentNom').val();
            $.ajax({
                url: `${prefix_api}/aliments.php`,
                method: "DELETE",
                contentType: "application/json",
                data: JSON.stringify({ name: alimentNom }),
                success: function(response) {
                    alert("Aliment supprimé avec succès.");
                    $('#alimentModal').hide();
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error("Erreur lors de la suppression de l'aliment :", error);
                }
            });
        }
    });

    $(window).on('click', function(event) {
        if ($(event.target).is('#alimentModal')) {
            $('#alimentModal').hide();
            $('#alimentForm')[0].reset();
            $('#deleteAliment').prop('disabled', true);
        }
    });
});

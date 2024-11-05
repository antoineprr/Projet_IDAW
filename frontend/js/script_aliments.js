$(document).ready(function() {
    const prefix_api = window.prefix_api;
    let currentPage = 1;
    let totalPages = loadTotalPages();
    const maxVisiblePages = 5;
    let limit = 100;

    loadPage(currentPage);

    function loadTotalPages(){
        $.ajax({
            type: 'GET',
            url: `${prefix_api}/aliments.php`,
            dataType: 'json',
            success: function(data) {
                totalPages = Math.floor(data.length / limit) + 1;
                console.log(totalPages);
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
                limit: limit
            },
            success: function(data) {
                let tbody = $('#aliments-table tbody');
                tbody.empty();
                $.each(data, function(index, aliment) {
                    tbody.append(`<tr><td>${aliment.NOM_ALIMENT}</td><td>${aliment.NOM_TYPE}</td></tr>`);
                });
                updatePagination();
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    function updatePagination() {
        let pagination = $('.pagination');
        pagination.empty();

        // Calculate the start and end pages for the pagination display
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

        // Adjust startPage if we're near the end
        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        // First button
        let firstDisabled = currentPage === 1 ? 'disabled' : '';
        let firstButton = $(`<li class="page-item ${firstDisabled}"><a class="page-link" href="#">First</a></li>`);
        firstButton.on('click', function() {
            if (currentPage !== 1) loadPage(1);
        });
        pagination.append(firstButton);

        // Previous button
        let prevDisabled = currentPage === 1 ? 'disabled' : '';
        let prevButton = $(`<li class="page-item ${prevDisabled}"><a class="page-link" href="#">&laquo;</a></li>`);
        prevButton.on('click', function() {
            if (currentPage > 1) loadPage(currentPage - 1);
        });
        pagination.append(prevButton);

        // Page number buttons within the range
        for (let i = startPage; i <= endPage; i++) {
            let activeClass = currentPage === i ? 'active' : '';
            let pageButton = $(`<li class="page-item ${activeClass}"><a class="page-link" href="#">${i}</a></li>`);
            pageButton.on('click', function() {
                loadPage(i);
            });
            pagination.append(pageButton);
        }

        // Next button
        let nextDisabled = currentPage === totalPages ? 'disabled' : '';
        let nextButton = $(`<li class="page-item ${nextDisabled}"><a class="page-link" href="#">&raquo;</a></li>`);
        nextButton.on('click', function() {
            if (currentPage < totalPages) loadPage(currentPage + 1);
        });
        pagination.append(nextButton);

        // Last button
        let lastDisabled = currentPage === totalPages ? 'disabled' : '';
        let lastButton = $(`<li class="page-item ${lastDisabled}"><a class="page-link" href="#">Last</a></li>`);
        lastButton.on('click', function() {
            if (currentPage !== totalPages) loadPage(totalPages);
        });
        pagination.append(lastButton);
    }
});

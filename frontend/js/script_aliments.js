$(document).ready(function(){
    let prefix_api = window.prefix_api;
    var currentPage = 1;
    totalPages = 31;
    
    function updatePagination() {
        var pagination = $('.pagination');
        pagination.empty();
        var prevClass = currentPage === 1 ? 'disabled' : '';
        pagination.append(`<li class="${prevClass}"><a href="#">«</a></li>`);
        for (var i = 1; i <= totalPages; i++) {
            var activeClass = currentPage === i ? 'active' : '';
            pagination.append(`<li class="${activeClass}"><a href="#">${i}</a></li>`);
        }
        var nextClass = currentPage === totalPages ? 'disabled' : '';
        pagination.append(`<li class="${nextClass}"><a href="#">»</a></li>`);
    }

    $('.pagination').on('click', 'li a', function(event) {
        event.preventDefault();
        var pageItem = $(this).parent();
        var pageText = $(this).text();

        if (pageItem.hasClass('disabled') || pageItem.hasClass('active')) {
            return;
        }

        if (pageText == '«') {
            loadPage(currentPage - 1);
        } else if (pageText == '»') {
            loadPage(currentPage + 1);
        } else {
            loadPage(parseInt(pageText));
        }
    });

    loadPage(currentPage);
});

function loadPage(page) {
    currentPage = page;
    loadAliments(page);
}

function loadAliments(page){
    $.ajax({
        type: 'GET',
        url: `${prefix_api}/aliments.php`,
        dataType: 'json',
        data: {
            page: page,
            limit: 100
        },
        success: function(data){
            var aliments = data;
            var tbody = $('#aliments-table tbody');
            tbody.empty();
            $.each(aliments, function(index, aliment){
                tbody.append('<tr>'+
                    '<td>'+aliment.NOM_ALIMENT+'</td>'+
                    '<td>'+aliment.NOM_TYPE+'</td>'+
                    '</tr>');
            });
            $('#page-num').text(page);
            updatePagination();
        },
        error: function(xhr, status, error){
            console.error(error);
        }
    });
}
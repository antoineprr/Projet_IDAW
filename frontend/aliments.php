<?php
require_once('header_template.php');
require_once('config.php');
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/script_aliments.js"></script>
<body>
    <h1>Liste des Aliments</h1>
    <table class="table table-hover" id="aliments-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Catégorie</th>
            </tr>
        </thead>
        <tbody>
            <!-- Les données seront insérées ici -->
        </tbody>
    </table>
    <div>
        <ul class="pagination">
            <li class="page-item disabled">
                <a class="page-link" href="#">&laquo;</a>
            </li>
            <li class="page-item active">
                <a class="page-link" href="#">1</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">2</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">3</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">4</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">5</a>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">&raquo;</a>
            </li>
        </ul>
    </div>

</body>
</html>

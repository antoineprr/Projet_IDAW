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
            <li class="page-item">
                <a class="page-link" id="prev">&laquo;</a>
            </li>
            <li class="page-item">
                <a class="page-link">1</a>
            </li>
            <li class="page-item">
                <a class="page-link">2</a>
            </li>
            <li class="page-item">
                <a class="page-link">3</a>
            </li>
            <li class="page-item">
                <a class="page-link">4</a>
            </li>
            <li class="page-item">
                <a class="page-link">5</a>
            </li>
            <li class="page-item">
                <a class="page-link" id="next">&raquo;</a>
            </li>
        </ul>
    </div>
</body>
</html>

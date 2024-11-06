<?php
require_once('header_template.php');
require_once('config.php');
?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/script_aliments.js"></script>
<body>
    <div class="row">
        <div class="col-md-12 text-center">
            <h1 style="margin: 30px;">Liste des Aliments</h1>
        </div>
    </div>
    <div class="container">
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
    </div>
    <div>
      <label for="exampleSelect1" class="form-label mt-4">Nombre d'éléments</label>
      <select class="form-select" id="exampleSelect1">
        <option>10</option>
        <option>25</option>
        <option>50</option>
        <option>100</option>
        <option>200</option>
      </select>
    </div>
    <table class="table table-hover table-sm" id="aliments-table">
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
    <br>
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
<?php
require_once('footer_template.php');
?>
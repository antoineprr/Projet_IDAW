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
    <div id="ratiosContainer" class="row">
        <!-- Les ratios seront affichés ici par JavaScript -->
    </div>
</div>

<div class="container flex-grow-1 mt-5">
    <button class="btn btn-primary create-btn">Entrer un nouveau repas</button>
</div>

<div class="container flex-grow-1 mt-5">
    <div id="ratiosContainer" class="row">
        <!-- Les ratios seront affichés ici par JavaScript -->
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/script_new_repas.js"></script>
<script src="js/script_ratio_journal.js"></script>


<?php
require_once('footer_template.php');
?>
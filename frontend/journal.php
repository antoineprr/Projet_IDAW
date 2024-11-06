<?php
require_once('header_template.php');
require_once('config.php');
?>

<div class="container flex-grow-1">
    <h2 class="text-center">Vos repas</h2>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Aliments</th>
                <th scope="col">Actions</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody id="mealsTableBody">
            <!-- Les données seront insérées ici par JavaScript -->
        </tbody>
    </table>
    <div id="paginationContainer">
        <!-- La pagination sera insérée ici par JavaScript -->
    </div>
</div>

<div class="container flex-grow-1 mt-5">
    <button class="btn btn-primary create-btn">Entrer un nouveau repas</button>
</div>

<div class="container flex-grow-1 mt-5" id="createMealFormContainer" style="display: none;">
    <h2>Créer un nouveau repas</h2>
    <form id="createMealForm">
        <div class="form-group">
            <label for="mealDate">Date et heure</label>
            <input type="datetime-local" class="form-control" id="mealDate" required>
        </div>
        <div id="alimentsContainer">
    <div class="form-group">
        <label for="aliments">Aliments</label>
        <div class="input-group mb-3 align-items-center">
            <input type="text" class="form-control col-3 aliment-search"  placeholder="Rechercher un aliment" required>
            <input type="hidden" class="aliment-id">
            <input type="number" class="form-control col-3 aliment-quantity" placeholder="Quantité" required>
            <div class="input-group-append">
                <button class="btn btn-danger remove-aliment" type="button" style="margin-left: 10px">Supprimer</button>
            </div>
            <div class="dropdown-menu aliment-dropdown"></div>
        </div>
    </div>
</div>
        <button class="btn btn-secondary" id="addAliment" type="button">Ajouter un aliment</button>
        <button type="submit" class="btn btn-primary">Créer le repas</button>
    </form>
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
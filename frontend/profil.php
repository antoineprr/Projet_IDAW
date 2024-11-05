<?php
require_once('header_template.php');
require_once('config.php');
?>
<script>   
    const login = '<?php echo $_SESSION['login']; ?>';
</script>
<script src="js/script_profil.js"></script>
    <div class="container flex-grow-1">
        <h2>Vos informations :</h2>
        <form id="editForm" action="" onsubmit="onFormSubmit(event)">
            <div class="form-group">
                <label for="login">Nom d'utilisateur :</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="date_naissance">Date de naissance :</label>
                <input type="date" id="date_naissance" name="date_naissance" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="tranche_age">Tranche d'âge :</label>
                <select id="tranche_age" name="tranche_age" class="form-control" required>
                    <option value="">Sélectionnez votre tranche d'âge</option>
                </select>
            </div>
            <div class="form-group">
                <label for="pratique_sport">Pratique sportive :</label>
                <select id="pratique_sport" name="pratique_sport" class="form-control" required>
                    <option value="">Sélectionnez votre degré de pratique sportive</option>
                </select>
            </div>
            <div class="form-group">
                <label for="sexe">Sexe :</label>
                <select id="sexe" name="sexe" class="form-control" required>
                    <option value="">Sélectionnez votre sexe</option>
                </select>
            </div>
            <button type="submit" id="btSave" style="display: none;">Enregistrer les informations</button>
        </form>
        <button id="btEdit" onClick="unlockForm(this)">Modifier les informations</button>
    </div>
    <div class="container flex-grow-1">
        <h2>Modifier le mot de passe :</h2>
        <form id="passwordForm" action="" onsubmit="onPasswordFormSubmit(event)">
            <div class="form-group">
                <label for="current_password">Mot de passe actuel :</label>
                <input type="password" id="current_password" name="current_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="new_password">Nouveau mot de passe :</label>
                <input type="password" id="new_password" name="new_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmer le nouveau mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" id="btChangePassword">Changer le mot de passe</button>
        </form>
    </div>

<?php
require_once('footer_template.php');
?>
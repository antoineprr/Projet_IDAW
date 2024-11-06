<?php
require_once(dirname(__FILE__) . '/../init_pdo.php');
require_once(dirname(__FILE__) . '/../config.php');


///////////////////////////////////////////
// fonctions utilisées dans les requetes //
///////////////////////////////////////////


function explode_url($url) {
    $url_segments = explode('/', $url);
    return $url_segments;
}


function user_exist($pdo, $login) {
    $sql = "SELECT * FROM utilisateur WHERE LOGIN=:login";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':login', $login);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!$res){
        return false;
    }
    return true;
}


function get_utilisateurs($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM utilisateur");
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!$res){
        http_response_code(404);
        exit(json_encode(['status' => 'error', 'message' => "No user found"]));
    }
    return $res;
}

function get_un_utilisateurs($pdo, $login) {
    $sql = "SELECT * FROM utilisateur WHERE LOGIN=:login;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':login', $login);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!$res){
        http_response_code(404);
        exit(json_encode(['status' => 'error', 'message' => "Utilisateur '$login' not found"]));
    }
    return $res;
}

function add_utilisateur($pdo, $login, $code_age, $code_sexe, $code_sport, $mdp, $nom, $prenom, $date_naissance, $email){
    if(user_exist($pdo, $login)){
        http_response_code(409);
        exit(json_encode(['status' => 'error', 'message' => "Utilisateur '$login' already exists"]));
    }
    $sql = "INSERT INTO utilisateur (LOGIN, CODE_AGE, CODE_SEXE, CODE_SPORT, MDP, NOM, PRENOM, DATE_NAISSANCE, EMAIL) VALUES (:login, :code_age, :code_sexe, :code_sport, :mdp, :nom, :prenom, :date_naissance, :email)";
    $add = $pdo->prepare($sql);
    $add->bindParam(':login', $login);
    $add->bindParam(':code_age', $code_age);
    $add->bindParam(':code_sexe', $code_sexe);
    $add->bindParam(':code_sport', $code_sport);
    $add->bindParam(':mdp', $mdp);
    $add->bindParam(':nom', $nom);
    $add->bindParam(':prenom', $prenom);
    $add->bindParam(':date_naissance', $date_naissance);
    $add->bindParam(':email', $email);
    $add->execute();
}

function delete_utilisateur($pdo, $login) {
    if(user_exist($pdo, $login)){
        $sql = "DELETE FROM utilisateur WHERE LOGIN=:login";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        http_response_code(200);
        exit(json_encode(['status' => 'success', 'message' => "Utilisateur '$login' deleted"]));
    } else {
        http_response_code(404);
        exit(json_encode(['status' => 'error', 'message' => "Utilisateur '$login' not found"]));
    }
}

function update_utilisateur($pdo, $login, $code_age, $code_sexe, $code_sport, $nom, $prenom, $date_naissance, $email) {
    if(user_exist($pdo, $login)){
        $sql = "UPDATE utilisateur SET CODE_AGE=:code_age, CODE_SEXE=:code_sexe, CODE_SPORT=:code_sport, NOM=:nom, PRENOM=:prenom, DATE_NAISSANCE=:date_naissance, EMAIL=:email WHERE LOGIN=:login";
        $update = $pdo->prepare($sql);
        $update->bindParam(':login', $login);
        $update->bindParam(':code_age', $code_age);
        $update->bindParam(':code_sexe', $code_sexe);
        $update->bindParam(':code_sport', $code_sport);
        $update->bindParam(':nom', $nom);
        $update->bindParam(':prenom', $prenom);
        $update->bindParam(':date_naissance', $date_naissance);
        $update->bindParam(':email', $email);
        $update->execute();
        setHeaders();
        http_response_code(200);
        exit(json_encode(['status' => 'success', 'message' => "Utilisateur '$login' updated"]));
    } else {
        http_response_code(404);
        exit(json_encode(['status' => 'error', 'message' => "Utilisateur '$login' not found"]));
    }
}

function get_aliment_repas_from_login_and_date($pdo, $login, $date) {
    if(user_exist($pdo, $login)){
        $sql = "SELECT r.DATE, a.NOM_ALIMENT
                FROM repas r
                JOIN contient c ON r.CODE_REPAS = c.CODE_REPAS
                JOIN aliment a ON c.NOM_ALIMENT = a.NOM_ALIMENT
                WHERE r.LOGIN = :login_utilisateur
                    AND DATE(r.DATE) = :date_donnee;
                ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login_utilisateur', $login);
        $stmt->bindParam(':date_donnee', $date);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!$res){
            http_response_code(404);
            exit(json_encode(['status' => 'error', 'message' => "No aliment repas ratios found for user '$login' on date '$date'"]));
        }
        return $res;
    } else {
        http_response_code(404);
        exit(json_encode(['status' => 'error', 'message' => "Utilisateur '$login' not found"]));
    }
}

function get_aliment_repas_from_login($pdo, $login) {
    if(user_exist($pdo, $login)){
        $sql = "SELECT r.CODE_REPAS ,r.DATE, a.NOM_ALIMENT
                FROM repas r
                JOIN contient c ON r.CODE_REPAS = c.CODE_REPAS
                JOIN aliment a ON c.NOM_ALIMENT = a.NOM_ALIMENT
                WHERE r.LOGIN = :login_utilisateur
                ORDER BY r.DATE DESC;
                ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login_utilisateur', $login);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!$res){
            http_response_code(404);
            exit(json_encode(['status' => 'error', 'message' => "No aliment repas ratios found for user '$login'"]));
        }
        return $res;
    } else {
        http_response_code(404);
        exit(json_encode(['status' => 'error', 'message' => "Utilisateur '$login' not found"]));
    }
}

function add_repas_to_utilisateur($pdo, $login, $date, $aliment, $quantite){
    if(user_exist($pdo, $login)){
        $sql = "INSERT INTO repas ( LOGIN, DATE) VALUES (:login, :date)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':date', $date);
        $stmt->execute();
        if(!$stmt){
            http_response_code(400);
            exit(json_encode(['status' => 'error', 'message' => "Error while adding repas"]));
        }
        $code_repas = $pdo->lastInsertId();
        $sql = "INSERT INTO contient ( CODE_REPAS, NOM_ALIMENT, QUANTITE) VALUES (:code_repas, :nom_aliment, :quantite)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':code_repas', $code_repas);
        $stmt->bindParam(':nom_aliment', $aliment);
        $stmt->bindParam(':quantite', $quantite);
        $stmt->execute();
        if(!$stmt){
            http_response_code(400);
            exit(json_encode(['status' => 'error', 'message' => "Error while adding aliment to repas"]));
        }
        http_response_code(201);
        exit(json_encode(['status' => 'success', 'message' => "Repas added to user '$login'"]));
    }
}

function check_pswd($pdo, $login, $mdp){
    if(user_exist($pdo, $login)){
        $sql = "SELECT MDP FROM utilisateur WHERE LOGIN=:login";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $login);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if($res['MDP'] != $mdp){
            http_response_code(401);
            exit(json_encode(['status' => 'error', 'message' => "Wrong password for user '$login'"]));
        }
        return true;
    }
    else{
        http_response_code(404);
        exit(json_encode(['status' => 'error', 'message' => "Utilisateur '$login' not found"]));
    }
}

function get_calories_login($pdo, $login) {
    if(user_exist($pdo, $login)){
        $sql = "SELECT SUM(cr.QUANTITE_RATIO * (c.QUANTITE / 100)) AS CALORIES, DATE(r.DATE) AS DAY
                FROM repas r
                JOIN contient c ON r.CODE_REPAS = c.CODE_REPAS
                JOIN contient_ratio cr ON cr.NOM_ALIMENT = c.NOM_ALIMENT
                JOIN ratio rat ON cr.CODE_RATIO = rat.CODE_RATIO
                WHERE r.LOGIN = :login_utilisateur
                AND rat.NOM_RATIO = 'Energie, N x facteur Jones, avec fibres  (kcal/100 g)'
                GROUP BY DAY 
                ORDER BY DAY DESC;
                ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login_utilisateur', $login);
        //$stmt->bindParam(':date_donnee', $date);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!$res){
            http_response_code(404);
            exit(json_encode(['status' => 'error', 'message' => "No calories found for user '$login'"]));
        }
        return $res;
    } else {
        http_response_code(404);
        exit(json_encode(['status' => 'error', 'message' => "Utilisateur '$login' not found"]));
    }
}

function get_ratio_of_repas_from_utilisateur_date($pdo, $login, $code) {
    if(user_exist($pdo, $login)){
        $sql = "SELECT rat.NOM_RATIO, SUM(cr.QUANTITE_RATIO) AS QUANTITE_RATIO
                FROM repas r
                JOIN contient c ON r.CODE_REPAS = c.CODE_REPAS
                JOIN contient_ratio cr ON c.NOM_ALIMENT = cr.NOM_ALIMENT
                JOIN ratio rat ON cr.CODE_RATIO = rat.CODE_RATIO
                JOIN aliment a ON c.NOM_ALIMENT = a.NOM_ALIMENT
                WHERE r.LOGIN = :login_utilisateur
                AND r.CODE_REPAS = :code_repas
                GROUP BY rat.NOM_RATIO;
                ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login_utilisateur', $login);
        $stmt->bindParam(':code_repas', $code);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!$res){
            http_response_code(404);
            exit(json_encode(['status' => 'error', 'message' => "No ratios found for user '$login'"]));
        }
        return $res;
    } else {
        http_response_code(404);
        exit(json_encode(['status' => 'error', 'message' => "Utilisateur '$login' not found"]));
    }
}

function update_mdp($pdo, $login, $new_password) {
    $sql = "UPDATE utilisateur SET MDP=:new_password WHERE LOGIN=:login";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':new_password', $new_password);
    $stmt->bindParam(':login', $login);
    $stmt->execute();
    setHeaders();
}

function get_ratios_percent_from_day_login($pdo, $login, $date) {
    $sql = "SELECT COUNT(*) AS COUNT
                FROM repas r
                WHERE DATE(r.DATE) = :date_donnee
                AND r.LOGIN = :login_utilisateur
                ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login_utilisateur', $login);
        $stmt->bindParam(':date_donnee', $date);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$res){
            http_response_code(404);
            exit(json_encode(['status' => 'error', 'message' => "No repas found for user '$login' on date '$date'"]));
        }

    if(user_exist($pdo, $login)){
        $sql = "SELECT SUM(cr.QUANTITE_RATIO) AS SUM_RATIO
                FROM repas r
                JOIN contient c ON r.CODE_REPAS = c.CODE_REPAS
                JOIN contient_ratio cr ON cr.NOM_ALIMENT = c.NOM_ALIMENT
                JOIN ratio rat ON cr.CODE_RATIO = rat.CODE_RATIO
                WHERE r.LOGIN = :login_utilisateur
                AND (rat.NOM_RATIO = 'Protéines, N x 6.25 (g/100 g)' 
                    OR rat.NOM_RATIO = 'Lipides (g/100 g)' 
                    OR rat.NOM_RATIO = 'Glucides (g/100 g)' 
                    OR rat.NOM_RATIO = 'Sucres (g/100 g)'
                    OR rat.NOM_RATIO = 'Fibres alimentaires (g/100 g)')
                AND DATE(r.DATE) = :date_donnee
                ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login_utilisateur', $login);
        $stmt->bindParam(':date_donnee', $date);
        $stmt->execute();
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$res){
            http_response_code(404);
            exit(json_encode(['status' => 'error', 'message' => "No ratios found for user '$login' on date '$date'"]));
        }
        $sum_ratio = $res['SUM_RATIO'];

        if($sum_ratio == 0){
            http_response_code(404);
            exit(json_encode(['status' => 'error', 'message' => "No ratios found for user '$login' on date '$date'"]));
        }

        $sql = "SELECT rat.NOM_RATIO, (SUM(cr.QUANTITE_RATIO) / $sum_ratio)*100 AS QUANTITE
                FROM repas r
                JOIN contient c ON r.CODE_REPAS = c.CODE_REPAS
                JOIN contient_ratio cr ON cr.NOM_ALIMENT = c.NOM_ALIMENT
                JOIN ratio rat ON cr.CODE_RATIO = rat.CODE_RATIO
                WHERE r.LOGIN = :login_utilisateur
                AND DATE(r.DATE) = :date_donnee
                AND (rat.NOM_RATIO = 'Protéines, N x 6.25 (g/100 g)' 
                    OR rat.NOM_RATIO = 'Lipides (g/100 g)' 
                    OR rat.NOM_RATIO = 'Glucides (g/100 g)' 
                    OR rat.NOM_RATIO = 'Sucres (g/100 g)'
                    OR rat.NOM_RATIO = 'Fibres alimentaires (g/100 g)')
                GROUP BY rat.NOM_RATIO
                ORDER BY QUANTITE DESC;
                ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login_utilisateur', $login);
        $stmt->bindParam(':date_donnee', $date);
        $stmt->execute();
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!$res){
            http_response_code(404);
            exit(json_encode(['status' => 'error', 'message' => "No ratios found for user '$login' on date '$date'"]));
        }
        return $res;
    } else {
        http_response_code(404);
        exit(json_encode(['status' => 'error', 'message' => "Utilisateur '$login' not found"]));
    }
}


function setHeaders() {
    // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Origin
    header("Access-Control-Allow-Origin: *");
    header('Content-type: application/json; charset=utf-8');
}

///////////////////////////////
// recuperation des requêtes //
///////////////////////////////


switch($_SERVER["REQUEST_METHOD"]) { //TODO voir comment faire pour l'explode de l'url et si c'est la bonne méthode pour récupérer les GET, POST...
    case 'GET':
        $url = explode_url($_SERVER['REQUEST_URI']);
        $size = count($url);
        if (isset($url[$size-2]) && $url[$size-2] == 'login' && isset($url[$size-1])) {
            $login = $url[$size-1];
            $result = get_un_utilisateurs($pdo, $login);
        } 
        
        else if (isset($url[$size-3]) && $url[$size-3] == 'all' && isset($url[$size-2]) && isset($url[$size-1])) {
            $login = $url[$size-2];
            $date = $url[$size-1];
            $result = get_aliment_repas_from_login_and_date($pdo, $login, $date);
        }
        
        else if (isset($url[$size-2]) && $url[$size-2] == 'calories' && isset($url[$size-1])) {
            $login = $url[$size-1];
            $result = get_calories_login($pdo, $login);
        }

        else if (isset($url[$size-3]) && $url[$size-3] == 'daily_ratios' && isset($url[$size-2]) && isset($url[$size-1])) {
            $login = $url[$size-2];
            $date = $url[$size-1];
            $result = get_ratios_percent_from_day_login($pdo, $login, $date);
        }

        else if (isset($url[$size-2]) && $url[$size-2] == 'repas' && isset($url[$size-1])) {
            $login = $url[$size-1];
            $result = get_aliment_repas_from_login($pdo, $login);
        }

        else if (isset($url[$size-3]) && $url[$size-3] == 'ratios_repas' && isset($url[$size-2]) && isset($url[$size-1])) {
            $login = $url[$size-2];
            $date = $url[$size-1];
            $result = get_ratio_of_repas_from_utilisateur_date($pdo, $login, $date);
        }
        
        else {
            $result = get_utilisateurs($pdo);  // Récupérer tous les utilisateurs si aucun login spécifique
        }
        setHeaders();
        http_response_code(200);
        exit(json_encode($result));

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if(isset($data['login']) && isset($data['code_age']) && isset($data['code_sexe']) && isset($data['code_sport']) && isset($data['mdp']) && isset($data['nom']) && isset($data['prenom']) && isset($data['date_naissance']) && isset($data['email'])){
            add_utilisateur($pdo, $data['login'], $data['code_age'], $data['code_sexe'], $data['code_sport'], $data['mdp'], $data['nom'], $data['prenom'], $data['date_naissance'], $data['email']);
            setHeaders();
            http_response_code(201);
            exit(json_encode(['status' => 'success', 'message' => 'Utilisateur ajouté']));
        }
        if(isset($data['login']) && isset($data['date']) && isset($data['aliment']) && isset($data['quantite'])){
            add_repas_to_utilisateur($pdo, $data['login'], $data['date'], $data['aliment'], $data['quantite']);
        }
        if(isset($data['login']) && isset($data['password'])){
            if(check_pswd($pdo, $data['login'], $data['password'])){
                http_response_code(200);
                exit(json_encode(['status' => 'success']));
            }
        }
        else{
            http_response_code(400);
            exit(json_encode(['status' => 'error', 'message' => 'Missing parameters']));
        }
    case 'DELETE':
        $url = explode_url($_SERVER['REQUEST_URI']);
        if (isset($url[$size-2]) && $url[$size-2] == 'login' && isset($url[$size-1])) {
            $login = $url[$size-1];
            delete_utilisateur($pdo, $login);
        } else {
            http_response_code(400);
            exit(json_encode(['status' => 'error', 'message' => 'Missing login']));
        }
    case 'PUT':
        $url = explode_url($_SERVER['REQUEST_URI']);
        $data = json_decode(file_get_contents('php://input'), true);
        $size = count($url);
        if (isset($url[$size-2]) && $url[$size-2] == 'login' && isset($url[$size-1])) {
            $login = $url[$size-1];
            if(isset($data['code_age']) && isset($data['code_sexe']) && isset($data['code_sport']) && isset($data['nom']) && isset($data['prenom']) && isset($data['date_naissance']) && isset($data['email'])){
                update_utilisateur($pdo, $login, $data['code_age'], $data['code_sexe'], $data['code_sport'], $data['nom'], $data['prenom'], $data['date_naissance'], $data['email']);
            } else {
                http_response_code(400);
                exit(json_encode(['status' => 'error', 'message' => 'Missing parameters']));
            }
        } else if (isset($data['login']) && isset($data['current_password']) && isset($data['new_password'])) {
            $login = $data['login'];
            if(check_pswd($pdo, $login, $data['current_password'])){
                update_mdp($pdo, $login, $data['new_password']);
                http_response_code(200);
                exit(json_encode(['status' => 'success', 'message' => "Password updated for user '$login'"]));
            } else {
                http_response_code(401);
                exit(json_encode(['status' => 'error', 'message' => "Current password is incorrect"]));
            }
        } else {
            http_response_code(400);
            exit(json_encode(['status' => 'error', 'message' => 'Missing elements']));
        }

    default:
        http_response_code(405);
        exit(json_encode(array("message" => "Method not allowed")));
}
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
    $sql = "SELECT * FROM utilisateur WHERE LOGIN=:login";
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

function update_utilisateur($pdo, $login, $code_age, $code_sexe, $code_sport, $mdp, $nom, $prenom, $date_naissance, $email) {
    if(user_exist($pdo, $login)){
        $sql = "UPDATE utilisateur SET CODE_AGE=:code_age, CODE_SEXE=:code_sexe, CODE_SPORT=:code_sport, MDP=:mdp, NOM=:nom, PRENOM=:prenom, DATE_NAISSANCE=:date_naissance, EMAIL=:email WHERE LOGIN=:login";
        $update = $pdo->prepare($sql);
        $update->bindParam(':login', $login);
        $update->bindParam(':code_age', $code_age);
        $update->bindParam(':code_sexe', $code_sexe);
        $update->bindParam(':code_sport', $code_sport);
        $update->bindParam(':mdp', $mdp);
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
        if (isset($url[4]) && $url[4] == 'login' && isset($url[5])) {
            $login = $url[5];
            $result = get_un_utilisateurs($pdo, $login);
        } else {
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
        else{
            http_response_code(400);
            exit(json_encode(['status' => 'error', 'message' => 'Missing parameters']));
        }
    case 'DELETE':
        $url = explode_url($_SERVER['REQUEST_URI']);
        if (isset($url[4]) && $url[4] == 'login' && isset($url[5])) {
            $login = $url[5];
            delete_utilisateur($pdo, $login);
        } else {
            http_response_code(400);
            exit(json_encode(['status' => 'error', 'message' => 'Missing login']));
        }
    case 'PUT':
        $url = explode_url($_SERVER['REQUEST_URI']);
        if (isset($url[4]) && $url[4] == 'login' && isset($url[5])) {
            $login = $url[5];
            $data = json_decode(file_get_contents('php://input'), true);
            if(isset($data['code_age']) && isset($data['code_sexe']) && isset($data['code_sport']) && isset($data['mdp']) && isset($data['nom']) && isset($data['prenom']) && isset($data['date_naissance']) && isset($data['email'])){
                update_utilisateur($pdo, $login, $data['code_age'], $data['code_sexe'], $data['code_sport'], $data['mdp'], $data['nom'], $data['prenom'], $data['date_naissance'], $data['email']);
            } else {
                http_response_code(400);
                exit(json_encode(['status' => 'error', 'message' => 'Missing parameters']));
            }
        } else {
            http_response_code(400);
            exit(json_encode(['status' => 'error', 'message' => 'Missing login']));
        }

    default:
        http_response_code(405);
        exit(json_encode(array("message" => "Method not allowed")));
}
<?php
require_once(dirname(__FILE__) . '/../init_pdo.php');
require_once(dirname(__FILE__) . '/../config.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

///////////////////////////////////////////
// fonctions utilisées dans les requetes //
///////////////////////////////////////////


function explode_url($url) {
    $url_segments = explode('/', $url);
    return $url_segments;
}


function get_utilisateurs($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM utilisateur");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        break;

    default:
        http_response_code(405);
        exit(json_encode(array("message" => "Method not allowed")));
        break;
}
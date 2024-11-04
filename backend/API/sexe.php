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

function get_tranche($pdo) {
    $sql = "SELECT * FROM sexe";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $result = get_tranche($pdo); 
        setHeaders();
        http_response_code(200);
        exit(json_encode($result));
    
    default:
        http_response_code(405);
        exit(json_encode(array("message" => "Method not allowed")));
}
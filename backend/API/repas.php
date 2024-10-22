<?php
require_once(dirname(__FILE__) . '/../init_pdo.php');
require_once(dirname(__FILE__) . '/../config.php');


///////////////////////////////////////////
// fonctions utilisées dans les requetes //
///////////////////////////////////////////

function get_repas($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM repas");
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
        $result = get_repas($pdo);
        setHeaders();
        http_response_code(200);
        exit(json_encode($result));
        break;

    default:
        http_response_code(405);
        exit(json_encode(array("message" => "Method not allowed")));
        break;
}
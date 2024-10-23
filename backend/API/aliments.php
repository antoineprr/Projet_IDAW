<?php
require_once(dirname(__FILE__) . '/../init_pdo.php');
require_once(dirname(__FILE__) . '/../config.php');


///////////////////////////////////////////
// fonctions utilisées dans les requetes //
///////////////////////////////////////////

function get_aliments($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM aliment");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_aliment_by_name($pdo, $aliment_url) {
    $sql = "SELECT * FROM aliment WHERE NOM_ALIMENT=:aliment";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':aliment', $aliment_url);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!$res){
        http_response_code(404);
        exit(json_encode(['status' => 'error', 'message' => "Aliment '$aliment_url' not found"]));
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
        $url = $_SERVER['REQUEST_URI'];
        $url_segments = explode('/', $url);
        $url_size = sizeof($url_segments);
        $aliment_url = $url_segments[$url_size-1];
        $aliment_url = htmlspecialchars($aliment_url, ENT_QUOTES, 'UTF-8');
        if ($aliment_url=='aliments' || $aliment_url==''){
            $result = get_aliments($pdo);
            setHeaders();
            http_response_code(200);
            exit(json_encode($result));
            break;
        }
        $aliment_url = str_replace("-", " ", $aliment_url);
        $result = get_aliment_by_name($pdo, $aliment_url);
        setHeaders();
        http_response_code(200);
        exit(json_encode($result));
        break;


    default:
        http_response_code(405);
        exit(json_encode(array("message" => "Method not allowed")));
        break;
}
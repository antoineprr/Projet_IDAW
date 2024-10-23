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

function get_repas_by_utilisateur($pdo, $utilisateur_url) {
    $sql = "SELECT * FROM repas WHERE LOGIN=:login";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':login', $utilisateur_url);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!$res){
        http_response_code(404);
        exit(json_encode(['status' => 'error', 'message' => "Utilateur '$utilisateur_url' not found"]));
    }
    return $res;
}

function add_repas($pdo, $login, $date) {
    try {
        $sql = "INSERT INTO repas (CODE_REPAS, LOGIN, DATE) VALUES (NULL, :login, :date)";
        $request = $pdo->prepare($sql);
        $request->bindParam(':login', $login);
        $request->bindParam(':date', $date);
        $request->execute();
        return get_repas_by_utilisateur($pdo, $login);
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') {
            return "Erreur : L'utilisateur avec le login '$login' n'existe pas.";
        } else {
            throw $e;
        }
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
        $result = get_repas($pdo);

        $url = $_SERVER['REQUEST_URI'];
        $url_segments = explode('/', $url);
        $url_size = sizeof($url_segments);
        $utilisateur_url = $url_segments[$url_size-1];
        $utilisateur_url = htmlspecialchars($utilisateur_url, ENT_QUOTES, 'UTF-8');
        if ($utilisateur_url=='repas' || $utilisateur_url==''){
            $result = get_repas($pdo);
        }
        else {
            $result = get_repas_by_utilisateur($pdo, $utilisateur_url);
        }
        setHeaders();
        http_response_code(200);
        exit(json_encode($result));

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        if(isset($input['login']) && isset($input['date'])){
            $result = add_repas($pdo, $input['login'], $input['date']);
            setHeaders();
            http_response_code(201);
            exit(json_encode($result));
        }
        else{
            http_response_code(404);
            exit(json_encode(['status'=>'error', 'message'=>'invalid input']));
        }

    default:
        http_response_code(405);
        exit(json_encode(array("message" => "Method not allowed")));
        break;
}
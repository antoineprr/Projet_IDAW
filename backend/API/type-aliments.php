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

function get_types_aliments($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM type_aliment");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function get_one_type_aliment($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM type_aliment WHERE CODE_TYPE = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_type_of_aliment($pdo, $aliment) {
    $sql = "SELECT type_aliment.NOM_TYPE 
            FROM type_aliment JOIN aliment 
                ON type_aliment.CODE_TYPE=aliment.CODE_TYPE 
            WHERE aliment.NOM_ALIMENT=:aliment;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':aliment', $aliment);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(!$res){
        http_response_code(404);
        exit(json_encode(['status' => 'error', 'message' => "Aliment '$aliment' not found"]));
    }
    return $res;
}

function add_type_aliment($pdo, $nom_type_aliment) {
    $sql = "INSERT INTO type_aliment (NOM_TYPE_ALIMENT) VALUES (:nom_type_aliment)";
    $add = $pdo->prepare($sql);
    $add->bindParam(':nom_type_aliment', $nom_type_aliment);
    $add->execute();
    return $pdo->lastInsertId();
}

function delete_type_aliment($pdo, $id) {
    $sql = "DELETE FROM type_aliment WHERE id = :id";
    $delete = $pdo->prepare($sql);
    $delete->bindParam(':id', $id);
    $delete->execute();
    return $pdo->lastInsertId();
}

function update_type_aliment($pdo, $id, $nom_type_aliment) {
    $sql = "UPDATE type_aliment SET NOM_TYPE_ALIMENT = :nom_type_aliment WHERE id = :id";
    $update = $pdo->prepare($sql);
    $update->bindParam(':id', $id);
    $update->bindParam(':nom_type_aliment', $nom_type_aliment);
    $update->execute();
    return $pdo->lastInsertId();
}


function setHeaders() {
    // https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Access-Control-Allow-Origin
    header("Access-Control-Allow-Origin: *");
    header('Content-type: application/json; charset=utf-8');
}

///////////////////////////////
// recuperation des requêtes //
///////////////////////////////


switch($_SERVER["REQUEST_METHOD"]) { 
    case 'GET':
        $url = explode_url($_SERVER['REQUEST_URI']);
        if (isset($url[4]) && $url[4] == 'id' && isset($url[5])) {
            $id = $url[5];
            $result = get_one_type_aliment($pdo, $id);
        } else if (isset($url[4]) && $url[4] == 'aliment' && isset($url[5])) {
            $aliment = urldecode($url[5]);
            $aliment = str_replace('-', ' ', $aliment);
            $result = get_type_of_aliment($pdo, $aliment);
        } else {
            $result = get_types_aliments($pdo);
        }
        setHeaders();
        http_response_code(200);
        exit(json_encode($result));
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if(isset($data['nom_type_aliment'])){
            $id = add_type_aliment($pdo, $data['nom_type_aliment']);
            setHeaders();
            http_response_code(201);
            exit(json_encode(['status' => 'success', 'message' => "Type aliment '$id' created"]));
        } else {
            http_response_code(400);
            exit(json_encode(['status' => 'error', 'message' => "Missing 'nom_type_aliment'"]));
        }
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $url = explode_url($_SERVER['REQUEST_URI']);
        if(isset($data['nom_type_aliment']) && isset($url[4]) && $url[4] == 'id' && isset($url[5])){
            $id = $url[5];
            $id = update_type_aliment($pdo, $id, $data['nom_type_aliment']);
            setHeaders();
            http_response_code(200);
            exit(json_encode(['status' => 'success', 'message' => "Type aliment '$id' updated"]));
        } else {
            http_response_code(400);
            exit(json_encode(['status' => 'error', 'message' => "Missing 'nom_type_aliment' or 'id'"]));
        }
    case 'DELETE':
        $url = explode_url($_SERVER['REQUEST_URI']);
        if (isset($url[4]) && $url[4] == 'id' && isset($url[5])) {
            $id = $url[5];
            $id = delete_type_aliment($pdo, $id);
            setHeaders();
            http_response_code(200);
            exit(json_encode(['status' => 'success', 'message' => "Type aliment '$id' deleted"]));
        } else {
            http_response_code(400);
            exit(json_encode(['status' => 'error', 'message' => "Missing 'id'"]));
        }
    default:
        http_response_code(405);
        exit(json_encode(array("message" => "Method not allowed")));
}
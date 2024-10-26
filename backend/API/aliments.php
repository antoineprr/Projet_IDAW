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

function get_aliment_by_code_type($pdo, $code_type) {
    $sql = "SELECT * FROM aliment WHERE CODE_TYPE=:code_type";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':code_type', $code_type);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!$res){
        http_response_code(404);
        exit(json_encode(['status' => 'error', 'message' => "Code '$code_type' not found"]));
    }
    return $res;
}

function aliment_exists($pdo, $name) {
    $sql = "SELECT COUNT(*) FROM aliment WHERE NOM_ALIMENT = :name";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

function create_aliment($pdo, $name, $type) {
    if (aliment_exists($pdo, $name)) {
        return ['status' => 'error', 'message' => 'Aliment already exists'];
    }

    $sql = "INSERT INTO aliment (NOM_ALIMENT, CODE_TYPE) VALUES (:name, :type)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':type', $type);
    $stmt->execute();
    return ['status' => 'success', 'message' => 'Aliment created successfully'];
}

function update_aliment($pdo, $name, $type) {
    $sql = "UPDATE aliment SET CODE_TYPE=:type WHERE NOM_ALIMENT=:name";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':type', $type);
    $stmt->execute();
    return $stmt->rowCount();
}

function delete_aliment($pdo, $name) {
    $sql = "DELETE FROM aliment WHERE NOM_ALIMENT=:name";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    return $stmt->rowCount();
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
        $url = $_SERVER['REQUEST_URI'];
        $url_segments = explode('/', $url);
        $url_size = sizeof($url_segments);
        $aliment_url = $url_segments[$url_size-1];
        $aliment_url = htmlspecialchars($aliment_url, ENT_QUOTES, 'UTF-8');
        if ($aliment_url=='aliments' || $aliment_url==''){
            $result = get_aliments($pdo);
        }
        elseif (!is_numeric($aliment_url)){
            $aliment_url = urldecode($aliment_url);
            $aliment_url = str_replace("-", " ", $aliment_url);
            $result = get_aliment_by_name($pdo, $aliment_url);
        }
        else{
            $result = get_aliment_by_code_type($pdo, $aliment_url);
        }
        setHeaders();
        http_response_code(200);
        exit(json_encode($result));

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['name']) && isset($data['type'])) {
            $result = create_aliment($pdo, $data['name'], $data['type']);
            setHeaders();
            if ($result['status'] === 'error') {
                http_response_code(409);
            } else {
                http_response_code(201);
            }
            exit(json_encode($result));
        } else {
            setHeaders();
            http_response_code(400);
            exit(json_encode(['status' => 'error', 'message' => 'Invalid input parameters']));
        }

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['name']) && isset($data['type'])) {
            $result = update_aliment($pdo, $data['name'], $data['type']);
            setHeaders();
            http_response_code(200);
            exit(json_encode(['status' => 'success', 'message' => 'Aliment updated successfully']));
        } else {
            setHeaders();
            http_response_code(400);
            exit(json_encode(['status' => 'error', 'message' => 'Invalid input parameters']));
        }

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['name'])) {
            $result = delete_aliment($pdo, $data['name']);
            setHeaders();
            http_response_code(200);
            exit(json_encode(['status' => 'success', 'message' => 'Aliment deleted successfully']));
        } else {
            setHeaders();
            http_response_code(400);
            exit(json_encode(['status' => 'error', 'message' => 'Invalid input parameters']));
        }
    
    default:
        http_response_code(405);
        exit(json_encode(array("message" => "Method not allowed")));
}
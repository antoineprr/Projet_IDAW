<?php
require_once(dirname(__FILE__) . '/../init_pdo.php');
require_once(dirname(__FILE__) . '/../config.php');


///////////////////////////////////////////
// fonctions utilisées dans les requetes //
///////////////////////////////////////////

function get_ratios($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM ratio");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_ratios_of_aliment($pdo, $ratio_url) {
    $sql = "SELECT contient_ratio.QUANTITE_RATIO, ratio.NOM_RATIO FROM contient_ratio JOIN ratio ON ratio.CODE_RATIO=contient_ratio.CODE_RATIO WHERE contient_ratio.NOM_ALIMENT=:aliment;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':aliment', $ratio_url);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!$res){
        http_response_code(404);
        exit(json_encode(['status' => 'error', 'message' => "Aliment '$ratio_url' not found"]));
    }
    return $res;
}

function get_one_ratio_of_aliment($pdo, $code_ratio, $aliment) {
    $sql = "SELECT contient_ratio.QUANTITE_RATIO, ratio.NOM_RATIO FROM contient_ratio JOIN ratio ON ratio.CODE_RATIO=contient_ratio.CODE_RATIO WHERE contient_ratio.NOM_ALIMENT=:aliment AND contient_ratio.CODE_RATIO=:code_ratio;" ;
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':code_ratio', $code_ratio);
    $stmt->bindParam(':aliment', $aliment);
    $stmt->execute();
    $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!$res){
        http_response_code(404);
        exit(json_encode(['status' => 'error', 'message' => "Code '$code_ratio' not found"]));
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
        $ratio_url = $url_segments[$url_size-1];
        $ratio_url = htmlspecialchars($ratio_url, ENT_QUOTES, 'UTF-8');
        if ($ratio_url=='ratio' || $ratio_url==''){
            $result = get_ratios($pdo);
        }
        elseif (!is_numeric($ratio_url)){
            $ratio_url = urldecode($ratio_url);
            $ratio_url = str_replace("-", " ", $ratio_url);
            $result = get_ratios_of_aliment($pdo, $ratio_url);
        }
        else{
            if ($url_segments[$url_size-2]!='ratio'){
                $aliment_url = urldecode($url_segments[$url_size-2]);
                echo $ratio_url;                
                echo $aliment_url;                
                $result = get_one_ratio_of_aliment($pdo, $ratio_url, $aliment_url);
            } else {
                http_response_code(400);
                exit(json_encode(['status' => 'error', 'message' => 'Wrong format']));
            }
            
        }
        setHeaders();
        http_response_code(200);
        exit(json_encode($result));

    default:
        http_response_code(405);
        exit(json_encode(array("message" => "Method not allowed")));
        break;
}
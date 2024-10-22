<?php
require_once('config.php');


$connectionString = "mysql:host=". _MYSQL_HOST;
if(defined('_MYSQL_PORT')){
    $connectionString .= ";port=". _MYSQL_PORT;
}
$connectionString .= ";dbname=". _MYSQL_DBNAME;
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' );

$pdo = NULL;
try {
    $pdo = new PDO($connectionString,_MYSQL_USER,_MYSQL_PASSWORD,$options);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $erreur) {
    echo 'Erreur : '.$erreur->getMessage();
}

$csvFilePath = "aliments.csv";
$file = fopen($csvFilePath, "r"); 
$ref = fgetcsv($file);

while($row = fgetcsv($file)){
    $nom = $row[7];
    for($i = 14; $i < sizeof($row); $i++){
        $ratio = $ref[$i];
        $quantite = $row[$i];
        $ratioCode = $pdo->prepare("SELECT CODE_RATIO FROM ratio WHERE NOM_RATIO = :ratio");
        $ratioCode->bindParam(':ratio', $ratio);
        $ratioCode->execute();
        $ratio = $ratioCode->fetchColumn();

        $quantite = str_replace(',', '.', $quantite);
        $quantite = str_replace('-', '', $quantite);
        $quantite = str_replace('<', '', $quantite);
        
        if($quantite == ''){
            $quantite = 0;
        }
        $stmt = $pdo->prepare("INSERT INTO contient_ratio (NOM_ALIMENT, CODE_RATIO, QUANTITE_RATIO) VALUES (:nom, :ratio, :quantite)");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':ratio', $ratio);
        $stmt->bindParam(':quantite', $quantite);
        $stmt->execute();
    }
}
?>
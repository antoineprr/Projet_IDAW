<?php
require_once(dirname(__FILE__) . '/../config.php');


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
$row = fgetcsv($file);
for($i = 14; $i < sizeof($row); $i++){
    $ratio = $row[$i];
    $stmt = $pdo->prepare("INSERT INTO ratio (NOM_RATIO) VALUES (:ratio)");
    $stmt->bindParam(':ratio', $ratio);
    $stmt->execute();



}
?>
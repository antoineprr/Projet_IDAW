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

$csvFilePath = "aliases.csv";
$file = fopen($csvFilePath, "r"); 
fgetcsv($file); // skip the first line
while (($row = fgetcsv($file)) !== FALSE) {

    $stmt = $pdo->prepare("INSERT INTO type_aliment (NOM, PRENOM, LOGIN, CODE_AGE, CODE_SEXE, CODE_SPORT, MDP, DATE_NAISSANCE, EMAIL) VALUES (:nom, :prenom, :login, 1, 1, 1, 'root', '1999-01-01', :email)");

    $stmt->execute();
}
?>
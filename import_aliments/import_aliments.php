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

$csvFilePath = "import-template.csv";
$file = fopen($csvFilePath, "r");
while (($row = fgetcsv($file)) !== FALSE) {
    $stmt = $mysqli->prepare("INSERT INTO aliment (NOM_ALIMENT, CODE_TYOE) VALUES (?, ?)");
    $stmt->bind_param("si", $row[1], $row[2]);
    $stmt->execute();
}
?>
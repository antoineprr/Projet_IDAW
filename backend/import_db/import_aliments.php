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
fgetcsv($file); // skip the first line
while (($row = fgetcsv($file)) !== FALSE) {
    $type = $row[4];
    $codeType = $pdo->prepare("SELECT CODE_TYPE FROM type_aliment WHERE NOM_TYPE = :type");
    $codeType->bindParam(':type', $type);
    $codeType->execute();
    $value = $codeType->fetchColumn();

    // Check if the aliment already exists
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM aliment WHERE NOM_ALIMENT = :nom");
    $checkStmt->bindParam(':nom', $row[7]);
    $checkStmt->execute();
    $count = $checkStmt->fetchColumn();

    if ($count == 0) {
        $stmt = $pdo->prepare("INSERT INTO aliment (NOM_ALIMENT, CODE_TYPE) VALUES (:nom, :codeType)");
        $stmt->bindParam(':nom', $row[7]);
        $stmt->bindParam(':codeType', $value);
        $stmt->execute();
    }
    
}

?>
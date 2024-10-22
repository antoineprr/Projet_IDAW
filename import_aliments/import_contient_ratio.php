<?php
require_once('config.php');

$connectionString = "mysql:host=" . _MYSQL_HOST;
if (defined('_MYSQL_PORT')) {
    $connectionString .= ";port=" . _MYSQL_PORT;
}
$connectionString .= ";dbname=" . _MYSQL_DBNAME;
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

try {
    $pdo = new PDO($connectionString, _MYSQL_USER, _MYSQL_PASSWORD, $options);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $erreur) {
    die('Erreur : ' . $erreur->getMessage()); 
}

$csvFilePath = "aliments.csv";
$file = fopen($csvFilePath, "r");
$ref = fgetcsv($file);

$ratios = [];
for ($i = 14; $i < sizeof($ref); $i++) {
    $ratio = $ref[$i];
    $stmt = $pdo->prepare("SELECT CODE_RATIO FROM ratio WHERE NOM_RATIO = :ratio");
    $stmt->bindParam(':ratio', $ratio);
    $stmt->execute();
    $ratios[$ratio] = $stmt->fetchColumn(); 
}

$pdo->beginTransaction();
try {
    while ($row = fgetcsv($file)) {
        $nom = $row[7];

        for ($i = 14; $i < sizeof($row); $i++) {
            $ratio = $ratios[$ref[$i]] ?? null; 
            $quantite = $row[$i];

            $quantite = str_replace([',', '-', '<', 'traces'], ['.', '', '', ''], $quantite);
            $quantite = ($quantite === '') ? 0 : (float)$quantite;

            if ($ratio) { 
                $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM contient_ratio WHERE NOM_ALIMENT = :nom AND CODE_RATIO = :ratio");
                $checkStmt->bindParam(':nom', $nom);
                $checkStmt->bindParam(':ratio', $ratio);
                $checkStmt->execute();

                if ($checkStmt->fetchColumn() == 0) {
                    $stmt = $pdo->prepare("INSERT INTO contient_ratio (NOM_ALIMENT, CODE_RATIO, QUANTITE_RATIO) VALUES (:nom, :ratio, :quantite)");
                    $stmt->bindParam(':nom', $nom);
                    $stmt->bindParam(':ratio', $ratio);
                    $stmt->bindParam(':quantite', $quantite);
                    $stmt->execute();
                }
            }
        }
    }
    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack(); 
    die('Erreur lors du traitement des données : ' . $e->getMessage());
}
fclose($file);
?>

<?php
    require_once(dirname(__FILE__) . '/../config.php');

    $script = file_get_contents('projet_idaw_sql.sql');

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
    $pdo->query($script);
?>
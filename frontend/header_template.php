<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: connexion.php');
    exit();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>iMangerMieux</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-primary" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Menu</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarColor01">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link <?php if($current_page == 'index.php') echo 'active'; ?>" href="index.php">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($current_page == 'profil.php') echo 'active'; ?>" href="profil.php">Profil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($current_page == 'journal.php') echo 'active'; ?>" href="journal.php">Journal</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php if($current_page == 'aliments.php') echo 'active'; ?>" href="aliments.php">Aliments</a>
        </li>
      </ul>
      <div class="d-flex">
        <a class="nav-link btn-secondary" href="../backend/deconnexion.php">Deconnexion</a>
    </div>
    </div>
  </div>
</nav>

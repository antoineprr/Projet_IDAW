# Application de Suivi Nutritionnel

Ce projet est une application de suivi nutritionnel permettant aux utilisateurs de gérer leurs repas, de surveiller leur apport calorique, et de visualiser leurs données nutritionnelles via des graphiques interactifs. Elle utilise un backend en PHP pour gérer les sessions et les données utilisateur, avec un frontend en HTML, CSS, et JavaScript (jQuery et Bootstrap).

---

## Configuration Initiale

### 1. Créer le fichier `config.php` dans le dossier `frontend`

Créez un fichier `config.php` dans le dossier `frontend` et ajoutez-y le code suivant :

```php
<?php
define('prefix_api', 'votre_url/backend/API');
?>
<script>window.prefix_api='votre_url/backend/API'</script>
```

Assurez-vous de remplacer `votre_url` par l'URL correcte vers l'API backend.

### 2. Logins et mots de passe d'exemple

Utilisez les identifiants suivants pour tester les différentes fonctionnalités de l'application :

| Nom d'utilisateur | Mot de passe      |
|-------------------|-------------------|
| testDEV           | dev               |
| ackbar            | MDPAckbar         |
| ...               | ...               |

---

## Explication du Frontend

Le frontend est conçu pour offrir une interface intuitive et réactive. Les fonctionnalités incluent :

- **Connexion et Inscription** : Authentification avec validation des formulaires.
- **Gestion de Profil** : Mise à jour des informations personnelles (nom, prénom, email, etc.).
- **Suivi des Repas** : Ajout, modification et suppression des repas avec choix des aliments et quantités.
- **Suivi Calorique** : Visualisation des apports caloriques via des graphiques interactifs fournis par amCharts.
- **Navigation Intuitive** : Conception responsive grâce à Bootstrap.

---

## Technologies Utilisées

- **HTML & CSS** : Pour la structure et le style des pages.
- **JavaScript & jQuery** : Pour les interactions et les requêtes AJAX vers l'API backend.
- **Bootstrap** : Pour le design responsive.
- **amCharts** : Pour la création de graphiques interactifs.
- **PHP** : Pour l'intégration avec le backend et la gestion des sessions.

---

## Structure des Fichiers

- **js** : Contient les fichiers JavaScript pour les fonctionnalités principales (connexion, inscription, profil, etc.).
- **css** : Contient les fichiers CSS pour le style de l'application.
- **frontend/index.php** : Page d'accueil après connexion, avec les graphiques intégrés.
- **frontend/inscription.php** : Formulaire d'inscription pour les nouveaux utilisateurs.
- **frontend/connexion.php** : Formulaire de connexion pour les utilisateurs existants.

---

## Configuration

1. **Définir l'API backend** : Assurez-vous que le fichier `config.php` pointe vers l'URL correcte de l'API backend.
2. **Installer les Dépendances** : Les bibliothèques externes (jQuery, Bootstrap, amCharts) sont incluses via des liens CDN dans les fichiers HTML/PHP.
3. **Tester les Logins** : Utilisez les identifiants fournis pour vous connecter et tester les différentes fonctionnalités.

---


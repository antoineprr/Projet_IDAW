# Documentation de l'API

Ce document décrit les différents endpoints de l'API, les méthodes HTTP disponibles, les paramètres attendus et les réponses renvoyées.

---

## Table des matières

- Utilisateur
    - GET /utilisateurs.php
    - GET /utilisateurs.php/login/:login
    - POST /utilisateurs.php
    - PUT /utilisateurs.php/login/:login
    - DELETE /utilisateurs.php/login/:login
    - GET /utilisateurs.php/repas/:login
    - GET /utilisateurs.php/daily_ratios/:login/:date
    - GET /utilisateurs.php/calories/:login
    - GET /utilisateurs.php/ratios_repas/:login/:code_repas
- Aliments
    - GET /aliments.php
    - GET /aliments.php/:type
- Repas
    - GET /repas.php
    - POST /repas.php
    - POST /repas.php (ajout d'aliment)
    - DELETE /repas.php/:code_repas
- Types d'aliments
    - GET /type-aliments.php
    - POST /type-aliments.php
    - PUT /type-aliments.php/id/:id
    - DELETE /type-aliments.php/id/:id
- Ratios
    - GET /ratio.php
    - GET /ratio.php/:aliment
    - GET /ratio.php/:aliment/:code_ratio
    - POST /ratio.php
    - PUT /ratio.php
    - DELETE /ratio.php

---

## Utilisateur

### GET /utilisateurs.php

**Description :**

Récupère la liste de tous les utilisateurs.

**Réponse :**
```
[
  {
    "LOGIN": "user1",
    "NOM": "Doe",
    "PRENOM": "John",
    "EMAIL": "john.doe@example.com",
    "DATE_NAISSANCE": "1990-01-01",
    "CODE_SEXE": 1,
    "CODE_SPORT": 1,
    "CODE_AGE": 2
  },
  {
    "LOGIN": "user2",
    "NOM": "Smith",
    "PRENOM": "Jane",
    "EMAIL": "jane.smith@example.com",
    "DATE_NAISSANCE": "1985-05-15",
    "CODE_SEXE": 2,
    "CODE_SPORT": 2,
    "CODE_AGE": 3
  },
  ...
]
```

### GET /utilisateurs.php/login/:login

**Description :**

Récupère les informations d'un utilisateur spécifique.

**Paramètres :**

- `:login` : Login de l'utilisateur.

**Réponse :**
```
{
  "LOGIN": "user1",
  "NOM": "Doe",
  "PRENOM": "John",
  "EMAIL": "john.doe@example.com",
  "DATE_NAISSANCE": "1990-01-01",
  "CODE_SEXE": 1,
  "CODE_SPORT": 1,
  "CODE_AGE": 2
}
```

### POST /utilisateurs.php

**Description :**

Crée un nouvel utilisateur.

**Corps de la requête :**
```
{
  "mdp": "nouveau_mot_de_passe",
  "nom": "Dupont",
  "prenom": "Marie",
  "date_naissance": "1995-07-20",
  "email": "marie.dupont@example.com",
  "code_sexe": 2,
  "code_sport": 1,
  "code_age": 2
}
```

**Réponse :**
```
{
  "status": "success",
  "message": "Utilisateur 'nouvel_utilisateur' mis à jour"
}
```

### DELETE /utilisateurs.php/login/:login

**Description :**

Supprime un utilisateur spécifique.

**Paramètres :**

- `:login` : Login de l'utilisateur à supprimer.

**Réponse :**
```
{
  "status": "success",
  "message": "Utilisateur 'nouvel_utilisateur' supprimé"
}
```

### GET /utilisateurs.php/repas/:login

**Description :**

Récupère les repas associés à un utilisateur.

**Paramètres :**

- `:login` : Login de l'utilisateur.

**Réponse :**
```
[
  {
    "CODE_REPAS": 1,
    "DATE": "2023-10-15 12:30:00",
    "NOM_ALIMENT": "Pomme"
  },
  {
    "CODE_REPAS": 1,
    "DATE": "2023-10-15 12:30:00",
    "NOM_ALIMENT": "Sandwich"
  },
  {
    "CODE_REPAS": 2,
    "DATE": "2023-10-16 19:00:00",
    "NOM_ALIMENT": "Salade"
  },
  ...
]
```

### GET /utilisateurs.php/daily_ratios/:login/:date

**Description :**

Récupère les ratios journaliers d'un utilisateur pour une date donnée.

**Paramètres :**

- `:login` : Login de l'utilisateur.
- `:date` : Date au format YYYY-MM-DD.

**Réponse :**
```
[
  {
    "NOM_RATIO": "Protéines",
    "QUANTITE": 50
  },
  {
    "NOM_RATIO": "Glucides",
    "QUANTITE": 200
  },
  {
    "NOM_RATIO": "Lipides",
    "QUANTITE": 70
  }
]
```

### GET /utilisateurs.php/calories/:login

**Description :**

Récupère les calories consommées par un utilisateur sur une période donnée.

**Paramètres :**

- `:login` : Login de l'utilisateur.

**Réponse :**
```
[
  {
    "DAY": "2023-10-15",
    "CALORIES": 1800
  },
  {
    "DAY": "2023-10-16",
    "CALORIES": 2000
  },
  ...
]
```

### GET /utilisateurs.php/ratios_repas/:login/:code_repas

**Description :**

Récupère les ratios pour un repas spécifique d'un utilisateur.

**Paramètres :**

- `:login` : Login de l'utilisateur.
- `:code_repas` : Code du repas.

**Réponse :**
```
[
  {
    "NOM_RATIO": "Protéines",
    "QUANTITE": 20
  },
  {
    "NOM_RATIO": "Glucides",
    "QUANTITE": 80
  },
  {
    "NOM_RATIO": "Lipides",
    "QUANTITE": 25
  }
]
```

---

## Aliments

### GET /aliments.php

La méthode GET permet de récupérer des informations sur les aliments. Elle offre plusieurs fonctionnalités en fonction des paramètres fournis.
#### Récupérer tous les aliments

**Description :**

- Récupère la liste complète de tous les aliments disponibles.

**Requête :**

- **Méthode HTTP :** GET
- **URL :** /aliments.php

**Réponse :**

- **Code HTTP :** 200 OK
- **Corps :** Tableau JSON des aliments.

**Exemple de réponse :**
```
[
  {
    "ID_ALIMENT": 1,
    "NOM_ALIMENT": "Pomme",
    "CODE_TYPE": 1
  },
  {
    "ID_ALIMENT": 2,
    "NOM_ALIMENT": "Banane",
    "CODE_TYPE": 1
  },
  ...
]
```

#### Récupérer un aliment par nom

**Description :**

- Récupère les informations d'un aliment spécifique en fonction de son nom exact.

**Requête :**

- **Méthode HTTP :** GET
- **URL :** `/aliments.php?name={nom_de_l_aliment}`

**Paramètres de requête :**

- `name` : Nom exact de l'aliment à rechercher (sensible à la casse).

**Exemple de requête :**
```
GET /aliments.php?name=Banane
```

**Exemple de réponse :**
```
{
  "ID_ALIMENT": 2,
  "NOM_ALIMENT": "Banane",
  "CODE_TYPE": 1
}
```

#### Récupérer les aliments par type

**Description :**

- Récupère la liste des aliments appartenant à un type spécifique.

**Requête :**

- **Méthode HTTP :** GET
- **URL :** `/aliments.php/{code_type}`

**Paramètres d'URL :**

- `{code_type}` : Code du type d'aliment (entier).

**Exemple de requête :**
```
GET /aliments.php/1
```

**Exemple de réponse :**
```
[
  {
    "ID_ALIMENT": 1,
    "NOM_ALIMENT": "Pomme",
    "CODE_TYPE": 1
  },
  {
    "ID_ALIMENT": 2,
    "NOM_ALIMENT": "Banane",
    "CODE_TYPE": 1
  },
  ...
]
```

#### Pagination des aliments

**Description :**

- Récupère une liste paginée des aliments, avec possibilité de filtrer par type.

**Requête :**

- **Méthode HTTP :** GET
- **URL :** `/aliments.php?page={page}&limit={limit}&type={code_type}`

**Paramètres de requête :**

- `page` : Numéro de la page à récupérer (entier, obligatoire).
- `limit` : Nombre d'aliments par page (entier, obligatoire).
- `type` : (Optionnel) Code du type d'aliment pour filtrer les résultats.

**Exemple de requête sans filtre de type :**
```
GET /aliments.php?page=1&limit=10
```

**Exemple de requête avec filtre de type :**
```
GET /aliments.php?page=1&limit=10&type=2
```

**Exemple de réponse :**
```
[
  {
    "ID_ALIMENT": 11,
    "NOM_ALIMENT": "Poulet",
    "CODE_TYPE": 2
  },
  {
    "ID_ALIMENT": 12,
    "NOM_ALIMENT": "Bœuf",
    "CODE_TYPE": 2
  },
  ...
]
```

### POST /aliments.php

**Description :**

- Crée un nouvel aliment dans la base de données.

**Requête :**

- **Méthode HTTP :** POST
- **URL :** /aliments.php
- **En-tête :** `Content-Type: application/json`
- **Corps de la requête :** JSON

**Champs du corps de la requête :**

- `name` : Nom de l'aliment (string, obligatoire).
- `type` : Code du type d'aliment (entier, obligatoire).

**Exemple de corps de requête :**
```
{
  "name": "Saumon",
  "type": 3
}
```

**Exemple de réponse en cas de succès :**
```
{
  "status": "success",
  "message": "Aliment créé avec succès"
}
```

**Exemple de réponse en cas d'erreur (aliment existant) :**
```
{
  "status": "error",
  "message": "L'aliment existe déjà"
}
```

### PUT /aliments.php

**Description :**

- Met à jour les informations d'un aliment existant.

**Requête :**

- **Méthode HTTP :** PUT
- **URL :** aliments.php
- **En-tête :** `Content-Type: application/json`
- **Corps de la requête :** JSON

**Champs du corps de la requête :**

- `name` : Nom de l'aliment à mettre à jour (string, obligatoire).
- `type` : Nouveau code du type d'aliment (entier, obligatoire).

**Exemple de corps de requête :**
```
{
  "name": "Saumon",
  "type": 4
}
```

**Exemple de réponse :**
```
{
  "status": "success",
  "message": "Aliment mis à jour avec succès"
}
```

### DELETE /aliments.php

**Description :**

- Supprime un aliment de la base de données.

**Requête :**

- **Méthode HTTP :** DELETE
- **URL :** /aliments.php
- **En-tête :** `Content-Type: application/json`
- **Corps de la requête :** JSON

**Champs du corps de la requête :**

- `name` : Nom de l'aliment à supprimer (string, obligatoire).

**Exemple de corps de requête :**
```
{
  "name": "Saumon"
}
```

**Exemple de réponse :**
```
{
  "status": "success",
  "message": "Aliment supprimé avec succès"
}
```


---

## Repas

### GET /repas.php

**Description :**

Récupère la liste de tous les repas.

**Réponse :**
```
[
  {
    "CODE_REPAS": 1,
    "LOGIN": "user1",
    "DATE": "2023-10-15 12:30:00"
  },
  {
    "CODE_REPAS": 2,
    "LOGIN": "user2",
    "DATE": "2023-10-16 19:00:00"
  },
  ...
]
```

### POST /repas.php

**Description :**

Crée un nouveau repas pour un utilisateur.

**Corps de la requête pour créer un repas :**
```
{
  "login": "user1",
  "date": "2023-10-17 08:00:00"
}
```

**Réponse :**
```
{
  "code_repas": 3
}
```

### POST /repas.php (ajout d'aliment)

**Description :**

Ajoute un aliment à un repas existant.

**Corps de la requête pour ajouter un aliment :**
```
{
  "code_repas": 3,
  "nom_aliment": "Café",
  "quantite": 1
}
```

**Réponse :**
```
{
  "status": "success",
  "message": "Aliment ajouté au repas"
}
```

### DELETE /repas.php/:code_repas

**Description :**

Supprime un repas spécifique.

**Paramètres :**

- `:code_repas` : Code du repas à supprimer.

**Réponse :**

```
{
  "status": "success",
  "message": "Repas supprimé"
}
```

---

## Types d'aliments

### GET /type-aliments.php

**Description :**

Récupère la liste de tous les types d'aliments disponibles.

**Réponse :**
```
[
  {
    "CODE_TYPE": 1,
    "NOM_TYPE": "Fruits"
  },
  {
    "CODE_TYPE": 2,
    "NOM_TYPE": "Viandes"
  },
  ...
]
```

### POST /type-aliments.php

**Description :**

Ajoute un nouveau type d'aliment.

**Corps de la requête :**
```
{
  "nom_type_aliment": "Légumes"
}
```

**Réponse :**
```
{
  "status": "success",
  "message": "Type aliment créé"
}
```

### PUT /type-aliments.php/id/:id

**Description :**

Met à jour les informations d'un type d'aliment existant.

**Paramètres :**

- `:id` : Code du type d'aliment à mettre à jour.

**Corps de la requête :**
```
{
  "nom_type_aliment": "Céréales"
}
```

**Réponse :**
```
{
  "status": "success",
  "message": "Type aliment mis à jour"
}
```

### DELETE /type-aliments.php/id/:id

**Description :**

Supprime un type d'aliment spécifique.

**Paramètres :**

- `:id` : Code du type d'aliment à supprimer.

**Réponse :**
```
{
  "status": "success",
  "message": "Type aliment supprimé"
}
```

---

## Ratios

### GET /ratio.php

**Description :**

Récupère la liste de tous les ratios disponibles.

**Réponse :**
```
[
  {
    "CODE_RATIO": 1,
    "NOM_RATIO": "Protéines"
  },
  {
    "CODE_RATIO": 2,
    "NOM_RATIO": "Glucides"
  },
  {
    "CODE_RATIO": 3,
    "NOM_RATIO": "Lipides"
  },
  ...
]
```

### GET /ratio.php/:aliment

**Description :**

Récupère les ratios associés à un aliment spécifique.

**Paramètres :**

- `:aliment` : Nom de l'aliment (remplacer les espaces par des tirets `-` si nécessaire).

**Exemple d'appel :**
```
GET /ratio.php/Pomme
```

**Réponse :**
```
[
  {
    "QUANTITE_RATIO": 0.3,
    "NOM_RATIO": "Protéines"
  },
  {
    "QUANTITE_RATIO": 14,
    "NOM_RATIO": "Glucides"
  },
  {
    "QUANTITE_RATIO": 0.2,
    "NOM_RATIO": "Lipides"
  },
  ...
]
```

### GET /ratio.php/:aliment/:code_ratio

**Description :**

Récupère la quantité d'un ratio spécifique pour un aliment donné.

**Paramètres :**

- `:aliment` : Nom de l'aliment (remplacer les espaces par des tirets `-` si nécessaire).
- `:code_ratio` : Code du ratio (identifiant numérique).

**Exemple d'appel :**
```
GET /ratio.php/Pomme/2
```

**Réponse :**
```
[
  {
    "QUANTITE_RATIO": 14,
    "NOM_RATIO": "Glucides"
  }
]
```

### POST /ratio.php

**Description :**

Crée un nouveau ratio.

**Corps de la requête :**
```
{
  "name": "Fibres"
}
```

**Réponse :**
```
{
  "status": "success",
  "message": "Ratio created successfully"
}
```

### PUT /ratio.php

**Description :**

Met à jour un ratio existant.

**Corps de la requête :**
```
{
  "id": 4,
  "name": "Fibres alimentaires"
}
```

**Réponse :**
```
{
  "status": "success",
  "message": "Ratio updated successfully"
}
```

### DELETE /ratio.php

**Description :**

Supprime un ratio existant.

**Corps de la requête :**
```
{
  "id": 4
}
```

**Réponse :**
```
{
  "status": "success",
  "message": "Ratio deleted successfully"
}
```

## Notes

- Tous les endpoints sont relatifs au préfixe de l'API (par exemple, `http://votre_domaine/backend/API`). Voir ReadMe dans le front pour configurer le préfixe
- Les dates sont généralement au format `YYYY-MM-DD` ou `YYYY-MM-DD HH:MM:SS`.
- Les codes (`CODE_REPAS`, `CODE_TYPE`, `ID_ALIMENT`, etc.) sont des identifiants numériques uniques.
- Les réponses en cas d'erreur contiennent généralement un champ `status` avec la valeur `"error"` et un champ `message` décrivant l'erreur.
- Les endpoints `pratique-sport`, `sexe`, et `tranche-age` ont uniquement une méthode GET qui renvoie toutes les pratiques sport, sexes et tranche ages.
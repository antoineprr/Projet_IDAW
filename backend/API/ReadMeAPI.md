# Fonctionnement API REST

## Endpoint : utilisateurs

### GET .../backend/API/utilisateurs.php

réponse : 
```
{
        "LOGIN": "ackbar",
        "CODE_AGE": 1,
        "CODE_SEXE": 1,
        "CODE_SPORT": 1,
        "MDP": "MDPAckbar",
        "NOM": "ACKBAR",
        "PRENOM": "ADMIRAL ACKBAR",
        "DATE_NAISSANCE": "1947-05-15",
        "EMAIL": "ackbar@starwars.com"
    },
    {
        "LOGIN": "bailorgana",
        "CODE_AGE": 1,
        "CODE_SEXE": 1,
        "CODE_SPORT": 1,
        "MDP": "MDPBailOrgana",
        "NOM": "BAIL",
        "PRENOM": "BAIL ORGANA",
        "DATE_NAISSANCE": "1955-02-12",
        "EMAIL": "bail-organa@starwars.com"
    }, ...
```

### GET .../backend/API/utilisateurs.php/login/:login/

réponse : 
```
{
        "LOGIN": "test_post",
        "CODE_AGE": 1,
        "CODE_SEXE": 1,
        "CODE_SPORT": 1,
        "MDP": "test_post_mdp_updated",
        "NOM": "test_post_updated",
        "PRENOM": "test_post_pre_updated",
        "DATE_NAISSANCE": "1999-03-01",
        "EMAIL": "test_post_updated@test.com"
    }
```

### POST .../backend/API/utilisateurs.php

Body:
```
{
        "LOGIN": "test_post",
        "CODE_AGE": 1,
        "CODE_SEXE": 1,
        "CODE_SPORT": 1,
        "MDP": "test_post_mdp_updated",
        "NOM": "test_post_updated",
        "PRENOM": "test_post_pre_updated",
        "DATE_NAISSANCE": "1999-03-01",
        "EMAIL": "test_post_updated@test.com"
    }
```

Réponse :
```
{
    "status": "success",
    "message": "Utilisateur ajouté"
}
```

### PUT .../backend/API/utilisateurs.php/login/:login/

Body: 
```
{
        "CODE_AGE": 1,
        "CODE_SEXE": 1,
        "CODE_SPORT": 1,
        "MDP": "test_post_mdp_updated",
        "NOM": "test_post_updated",
        "PRENOM": "test_post_pre_updated",
        "DATE_NAISSANCE": "1999-03-01",
        "EMAIL": "test_post_updated@test.com"
    }
```

Réponse:
```
{
    "status": "success",
    "message": "Utilisateur 'test_post' updated"
}
```

## Endpoint : type_aliments

### GET .../backend/API/type-aliments.php

Retourne tous les types d'aliments disponibles dans la base de données.

#### Réponse : 
```
[
    {
        "ID": 1,
        "NOM": "Fruit",
    },
    {
        "ID": 2,
        "NOM": "Légume",
    },
    ...
]
```

### POST .../backend/API/type-aliments.php

Ajoute un nouveau type d’aliment dans la base de données.

#### Body :
```
{
    "nom_type_aliment": "Céréales"
}
```

#### Réponse :
```
{
    "status": "success",
    "message": "Type aliment '3' created"
}
```

### PUT .../backend/API/type-aliments.php/id/:id/

Met à jour les informations d’un type d’aliment spécifique dans la base de données.

#### Body :
```
{
    "nom_type_aliment": "Légumineuses"
}
```

#### Réponse :
```
{
    "status": "success",
    "message": "Type aliment '3' updated"
}
```

### DELETE .../backend/API/type-aliments.php/id/:id/

Supprime un type d’aliment spécifique de la base de données.

#### Réponse :
```
{
    "status": "success",
    "message": "Type aliment '3' deleted"
}
```
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
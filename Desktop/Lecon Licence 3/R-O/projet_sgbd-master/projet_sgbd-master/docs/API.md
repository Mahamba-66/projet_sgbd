# API Documentation

## Gestion des Parrainages

### Routes

#### Liste des parrainages
```
GET /admin/sponsorships
```
Retourne la liste paginée des parrainages avec leurs relations.

**Réponse**
```json
{
    "data": [
        {
            "id": 1,
            "candidate": {
                "id": 1,
                "name": "Nom du candidat"
            },
            "voter": {
                "id": 2,
                "name": "Nom de l'électeur"
            },
            "status": "pending",
            "created_at": "2025-03-18T04:59:21Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 10,
        "total": 50
    }
}
```

#### Validation d'un parrainage
```
PUT /admin/sponsorships/{id}/validate
```
Valide un parrainage spécifique.

**Conditions**
- Une période de parrainage doit être active
- Le parrainage doit être en attente

**Réponse succès**
```json
{
    "message": "Parrainage validé avec succès",
    "sponsorship": {
        "id": 1,
        "status": "validated",
        "updated_at": "2025-03-18T05:00:00Z"
    }
}
```

**Réponse erreur**
```json
{
    "error": "Impossible de valider : aucune période de parrainage n'est active"
}
```

#### Rejet d'un parrainage
```
PUT /admin/sponsorships/{id}/reject
```
Rejette un parrainage spécifique.

**Réponse**
```json
{
    "message": "Parrainage rejeté",
    "sponsorship": {
        "id": 1,
        "status": "rejected",
        "updated_at": "2025-03-18T05:00:00Z"
    }
}
```

## Périodes de Parrainage

### Routes

#### Création d'une période
```
POST /admin/sponsorship-periods
```

**Paramètres**
```json
{
    "start_date": "2025-03-18T00:00:00Z",
    "end_date": "2025-04-18T23:59:59Z",
    "description": "Période de parrainage pour l'élection 2025",
    "is_active": true
}
```

**Réponse**
```json
{
    "message": "Période de parrainage créée avec succès",
    "period": {
        "id": 1,
        "start_date": "2025-03-18T00:00:00Z",
        "end_date": "2025-04-18T23:59:59Z",
        "description": "Période de parrainage pour l'élection 2025",
        "is_active": true
    }
}
```

#### Liste des périodes
```
GET /admin/sponsorship-periods
```

**Réponse**
```json
{
    "data": [
        {
            "id": 1,
            "start_date": "2025-03-18T00:00:00Z",
            "end_date": "2025-04-18T23:59:59Z",
            "description": "Période de parrainage pour l'élection 2025",
            "is_active": true
        }
    ]
}
```

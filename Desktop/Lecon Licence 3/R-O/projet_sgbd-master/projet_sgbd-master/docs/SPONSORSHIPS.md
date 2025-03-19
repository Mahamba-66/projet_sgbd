# Gestion des Parrainages

Ce document décrit le système de gestion des parrainages dans l'application électorale.

## Périodes de Parrainage

### Définition
Une période de parrainage est un intervalle de temps pendant lequel les parrainages peuvent être validés par les administrateurs. Une seule période peut être active à la fois.

### Caractéristiques
- Date de début
- Date de fin
- Description (optionnelle)
- Statut (active/inactive)

### Règles
1. Une seule période peut être active à la fois
2. Les parrainages ne peuvent être validés que pendant une période active
3. Les dates de début et de fin sont obligatoires
4. La date de fin doit être postérieure à la date de début

## Parrainages

### États possibles
- **En attente (pending)** : État initial d'un parrainage
- **Validé (validated)** : Parrainage approuvé par un administrateur
- **Rejeté (rejected)** : Parrainage refusé par un administrateur

### Processus de validation
1. L'administrateur consulte la liste des parrainages
2. Pour chaque parrainage en attente :
   - Vérification de l'existence d'une période active
   - Validation ou rejet du parrainage
   - Notification du résultat

### Règles de validation
1. Une période de parrainage doit être active
2. Le parrainage doit être en état "en attente"
3. L'électeur et le candidat doivent être actifs dans le système

## Interface d'administration

### Tableau de bord
- Vue d'ensemble des statistiques
  - Total des parrainages
  - Parrainages validés
  - Parrainages en attente
  - Parrainages rejetés

### Gestion des périodes
- Création d'une nouvelle période
- Activation/désactivation des périodes
- Visualisation de l'historique des périodes

### Liste des parrainages
- Filtrage par statut
- Tri par date
- Actions rapides (valider/rejeter)
- Détails complets disponibles

## Sécurité
- Accès restreint aux administrateurs
- Journalisation des actions
- Validation des données
- Protection contre les modifications non autorisées

## Bonnes pratiques
1. Toujours vérifier l'existence d'une période active avant de valider
2. Documenter les raisons des rejets
3. Vérifier régulièrement les statistiques
4. Maintenir une communication claire avec les utilisateurs

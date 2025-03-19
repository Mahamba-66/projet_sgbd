# Gestion des Périodes de Parrainage

## Vue d'ensemble

Le système de gestion des périodes de parrainage permet aux administrateurs de :
- Définir des périodes pendant lesquelles les parrainages sont autorisés
- Activer ou désactiver des périodes
- Suivre l'historique des périodes de parrainage

## Fonctionnalités

### 1. Liste des périodes
- Vue d'ensemble de toutes les périodes
- Statut (active/inactive)
- Dates de début et de fin
- Description optionnelle

### 2. Création d'une période
- Définition des dates de début et de fin
- Option pour activer immédiatement
- Description facultative
- Validation des dates (la date de fin doit être après la date de début)

### 3. Modification d'une période
- Modification des dates et de la description
- Possibilité d'activer/désactiver
- Validation des modifications

### 4. Suppression d'une période
- Suppression possible uniquement si aucun parrainage n'y est associé
- Confirmation requise avant suppression

## Règles de gestion

1. **Unicité de la période active**
   - Une seule période peut être active à la fois
   - L'activation d'une période désactive automatiquement toute autre période

2. **Validation des dates**
   - La date de fin doit être postérieure à la date de début
   - Les dates sont au format datetime pour une précision horaire

3. **Statut des périodes**
   - Active : période en cours
   - Inactive : période passée ou future

## Accès et sécurité

1. **Restrictions d'accès**
   - Seuls les administrateurs peuvent gérer les périodes
   - Authentification requise
   - Vérification du rôle admin via middleware

2. **Actions protégées**
   - Création de période
   - Modification de période
   - Suppression de période
   - Activation/désactivation

## Interface utilisateur

1. **Tableau de bord**
   - Affichage de la période active
   - Statut actuel du système de parrainage
   - Liens rapides vers la gestion des périodes

2. **Liste des périodes**
   - Tableau avec tri par dates
   - Indicateurs visuels de statut
   - Actions rapides (modifier, supprimer)

3. **Formulaires**
   - Validation côté client et serveur
   - Messages d'erreur clairs
   - Confirmation pour les actions importantes

## Bonnes pratiques

1. **Gestion des périodes**
   - Planifier les périodes à l'avance
   - Éviter les chevauchements
   - Maintenir des descriptions claires

2. **Maintenance**
   - Archiver les anciennes périodes
   - Vérifier régulièrement les dates
   - Documenter les changements

3. **Communication**
   - Informer les utilisateurs des changements de période
   - Afficher clairement les dates sur l'interface
   - Envoyer des notifications aux administrateurs

# Guide d'importation des électeurs

## Prérequis

1. **Accès administrateur**
   - Vous devez avoir un compte avec le rôle `admin` ou `superadmin`
   - Consultez le document [ADMIN_ACCESS.md](./ADMIN_ACCESS.md) pour plus de détails sur les droits d'accès

## Format du fichier CSV
Le fichier d'importation doit être un fichier CSV avec les colonnes suivantes :

```csv
nom,prenom,email,cin,numero_electeur,region_id,date_naissance
```

### Règles de validation

1. **Nom et Prénom**
   - Caractères autorisés : lettres, espaces et tirets
   - Pas de caractères spéciaux ou accents

2. **Email**
   - Format email standard
   - Doit être unique dans la base de données

3. **CIN (Carte d'Identité Nationale)**
   - Exactement 13 chiffres
   - Doit être unique

4. **Numéro d'électeur**
   - 10 caractères alphanumériques
   - Lettres majuscules et chiffres uniquement
   - Doit être unique

5. **Region ID**
   - Doit correspondre à une région existante dans la base de données

6. **Date de naissance**
   - Format : YYYY-MM-DD
   - Date valide

## Exemple de fichier CSV valide

```csv
nom,prenom,email,cin,numero_electeur,region_id,date_naissance
Rakoto,Jean,rakoto.jean@email.com,1234567890123,ABC123XYZ9,1,1990-05-15
Rabe,Marie,rabe.marie@email.com,9876543210123,DEF456UVW8,2,1985-08-22
```

## Processus d'importation

1. **Préparation du fichier**
   - Créer le fichier CSV avec les colonnes requises
   - S'assurer que l'encodage est en ASCII ou UTF-8
   - Vérifier que toutes les données respectent les règles de validation

2. **Upload du fichier**
   - Se connecter en tant qu'administrateur
   - Aller dans la section "Importation des électeurs"
   - Sélectionner le fichier CSV
   - Cliquer sur "Importer"

3. **Validation et importation**
   - Le système vérifie automatiquement chaque ligne
   - Les erreurs sont affichées dans un tableau détaillé
   - Les électeurs valides sont créés comme utilisateurs avec le rôle "voter"
   - Un historique de l'importation est enregistré

4. **Vérification**
   - Consulter la liste des électeurs importés
   - Vérifier l'historique d'importation
   - S'assurer que les comptes ont été créés correctement

## Résolution des problèmes courants

1. **Erreur de format de fichier**
   - Vérifier que le fichier est bien en format CSV
   - S'assurer que les noms des colonnes sont corrects
   - Vérifier l'encodage du fichier

2. **Erreurs de validation**
   - Consulter le tableau des erreurs
   - Corriger les données dans le fichier CSV
   - Réessayer l'importation

3. **Doublons**
   - Vérifier que les CIN et numéros d'électeur sont uniques
   - S'assurer que les emails ne sont pas déjà utilisés

## Notes importantes

- Faire une sauvegarde de la base de données avant une importation massive
- Tester d'abord avec un petit fichier (2-3 lignes) avant d'importer un grand nombre d'électeurs
- En cas d'erreur, tous les changements sont annulés (transaction)

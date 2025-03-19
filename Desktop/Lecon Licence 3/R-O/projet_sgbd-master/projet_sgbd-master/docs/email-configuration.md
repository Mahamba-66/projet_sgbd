# Configuration de l'envoi d'emails

## Configuration Gmail SMTP

Pour configurer l'envoi d'emails via Gmail SMTP dans l'application, suivez ces étapes :

1. Dans le fichier `.env`, ajoutez ces configurations :
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre_email@gmail.com
MAIL_PASSWORD="votre_mot_de_passe_application"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre_email@gmail.com
MAIL_FROM_NAME="PARRAINAGE"
```

2. Pour Gmail spécifiquement :
   - Activez l'authentification à deux facteurs sur votre compte Google
   - Générez un mot de passe d'application :
     1. Allez dans les paramètres de votre compte Google
     2. Sécurité > Authentification à 2 facteurs
     3. En bas de la page, cliquez sur "Mots de passe des applications"
     4. Sélectionnez "Autre (nom personnalisé)" et donnez un nom comme "Laravel Parrainage"
     5. Copiez le mot de passe généré et utilisez-le comme `MAIL_PASSWORD` dans `.env`

3. Après modification du fichier `.env`, exécutez :
```bash
php artisan config:clear
php artisan cache:clear
```

4. Redémarrez le serveur Laravel :
```bash
php artisan serve
```

## Test de la configuration

Pour tester si l'envoi d'email fonctionne :

1. Visitez : `http://127.0.0.1:8000/test-mail-debug`
2. Vérifiez la réponse JSON qui devrait montrer :
   - "status": "success"
   - "driver": "smtp"
   - "host": "smtp.gmail.com"

## Dépannage

Si les emails ne sont pas reçus :
1. Vérifiez le dossier spam
2. Assurez-vous que le mot de passe d'application est correct
3. Vérifiez que l'authentification à deux facteurs est activée
4. Confirmez que les paramètres SMTP sont corrects dans `.env`
5. Vérifiez les logs Laravel pour les erreurs : `storage/logs/laravel.log`

## Sécurité

- Ne commettez JAMAIS le fichier `.env` dans Git
- Utilisez toujours des mots de passe d'application, jamais votre mot de passe Gmail principal
- Gardez vos clés d'accès confidentielles

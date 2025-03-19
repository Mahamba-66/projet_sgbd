<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #007bff;
            margin-bottom: 20px;
        }
        .code {
            text-align: center;
            font-size: 32px;
            letter-spacing: 5px;
            color: #007bff;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Vérification de votre compte</h2>
        </div>

        <p>Bonjour {{ $user->name }},</p>

        <p>Bienvenue sur notre système électoral. Pour finaliser votre inscription, veuillez utiliser le code de vérification suivant :</p>

        <div class="code">
            {{ $user->verification_code }}
        </div>

        <p>Ce code expirera dans 60 minutes pour des raisons de sécurité.</p>

        <p><strong>Important :</strong></p>
        <ul>
            <li>Ne partagez ce code avec personne</li>
            <li>Notre équipe ne vous demandera jamais ce code</li>
            <li>Si vous n'avez pas demandé ce code, ignorez cet email</li>
        </ul>

        <div class="footer">
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
            <p>&copy; {{ date('Y') }} Système Electoral. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>

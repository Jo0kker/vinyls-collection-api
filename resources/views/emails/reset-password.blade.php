<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation du mot de passe</title>
    <style>
        /* Styles généraux */
        body {
            font-family: Arial, sans-serif;
            color: #333333;
        }
        h1 {
            color: #4f114b;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: #ffffff;
            border: 1px solid #cccccc;
        }
        .button {
            display: inline-block;
            background-color: #4f114b;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Réinitialisation du mot de passe</h1>
    <p>Nous avons reçu une demande de réinitialisation de votre mot de passe. Veuillez cliquer sur le bouton ci-dessous pour procéder à la réinitialisation.</p>
    <p>
        <a class="button" href="{{ $actionUrl }}">Réinitialiser mon mot de passe</a>
    </p>
    <p>Si vous n'avez pas demandé de réinitialisation de mot de passe, aucune action n'est requise.</p>
    <p>Cordialement, {{ config('app.name') }}</p>
</div>
</body>
</html>

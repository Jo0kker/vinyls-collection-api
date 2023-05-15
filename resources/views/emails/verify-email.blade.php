<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de l'adresse e-mail</title>
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
    <h1>Vérification de l'adresse e-mail</h1>
    <p>Merci de vous être inscrit sur notre application ! Avant de commencer, vous devez vérifier votre adresse e-mail en cliquant sur le bouton ci-dessous.</p>
    <p>
        <a class="button" href="{{ $actionUrl }}">Vérifier mon adresse e-mail</a>
    </p>
    <p>Si vous n'avez pas créé de compte, aucune action n'est requise.</p>
    <p>Cordialement, {{ config('app.name') }}</p>
</div>
</body>
</html>

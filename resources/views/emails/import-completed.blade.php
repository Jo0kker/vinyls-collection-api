<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importation des Collections Terminée</title>
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
    <h1>Importation des Collections Terminée</h1>
    <p>Bonjour,</p>
    <p>Nous avons le plaisir de vous informer que l'importation de vos collections Discogs est terminée. Un total de {{ $collectionsCount }} collections ont été synchronisées avec succès.</p>
    <p>Vous pouvez maintenant consulter vos collections mises à jour dans votre espace personnel.</p>
    <p>Merci d'utiliser notre application !</p>
    <p>Cordialement, {{ config('app.name') }}</p>
</div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Nouvelle Réponse au Sondage</title>
</head>
<body>
<h1>Une nouvelle réponse a été soumise !</h1>

<p>Bonjour,</p>

<p>Une nouvelle réponse (ID: **{{ $answer->id }}**) a été enregistrée pour votre sondage.</p>



<p>Détails de la réponse : {{ $answer->content }}</p> --}}

<p>Merci.</p>
</body>
</html>

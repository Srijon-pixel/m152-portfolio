<!DOCTYPE html>
<html lang="en">
<!--
Projet : Créer un portfolio
Auteur : Srijon Rahman
Date : 23.01.23
Détail : Page d'accueil du site
-->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/base.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">

    <title>Home</title>
</head>

<body>
    <?php
    session_start(); // ouverture de la session
    require_once './functions.php'; // récuperation de toute les fonctions


    ?>
    <header>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item" class="bi bi-house"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="./poste.php">Post</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Login</a></li>
                </ul>
            </div>
        </nav>
        <h1>Bienvenue sur mon portfolio</h1>

    </header>
    <main>
        <div class="card">
            <img src="./img/photo_profil.jpg" alt="photo">
            <div class="container">
                <p>Nom : Srijon Rahman</p>
                <p>Passion : Jouer aux jeux vidéo</p>
            </div>
        </div>
        <?php // On va afficher après le formulaire les images de l'utilisateur
        $imgs = LoadUserEnc64Images();
        foreach ($imgs as $img) {
            echo '<img src="'.$img->encodeImage.'">';

            // On affiche directement dans l’attribut src d’un tag 
            echo '';
            echo '';
        }
        ?>
    </main>
    <footer></footer>

</body>

</html>
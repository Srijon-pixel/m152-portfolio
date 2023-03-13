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
    <style>
        .card {
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            width: 12%;
            background-color: white;
            border-radius: 5px;
            margin: 1%;
        }

        .card:hover {
            box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .container {
            padding: 2px 16px;
        }

        img {
            border-radius: 5px 5px 0 0;

        }

        main {
            flex-wrap: wrap;
        }
    </style>
    <title>Home</title>
</head>

<body>
    <?php
    session_start(); // ouverture de la session
    require_once './functions.php'; // récuperation de toute les fonctions
    define('DELETE_BUTTON', "Supprimer");
    define('UPDATE_BUTTON', "Modifier");


    if (isset($_POST['DEL'])) {
        $idDelete = filter_input(INPUT_POST, "poste");
        $idDelete = intval($idDelete);
    }
   

    if ($idDelete > 0) {
        if (deletePostWithMedia($idDelete) == false) {
            echo "Le produit ne peut pas être supprimé. Une erreur s'est produite.";
        }else{
            header('Location: index.php');
        }
    }
    if (isset($_POST['UPD'])) {
        $idModify = filter_input(INPUT_POST, "poste");
        $idModify = intval($idModify);
        $_SESSION['idModifyPost'] = $idModify;
        header('Location: modifyPost.php');
    }
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
            <img src="./img/edit.png" alt="éditer">
            <div class="container">
                <p>Nom : Srijon Rahman</p>
                <p>Passion : Jouer aux jeux vidéo</p>
            </div>
        </div>
        <?php // On va afficher après le formulaire les images de l'utilisateur
        $imgs = getPostWithMedia();
        foreach ($imgs as $img) {
            echo "<div class=\"card\">";
            echo '<img src="'.$img->encodeImage.'">';
            echo '<form action="" method="post"><input type="hidden" name="poste" value="' . $img->idMedia . '">';
            echo '<input type="submit" name="DEL" value="' . DELETE_BUTTON . '"><input type="submit" name="UPD" value="' . UPDATE_BUTTON . '"></form>';
            echo '</div>';
        }
        ?>
    </main>
    <footer></footer>

</body>

</html>
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
    define('DELETE_BUTTON', "Supprimer");
    define('UPDATE_BUTTON', "Modifier");

    $idDelete = -1;
    $idModify = -1;
    if (isset($_POST['DEL'])) {
        $idDelete = filter_input(INPUT_POST, "poste");
        $idDelete = intval($idDelete);
    }


    if ($idDelete > 0) {
        if (deletePostWithMedia($idDelete) == false) {
            echo "Le produit ne peut pas être supprimé. Une erreur s'est produite.";
        } else {
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
                    <li class="nav-item"><a class="nav-link" href="./poste.php">Post</a></li>
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
        $medias = getPostWithMedia();
        foreach ($medias as $media) {
            echo "<div class=\"card\">";
            if(preg_match('/^video/i', $media->typeMedia)){
                echo '<video src="' . $media->encode . '" controls></video>';

            }else if (preg_match('/^image/i', $media->typeMedia)) {
                echo '<img src="' . $media->encode . '">';

            }else if (preg_match('/^audio/i', $media->typeMedia)) {
                echo '<audio src="' . $media->encode . '" controls></audio>';

            }
            echo '<form action="" method="post"><input type="hidden" name="poste" value="' . $media->idMedia . '">';
            echo '<input type="submit" name="DEL" value="' . DELETE_BUTTON . '"><input type="submit" name="UPD" value="' . UPDATE_BUTTON . '"></form>';
            echo '</div>';
        }
        ?>
    </main>
    <footer></footer>

</body>

</html>
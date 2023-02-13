<!DOCTYPE html>
<html lang="en">
<!--
Projet : Créer un portfolio
Auteur : Srijon Rahman
Date : 23.01.23
Détail : Va ajouter les postes de l'utilisateur dans la page home. Le poste contiendra une image et une description
-->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/base.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>Post</title>
</head>

<body>
    <?php
    session_start(); // ouverture de la session
    require_once './functions.php'; // récuperation de toutes les fonctions

    const COL_ERROR = "red";

    $img = "";
    $description = "";
    

    $colImg = "";
    $colDescription = "";

    //Test si les données des champs seront dans la BD ou pas
    if (isset($_POST['poster'])) {
        $dateCreation = date("Y-m-d");
        $img = filter_input(INPUT_POST, 'img');
        if ($img == false) {
            $colImg = COL_ERROR;
        }

        $description = filter_input(INPUT_POST, 'description');
        if ($description == false) {
            $colDescription = COL_ERROR;
        }

        if (!isset($_FILES['userfile']) || !is_uploaded_file($_FILES['userfile']['tmp_name'])) {
            echo ('Problème de transfert');
            exit;
        }
        if (!SaveUserEnc64Image($email, file_get_contents($_FILES['userfile']['tmp_name']), $_FILES['userfile']['type'], $_FILES['userfile']['name'])) {
            echo ('Problème pour insérer une image dans la base');
            exit;
        }

        if ($colDescription != COL_ERROR && $colImg != COL_ERROR) {
            if (addPost($description, $dateCreation, $img)) {
                echo '<script>alert("Le poste a été ajouté")</script>';
            }
        } else {
            echo '<script>alert("Pas possible il vous manque des valeurs ou des valeurs sont fausses")</script>';
        }
    }
    ?>
    <header>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="./index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Post</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Login</a></li>
                </ul>
            </div>
        </nav>

    </header>
    <main>
        <input type="hidden" name="MAX_FILE_SIZE" value="300000">
        <label for="imgPost">Images</label>
        <input type="file" name="imgPost" id="imgPost"> <br>
        <textarea name="description" id="description" cols="30" rows="10"></textarea>
        <input type="submit" name="poster" value="Poster">
        <?php


        // On va afficher après le formulaire les images de l'utilisateur
        $imgs = LoadUserEnc64Images();
        foreach ($imgs as $img) {
            echo '';
            echo $img->nomFichierMedia;
            echo '';
            // On affiche directement dans l’attribut src d’un tag 
            echo '';
            echo '';
        }


        ?>
    </main>
    <footer></footer>
</body>

</html>
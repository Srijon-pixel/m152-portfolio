<!DOCTYPE html>
<html lang="en">
<!--
Projet : Créer un portfolio
Auteur : Srijon Rahman
Date : 23.01.23
Détail : Va ajouter les postes de l'utilisateur qui seront affichés dans la page home. Le poste contiendra une image et une description
-->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>Post</title>
</head>

<body>
    <?php
    session_start(); // ouverture de la session
    require_once './functions.php'; // récuperation de toutes les fonctions

    const COL_ERROR = "red";

  
    $description = "";


    $colImg = "";
    $colDescription = "";

    //Test si les données des champs seront dans la BD ou pas
    if (isset($_POST['poster'])) {
        $dateCreation = date("Y-m-d");

        $description = filter_input(INPUT_POST, 'description');
        if ($description == false) {
            $colDescription = COL_ERROR;
        }


        if (isset($_FILES['imgPost']) && !is_uploaded_file($_FILES['imgPost']['tmp_name'])) {
            echo ('Problème de transfert');
            $colImg = COL_ERROR;
        }

        if ($colDescription != COL_ERROR && $colImg != COL_ERROR) {
            $idPost = addPost($description, $dateCreation);
            if ($idPost !== false) {
              
                if (addMedia2Post($idPost, $_FILES['imgPost']['name'], file_get_contents($_FILES['imgPost']['tmp_name']), $_FILES['imgPost']['type'])) {
                            header('Location: index.php');
                    exit;
                }
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
                    <li class="nav-item"><a class="nav-link" href="./index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Post</a></li>
                </ul>
            </div>
        </nav>

    </header>
    <main>
        <form action="#" method="post" enctype="multipart/form-data">
            <!--input type="hidden" name="MAX_FILE_SIZE" value="300000"-->
            <label for="imgPost">Images</label><br>
            <input type="file" name="imgPost" id="imgPost" multiple> <br>
            <label for="description" style="color:<?php echo $colDescription; ?>">Description</label><br>
            <textarea name="description" id="description" cols="30" rows="10" value="<?php echo $description; ?>"></textarea><br>
            <input type="submit" name="poster" value="Poster">
        </form>
        <?php




        ?>
        </form>
    </main>
    <footer></footer>
</body>

</html>
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
    require_once './class/media.php'; // récuperation de toutes les fonctions

    const COL_ERROR = "red";


    $description = "";
    $colMedia = "";
    $colDescription = "";

    // Vérifie si le bouton poster a été cliqué
    if (isset($_POST['poster'])) {
        $description = filter_input(INPUT_POST, 'description');
        if ($description == false || $description = "") {
            $colDescription = COL_ERROR;
            echo "Manque la description";
        }
        $dateCreation = date("Y-m-d");
        $mediaToInsert = array();

        foreach ($_FILES['mediaPost']['tmp_name'] as $key => $tmpName) {
            $type = $_FILES['mediaPost']['type'][$key];
            $error = $_FILES['mediaPost']['error'][$key];
            if ($error == 0) {
                // Vérifier que le type de fichier est une image, vidéo ou audio
                if (preg_match('/^image/i', $type) || preg_match('/^video/i', $type) || preg_match('/^audio/i', $type)) {
                    // Vérifier que le fichier a bien été uploadé
                    if (is_uploaded_file($tmpName)) {
                        $fileContent = file_get_contents($tmpName);
                        // Vérifier que le contenu du fichier a bien été récupéré
                        if ($fileContent !== false) {
                            
                            $mediaToInsert[] = new EMedia(0, $fileContent, $type);
                        } else {
                            $colMedia = COL_ERROR;
                            echo "Erreur de récuperation du contenu du fichier.";
                        }
                    } else {
                        $colMedia = COL_ERROR;
                        echo "Erreur de récuperation du fichier.";
                    }
                } else {
                    $colMedia = COL_ERROR;
                    echo "Erreur de type de fichier.";
                }
            } else {
                $colMedia = COL_ERROR;
                echo "Erreur sur le fichier";
            }
        }

        $idPost = addPost($description, $dateCreation);
        //Insertion dans la base de donnée
        if (addMedias2Post($idPost, $mediaToInsert)) {
            header('Location: index.php');
            exit;
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
            <input type="hidden" name="MAX_FILE_SIZE" value="70000000">
            <label for="mediaPost" style="color:<?php echo $colMedia; ?>">Medias</label><br>
            <input type="file" name="mediaPost[]" id="mediaPost" multiple accept="video/*, image/*, audio/*"> <br>
            <label for="description" style="color:<?php echo $colDescription; ?>">Description</label><br>
            <textarea name="description" id="description" cols="30" rows="10" value="<?php echo $description; ?>"></textarea><br>
            <input type="submit" name="poster" value="Poster">
        </form>
        <?php




        ?>
        </form>
    </main>
    <footer>
        &copy;Fait par Mofassel Haque Srijon Rahman <br>
        Contact : srijon.rhmn@eduge.ch
    </footer>
</body>

</html>
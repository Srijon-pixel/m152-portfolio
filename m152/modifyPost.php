<!DOCTYPE html>
<html lang="en">
<!--
Projet : Créer un portfolio où l'utilisateur pourra poster des message avec un fichier, image, audio et vidéo
Auteur : Srijon Rahman
Date : 23.01.23
Détail : Va ajouter les postes de l'utilisateur qui seront affichés dans la page home. Le poste contiendra une image et une description
-->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <title>modifer le poste</title>
</head>

<body>
    <?php
    session_start(); // ouverture de la session
    require_once './functions.php'; // récuperation de toutes les fonctions
    
    const COL_ERROR = "red";
    $colMedia = "";

    $idMedia = $_SESSION['idModifyMedia'];
    // Vérifie si le bouton poster a été cliqué
    if (isset($_POST['modifier']) && isset($_FILES['mediaPost'])) {
        $type = $_FILES['mediaPost']['type'];
        $error = $_FILES['mediaPost']['error'];
        $tmpName = $_FILES['mediaPost']['tmp_name'];
        if ($error == 0) {
            // Vérifier que le type de fichier est une image, vidéo ou audio
            if (preg_match('/^image/i', $type) || preg_match('/^video/i', $type) || preg_match('/^audio/i', $type)) {
                // Vérifier que le fichier a bien été uploadé
                if (is_uploaded_file($tmpName)) {
                    $fileContent = file_get_contents($tmpName);
                    // Vérifier que le contenu du fichier a bien été récupéré
                    if ($fileContent !== false) {
                        if (modifyMedia($idMedia, $fileContent, $type)) {
                            header('Location: index.php');
                            exit;
                        }
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





    ?>
    <header>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link " href="./index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link " href="./poste.php">Post</a></li>
                </ul>
            </div>
        </nav>

    </header>
    <main>
        <form action="#" method="post" enctype="multipart/form-data">
            <input type="hidden" name="MAX_FILE_SIZE" value="70000000">
            <label for="mediaPost">Medias</label><br>
            <input type="file" name="mediaPost" id="mediaPost" multiple accept="video/*, image/*, audio/*"> <br>
            <input type="submit" name="modifier" value="Modifier le poste">
        </form>
        <?php




        ?>
        </form>
    </main>
    <footer></footer>
</body>

</html>
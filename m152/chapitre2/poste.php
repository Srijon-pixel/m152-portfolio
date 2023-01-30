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
    if (isset($_POST['add'])) {

        $img = filter_input(INPUT_POST, 'img');
        if ($img == false) {
            $colImg = COL_ERROR;
        }

        $description = filter_input(INPUT_POST, 'description');
        if ($description == false) {
            $colDescription = COL_ERROR;
        }

        if ($colDescription != COL_ERROR && $colImg != COL_ERROR) {
            if (addPost($description, $dateCreation = date("Y-m-d"), $img)) {
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
        <form method="post">
            <input type="file" id="img" name="img" accept="image/*"> <br>
            <textarea name="description" id="" cols="40" rows="2"></textarea><br>
            <input type="submit" name="add" value="Ajouter le poste" class="btn btn-primary">
        </form>
    </main>
    <footer></footer>
</body>

</html>
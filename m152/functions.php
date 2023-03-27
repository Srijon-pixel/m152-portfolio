<?php

/**
 * Projet: Site web permettant aux utilisateurs d'ajouter, de modifier et de supprimer leurs postes
 * Auteur: Srijon Rahman
 * Date: 30.01.2023
 * Détail: Page contenant toutes les fonctionnalitées du site
 */
require_once './db/database.php';
require_once './class/media.php';
require_once './class/post.php';



/** 
 * Modifie le média de la base de donnée
 * @param int $idMedia identifiant du media
 * @param string $fileContent contenu du média
 * @param string $typeMedia type du média
 */
function modifyMedia($idMedia, $fileContent, $typeMedia)
{
    $sql = "UPDATE `portfolio_img`.`media` SET `encode` = :e, `typeMedia` = :t WHERE `idMedia` = :i";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":i" => $idMedia, ":e" => 'data:' . $typeMedia . ';base64,' . base64_encode($fileContent), ":t" => $typeMedia));
    } catch (PDOException $e) {
        return false;
    }
    // Fini
    return true;
}



/**
 *Fonction pour ajouter un nouveau post dans la base de données
 *@param string $description description du post
 *@param string $dateCreation date de création du post au format YYYY-MM-DD
 *@return int|false identifiant du post ajouté ou false en cas d'erreur
 */
function addPost($description, $dateCreation)
{
    // Préparation de la requête SQL
    $sql = "INSERT INTO `portfolio_img`.`post` (`description`,`dateCreation`) VALUES(:d,:dm)";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    try {
        // Exécution de la requête SQL avec les paramètres
        $statement->execute(array(":d" => $description, ":dm" => $dateCreation));
    } catch (PDOException $e) {
        return false;
    }
    // Retourner l'identifiant du post ajouté
    return EDatabase::lastInsertId();
}


/**
 * Insère un tableau de media dans la base de données
 *@param int $idPost l'identifiant du post récupérer après l'avoir crée
 * @param EMedia[] $medias  le tableau de media à inséré dans la base de données
 * @return bool     true les données ont été insérées, false autrement.
 */
function addMedias2Post($idPost, $medias)
{
    // Début d'une transaction
    EDatabase::beginTransaction();
    // Préparation de la requête SQL pour ajouter l'image dans la table media

    $sql = "INSERT INTO `portfolio_img`.`media` (`encode`, `typeMedia`) 
    VALUES ";
    // La boucle for itère sur chaque élément de l'array $medias.
    for ($i = 0; $i < count($medias); $i++) {
      
        // On récupère la valeur de la propriété 'typeMedia' de l'élément courant.
        $typeMedia = $medias[$i]->typeMedia;

        // On récupère la valeur de la propriété 'encode' de l'élément courant et on l'encode en base 64.
        $encode = 'data:' . $typeMedia . ';base64,' . base64_encode($medias[$i]->encode);
        // On ajoute une ligne à la requête SQL qui insère ces valeurs dans la base de données.
        $sql .= "( \"$encode\" , \"$typeMedia\" )";
        // Si ce n'est pas le dernier élément, on ajoute une virgule à la fin pour séparer les éléments de la requête.
        if ($i != count($medias) - 1) {
            $sql .= ',';
        }
    }

    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    try {
        $statement->execute();
    } catch (PDOException $e) {
        EDatabase::rollBack();
        return false;
    }
    //Récupère le dernier id qui vient d'être créer.
    $idMedia = EDatabase::lastInsertId();
    // Vérifie si la liaison entre le post et le media a été ajoutée à la base de données
    // Si elle n'a pas été ajoutée, annule la transaction et renvoie false
    if (addPostHasMedia($idPost, $idMedia) == false) {
        EDatabase::rollBack();
        return false;
    }
    EDatabase::commit();
    return true;
}


/**
 *Fonction pour ajouter une image à un post
 *@param int $idPost identifiant du post auquel ajouter l'image
 *@param string $image contenu de l'image encodé en base64
 *@param string $InMimeType type MIME de l'image (ex: image/png)
 *@return bool true si l'ajout est réussi, false sinon
 */
/*function addMedia2Post($idPost, $image, $InMimeType)
{
    // Début d'une transaction
    EDatabase::beginTransaction();
    // Préparation de la requête SQL pour ajouter l'image dans la table media

    $sql = "INSERT INTO `portfolio_img`.`media` (`encode`, `typeMedia`) 
    VALUES
    (:e, :t)";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    try {
        // Exécution de la requête SQL avec les paramètres
        $statement->execute(array(":e" => $image, ":t" => $InMimeType));
    } catch (PDOException $e) {
        EDatabase::rollBack();
        return false;
    }
    $idMedia = EDatabase::lastInsertId();
    if (addPostHasMedia($idPost, $idMedia) == false) {
        EDatabase::rollBack();
        return false;
    }
    EDatabase::commit();
    return true;
}
*/



/**
 * Insère le post avec un media dans la table de la bd.
 *
 * @param [type] $idPost l'identifiant du post récupérer après l'avoir crée
 * @param [type] $idMedia l'identifiant du média récupérer après l'avoir crée
 * @return void
 */
function addPostHasMedia($idPost, $idMedia)
{

    // Préparation de la requête SQL
    $sql = "INSERT INTO `portfolio_img`.`post_has_media` (`post_idPost`, `media_idMedia`) VALUES (:p, :m)";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    try {
        // Exécution de la requête SQL avec les paramètres
        $statement->execute(array(":p" => $idPost, ":m" => $idMedia));
    } catch (PDOException $e) {
        return false;
    }
    return true;
}


/** 
 * Supprime définitivement le poste de la base de donnée 
 * @param int $idPost identifiant unique du post
 */
function deletePostwithMedia($idMedia)
{
    $sql = "DELETE FROM `portfolio_img`.`media` WHERE `media`.`idMedia` = :i";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":i" => $idMedia));
    } catch (PDOException $e) {
        return false;
    }
    // Fini
    return true;
}






/**
 * Récupère toutes les images de l'utilisateur de la base de données
 * @return array Un tableau d'objet EMedia. False si une erreur est survenue
 * 
 * @remark Les images sont stockées directement dans un enregistrement de la base de données sous forme encodée 64bits
 */
/*function getPostWithMedia()
{
    // On crée un tableau qui va contenir les objets EPost
    $arr = array();

    $sql = "SELECT p.idPost, p.description, p.dateCreation, m.encode, m.typeMedia 
            FROM post p 
            JOIN post_has_media pm ON p.idPost = pm.post_idPost 
            JOIN media m ON pm.media_idMedia = m.idMedia";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute();
    } catch (PDOException $e) {
        return false;
    }
    // On parcourt les enregistrements 
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // On crée l'objet EPost en l'initialisant avec les données provenant
        // de la base de données
        $post = new EPost(
            intval($row['idPost']),
            $row['description'],
            $row['dateCreation'],
        );
        // On crée l'objet EMedia en l'initialisant avec les données provenant
        // de la base de données
        $media = new EMedia(
            intval($row['idMedia']),
            $row['encode'],
            $row['typeMedia']
        );
        // On ajoute l'objet EMedia à l'objet EPost
        $post->addMedia($media);
        // On place l'objet EPost créé dans le tableau
        array_push($arr, $post);
    }
    // On retourne le tableau contenant la définition des posts sous forme EPost
    return $arr;
}*/

/**
 * Récupère toutes les images de l'utilisateur de la base de données
 * @return array Un tableau d'objet EMedia. False si une erreur est survenue
 * 
 * @remark Les images sont stockées directement dans un enregistrement de la base de données sous forme encodée 64bits
 */
function getPostWithMedia()
{
    $arr = array();

    $sql = "SELECT `media`.`idMedia`, `media`.`encode`, `media`.`typeMedia`
    FROM `portfolio_img`.`media`;";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute();
    } catch (PDOException $e) {
        return false;
    }
    // On parcoure les enregistrements 
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // On crée l'objet EMedia en l'initialisant avec les données provenant
        // de la base de données
        $c = new EMedia(
            intval($row['idMedia']),
            $row['encode'],
            $row['typeMedia']
        );
        // On place l'objet EMedia créé dans le tableau
        array_push($arr, $c);
    }

    // Fini
    return $arr;
}
?>
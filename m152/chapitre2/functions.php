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
 * Modifie le poste de la base de donnée
 * @param int $idPost identifiant du poste
 * @param string $description description du poste
 * @param string $dateCreation date de création du poste
 * @param string $dateModification date de modification du poste
 */
function modifyPost($idPost, $description, $dateModification)
{
    $sql = "UPDATE `portfolio_img`.`post` SET `description` = :d, `dateModification` = :dm WHERE `idPost` = :i";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":i" => $idPost, ":d" => $description, ":dm" => $dateModification));
    } catch (PDOException $e) {
        return false;
    }
    // Fini
    return true;
}

/**
 * Récupère touts les postes de la base de données
 *
 * @return array|bool Un tableau de EPost
 *                    False si une erreur
 */
function getAllPosts()
{
    $arr = array();

    $sql = "SELECT `post`.`idPost`, `media`.`nomFichierMedia` AS `nomImage`, `post`.`description`, `post`.`dateCreation`, `post`.`idMedia`
    FROM `portfolio_img`.`post`
	JOIN `media` ON post.idMedia = media.idMedia";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute();
    } catch (PDOException $e) {
        return false;
    }
    // On parcoure les enregistrements 
    while ($row = $statement->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
        // On crée l'objet EPost en l'initialisant avec les données provenant
        // de la base de données
        $c = new EPost(
            intval($row['idPost']),
            $row['description'],
            $row['dateCreation']
        );
        // On place l'objet EPost créé dans le tableau
        array_push($arr, $c);
    }

    // Fini
    return $arr;
}





/**
 * 
 */
function addPost($description, $dateCreation)
{
    $sql = "INSERT INTO `portfolio_img`.`post` (`description`,`dateCreation`) VALUES(:d,:dm)";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    try {
        $statement->execute(array(":d" => $description, ":dm" => $dateCreation));
    } catch (PDOException $e) {
        return false;
    }
    return EDatabase::lastInsertId();
}

/**
 * Fonction pour ajouter l'image à l'aide du idPost
 * @param int $idPost identifiant du post
 * @param
 */
function addMedia2Post($idPost, $nomFichierMedia, $image, $InMimeType)
{
    EDatabase::beginTransaction();

    $sql = "INSERT INTO `portfolio_img`.`media` (`nomFichierMedia`,`encode`) VALUES(:n, :e)";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    // Préparer la chaîne qui permet d'afficher directement l'image dans un tag img
    $SrcEnc64 = 'data:' . $InMimeType . ';base64,' . base64_encode($image);

    try {
        $statement->execute(array(":n" => $nomFichierMedia, ":e" => $SrcEnc64));
    } catch (PDOException $e) {
        EDatabase::rollBack();
        return false;
    }
    $idMedia = EDatabase::lastInsertId();
    if (addPostHasMedia($idPost, $idMedia) == false)
    {
        EDatabase::rollBack();
        return false;
    }
    EDatabase::commit();
    return true;
}




function addPostHasMedia($idPost, $idMedia)
{
    $sql = "INSERT INTO `portfolio_img`.`post_has_media` (`post_idPost`, `media_idMedia`) VALUES (:p, :m)";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    try {
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
function deletePostwithMedia($idPost)
{
    $sql = "DELETE FROM `portfolio_img`.`post` WHERE `post`.`idPost` = :i";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    try {
        $statement->execute(array(":i" => $idPost));
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
function getPostWithMedia()
{
    // On crée un tableau qui va contenir les objets EMedia
    $arr = array();

    $sql = "SELECT  `media`.`idMedia`, `media`.`nomFichierMedia`, `media`.`encode` FROM `portfolio_img`.`media` ";
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
        $img = new EMedia(
            intval($row['idMedia']),
            $row['nomFichierMedia'],
            $row['encode']
        );
        // On place l'objet EMedia créé dans le tableau
        array_push($arr, $img);
    }
    // On retourne le tableau contenant la définition des images sous forme EMedia
    return $arr;
}

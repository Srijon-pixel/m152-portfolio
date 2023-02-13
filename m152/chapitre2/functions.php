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

    $sql = "SELECT * FROM `portfolio_img`.`post` 
	JOIN `media` USING(idPost)
	JOIN `type` USING(idType)";
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
            $row['dateCreation'],
            $row['dateModification'],
            $row['idMedia']
        );
        // On place l'objet EPost créé dans le tableau
        array_push($arr, $c);
    }

    // Fini
    return $arr;
}


/** 
 * Intègre le poste dans la base de donnée
 * @param string $nom nom du budget
 * @param int $montant montant du budget
 */
function addPost($description, $dateCreation)
{

    $sql = "INSERT INTO `portfolio_img`.`post` (`description`,`dateCreation`) 
	VALUES(:d,:dm)";
    //$sqlMedia = "INSERT INTO `portfolio_img`.`media` (`idPost`,`idMedia`) VALUES(:ip,:m)";
    $statement = EDatabase::prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	//$statMedia = EDatabase::prepare($sqlMedia, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    try {
        $statement->execute(array(
            ":d" => $description, ":dm" => $dateCreation
        ));
       //$statMedia->execute(array(":ip" => $idPost, ":m" => $idMedia));
    } catch (PDOException $e) {
        return false;
    }
    // Fini
    return true;
}


/** 
 * Supprime définitivement le poste de la base de donnée 
 * @param int $idPost identifiant unique du post
 */
function deletePost($idPost)
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
 * Sauvegarder une image pour un utilisateur donné sous forme encodée 64bits
 * @param string $image L'image que l'utilisateur à ajouter
 * @param string $InMimeType Le type mime du fichier à sauvegarder
 * @param string $nomFichierMedia Le nom original du fichier
 * @return bool True si correctement sauvegardé, autrement False si une erreur est survenue
 * 
 * @remark Les images sont stockées directement dans un enregistrement de la base de données sous forme encodée 64bits
 */
function SaveUserEnc64Image($nomFichierMedia, $image , $InMimeType){
    // Insérer le contenu directement dans l'enregistrement de la base de données
    $sql = "INSERT INTO `portfolio_img`.`media` (`nomFichierMedia`,`encodeImage`) VALUES(:n, :e)";
    $statement = EDatabase::prepare($sql);
    // Préparer la chaîne qui permet d'afficher directement l'image dans un tag img
    $SrcEnc64 = 'data:'.$InMimeType.';base64,'.base64_encode($image);

    try {
        $statement->execute(array(':n' => $nomFichierMedia, ':e' => $SrcEnc64));
    }
    catch (PDOException $e) {
        echo 'Problème écriture dans la base de données: '.$e->getMessage();
        // fail
        return false;
    }
    // Done
    return true;
}
   
               
 /**
 * Récupère toutes les images de l'utilisateur de la base de données
 * @return array Un tableau d'objet EMedia. False si une erreur est survenue
 * 
 * @remark Les images sont stockées directement dans un enregistrement de la base de données sous forme encodée 64bits
 */
function LoadUserEnc64Images()
{
	// On crée un tableau qui va contenir les objets EMedia
	$arr = array();

    $s = "SELECT * FROM `portfolio_img`.`media`";
	$statement = EDatabase::prepare($s,array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	try {
		$statement->execute();
	}
	catch (PDOException $e) {
        echo 'Problème de lecture de la base de données: '.$e->getMessage();
		return false;
	}
	// On parcoure les enregistrements 
	while ($row=$statement->fetch(PDO::FETCH_ASSOC,PDO::FETCH_ORI_NEXT)){
		// On crée l'objet EMedia en l'initialisant avec les données provenant
		// de la base de données
        $img = new EMedia(
            intval($row['idMedia']),
            $row['nomFichierMedia'],
            $row['encodeImage']
        );
		// On place l'objet EMedia créé dans le tableau
		array_push($arr, $img);
	}        
	// On retourne le tableau contenant la définition des images sous forme EMedia
	return $arr;
}     


     
<?php

/**
 * Classe container post
 */
class EPost
{
    private $medias = array();
    /**
     * Constructeur permettant de créer un nouvel objet de type post
     * @param integer $InIdPost L'identifiant du post
     * @param string $InDescription La description du post
     * @param integer $InDateCreation La date de création du post
     */
    public function __construct($InIdPost, $InDescription, $InDateCreation)
    {

        $this->idPost = $InIdPost;
        $this->description = $InDescription;
        $this->dateCreation = $InDateCreation;
    }

   /* public function addMedia($media)
    {
        array_push($this->medias, $media);
    }*/

    /**
     * @var integer identifiant du post
     */
    public $idPost;
    /**
     * @var string description du post
     */
    public $description;
    /**
     * @var string date de création du post
     */
    public $dateCreation;
 
    
}
?>
<?php

/**
 * Classe container post
 */
class EPost
{

    /**
     * Constructeur permettant de créer un nouveau poste
     * @param integer $InIdBudget L'identifiant du budget
     * @param string $InNom le nom du budget
     * @param integer $InMontant le montant du budget
     */
    public function __construct($InIdPost, $InDescription, $InDateCreation)
    {

        $this->idPost = $InIdPost;
        $this->description = $InDescription;
        $this->dateCreation = $InDateCreation;
    }



    /**
     * @var integer identifiant du poste
     */
    public $idPost;
    /**
     * @var string description du poste
     */
    public $description;
    /**
     * @var string date de création du poste
     */
    public $dateCreation;
 
    
}
?>
<?php

/**
 * Classe container post
 */
class EMedia
{

    /**
     * Constructeur permettant de créer un nouvel objet de type média
     * @param integer $InIdBudget L'identifiant du budget
     * @param string $InNom le nom du budget
     * @param integer $InMontant le montant du budget
     */
    public function __construct($InIdMedia, $InNomFichierMedia = "", $InTypeMedia = "", $InImage = "")
    {

        $this->idMedia = $InIdMedia;
        $this->nomFichierMedia = $InNomFichierMedia;
        $this->typeMedia = $InTypeMedia;
        $this->image = $InImage
    }


  
    /**
     * @var int identifiant du média
     */
    public $idMedia;
    /**
     * @var string nom du fichier contenant le média
     */
    public $nomFichierMedia;
    /**
     * @var string type du fichier contenant le média
     */
    public $typeMedia;
    /**
     * @var string l'image encodé en base 64
     */
    public $image
}

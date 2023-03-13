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
    public function __construct($InIdMedia, $InNomFichierMedia, $InEncode)
    {

        $this->idMedia = $InIdMedia;
        $this->nomFichierMedia = $InNomFichierMedia;
        $this->encode = $InEncode;
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
     * @var string l'image encodé en base 64
     */
    public $encode;
}
?>

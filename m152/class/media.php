<?php

/**
 * Classe container post
 */
class EMedia
{

    /**
     * Constructeur permettant de créer un nouvel objet de type média
     * @param integer $InIdMedia L'identifiant du média
     * @param string $InEncode L'image encodé en base 64
     * @param string $InTypeMedia le type du fichier
     */
    public function __construct($InIdMedia, $InEncode, $InTypeMedia)
    {

        $this->idMedia = $InIdMedia;
        $this->encode = $InEncode;
        $this->typeMedia = $InTypeMedia;
    }


  
    /**
     * @var int identifiant du média
     */
    public $idMedia;
    /**
     * @var string l'image encodé en base 64
     */
    public $encode;
    /**
     * @var string le type du fichier
     */
    public $typeMedia;
}
?>

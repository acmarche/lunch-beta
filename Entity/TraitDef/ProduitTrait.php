<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/09/17
 * Time: 17:00
 */

namespace AcMarche\LunchBundle\Entity\TraitDef;

use AcMarche\LunchBundle\Entity\InterfaceDef\ProduitInterface;
use AcMarche\LunchBundle\Entity\Produit;
use Doctrine\ORM\Mapping as ORM;

trait ProduitTrait
{

    /**
     * @ORM\ManyToOne(targetEntity="AcMarche\LunchBundle\Entity\Produit", inversedBy="commande_produits")
     *
     * @var
     */
    protected $produit;

    /**
     * @return ProduitInterface
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * @param ProduitInterface $produit
     */
    public function setProduit(ProduitInterface $produit)
    {
        $this->produit = $produit;
    }

}

<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 16/09/17
 * Time: 18:47
 */

namespace AcMarche\LunchBundle\Entity\TraitDef;


use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeInterface;

trait LieuLivraisonTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="AcMarche\LunchBundle\Entity\LieuLivraison", inversedBy="commandes")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL" )
     * @var
     */
    protected $lieu_livraison;

    /**
     * Set lieuLivraison
     *
     * @param \AcMarche\LunchBundle\Entity\LieuLivraison $lieuLivraison
     *
     * @return CommandeInterface
     */
    public function setLieuLivraison(\AcMarche\LunchBundle\Entity\LieuLivraison $lieuLivraison = null)
    {
        $this->lieu_livraison = $lieuLivraison;

        return $this;
    }

    /**
     * Get lieuLivraison
     *
     * @return \AcMarche\LunchBundle\Entity\LieuLivraison
     */
    public function getLieuLivraison()
    {
        return $this->lieu_livraison;
    }
}
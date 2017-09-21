<?php

namespace AcMarche\LunchBundle\Entity;

use AcMarche\LunchBundle\Entity\Base\BaseCommande;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeInterface;
use AcMarche\LunchBundle\Entity\TraitDef\CommandeProduitTrait;
use AcMarche\LunchBundle\Entity\TraitDef\CommerceTrait;
use AcMarche\LunchBundle\Entity\TraitDef\LieuLivraisonTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="AcMarche\LunchBundle\Repository\CommandeRepository")
 */
class Commande extends BaseCommande implements CommandeInterface
{
    use CommerceTrait;
    use CommandeProduitTrait;
    use LieuLivraisonTrait;

    /**
     * Overload pour le inversedBy
     *
     * @ORM\ManyToOne(targetEntity="AcMarche\LunchBundle\Entity\Commerce", inversedBy="commandes")
     * @ORM\JoinColumn(nullable=false)
     * @var
     */
    protected $commerce;

    /**
     * @ORM\OneToOne(targetEntity="AcMarche\LunchBundle\Entity\StripeCharge", mappedBy="commande")
     *
     */
    protected $stripe_charge;

    /**
     * Get isFood
     * Raccourcis pour les templates
     * @return boolean
     */
    public function getisFood()
    {
        return false;
    }


    /**
     * STOP
     */


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commande_produits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set stripeCharge
     *
     * @param \AcMarche\LunchBundle\Entity\StripeCharge $stripeCharge
     *
     * @return Commande
     */
    public function setStripeCharge(\AcMarche\LunchBundle\Entity\StripeCharge $stripeCharge = null)
    {
        $this->stripe_charge = $stripeCharge;

        return $this;
    }

    /**
     * Get stripeCharge
     *
     * @return \AcMarche\LunchBundle\Entity\StripeCharge
     */
    public function getStripeCharge()
    {
        return $this->stripe_charge;
    }
}

<?php

namespace AcMarche\LunchBundle\Entity;

use AcMarche\LunchBundle\Entity\Base\BaseCommande;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeInterface;
use AcMarche\LunchBundle\Entity\TraitDef\CommandeProduitTrait;
use AcMarche\LunchBundle\Entity\TraitDef\CommerceTrait;
use AcMarche\LunchBundle\Entity\TraitDef\LieuLivraisonTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeLunch
 *
 * @ORM\Table(name="commande_lunch")
 * @ORM\Entity(repositoryClass="AcMarche\LunchBundle\Repository\CommandeRepository")
 */
class CommandeLunch extends BaseCommande implements CommandeInterface
{
    use CommerceTrait;
    use CommandeProduitTrait;
    use LieuLivraisonTrait;

    /**
     * Override pour le inversedBy
     * Voir CommerceTrait
     * @ORM\ManyToOne(targetEntity="AcMarche\LunchBundle\Entity\Commerce", inversedBy="commandes")
     * @ORM\JoinColumn(nullable=false)
     * @var
     */
    protected $commerce;

    /**
     * Override pour targetEntity
     * See CommandeProduitTrait
     * @ORM\OneToMany(targetEntity="CommandeProduitLunch", mappedBy="commande", cascade={"persist", "remove"})
     *
     */
    protected $commande_produits;

    /**
     * @ORM\OneToOne(targetEntity="AcMarche\LunchBundle\Entity\StripeCharge", mappedBy="commande_lunch")
     *
     */
    protected $stripe_charge;

    /**
     * Override car une commande lunch est directement validee
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=1} )
     */
    protected $valide = 1;

    /**
     * Get isFood
     *
     * Raccourcis pour les templates
     *
     * @return boolean
     */
    public function getisFood()
    {
        return true;
    }

    public function __toString()
    {
        return "Commande lunch ".$this->getId();
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
     * @return CommandeLunch
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

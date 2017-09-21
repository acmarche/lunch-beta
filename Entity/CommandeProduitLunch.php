<?php

namespace AcMarche\LunchBundle\Entity;

use AcMarche\LunchBundle\Entity\Base\BaseCommandeProduit;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeProduitInterface;
use AcMarche\LunchBundle\Entity\TraitDef\CommandeTrait;
use AcMarche\LunchBundle\Entity\TraitDef\ProduitTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeProduitLunch
 *
 * @ORM\Table(name="commande_produit_lunch")
 * @ORM\Entity(repositoryClass="AcMarche\LunchBundle\Repository\CommandeProduitRepository")
 */
class CommandeProduitLunch extends BaseCommandeProduit implements CommandeProduitInterface
{
    use ProduitTrait;
    use CommandeTrait;

    /**
     * Override pour targetentity
     * See CommandeTrait
     * @ORM\ManyToOne(targetEntity="CommandeLunch", inversedBy="commande_produits")
     * @ORM\JoinColumn(nullable=false)
     * @var
     */
    protected $commande;


    /**
     * STOP
     */

}

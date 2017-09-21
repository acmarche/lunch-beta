<?php

namespace AcMarche\LunchBundle\Entity;

use AcMarche\LunchBundle\Entity\Base\BaseCommandeProduit;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeProduitInterface;
use AcMarche\LunchBundle\Entity\TraitDef\CommandeTrait;
use AcMarche\LunchBundle\Entity\TraitDef\ProduitTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeProduit
 *
 * @ORM\Table(name="commande_produit")
 * @ORM\Entity(repositoryClass="AcMarche\LunchBundle\Repository\CommandeProduitRepository")
 */
class CommandeProduit extends BaseCommandeProduit implements CommandeProduitInterface
{
    use ProduitTrait;
    use CommandeTrait;




    /**
     * STOP
     */


}

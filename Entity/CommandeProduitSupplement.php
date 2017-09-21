<?php

namespace AcMarche\LunchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandeProduitSupplement
 *
 * ORM\Table(name="commande_produit_supplement")
 * ORM\Entity(repositoryClass="AcMarche\LunchBundle\Repository\CommandeProduitSupplementRepository")
 */
class CommandeProduitSupplement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="CommandeProduitLunch", inversedBy="commande_produit_supplement")
     * @ORM\JoinColumn(nullable=false)
     * @var
     */
    protected $commande_produit2;

    /**
     * @ORM\ManyToOne(targetEntity="AcMarche\LunchBundle\Entity\Supplement", inversedBy="commande_produit_supplement")
     * @ORM\JoinColumn(nullable=false)
     * @var
     */
    protected $supplement;


    /**
     * STOP
     */

}

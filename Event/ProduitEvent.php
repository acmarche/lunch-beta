<?php

namespace AcMarche\LunchBundle\Event;

use AcMarche\LunchBundle\Entity\Produit;
use Symfony\Component\EventDispatcher\Event;

/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/07/17
 * Time: 12:05
 */
class ProduitEvent extends Event
{
    const PRODUIT_NEW = 'ac_marche_lunch.produit.new';

    protected $produit;

    public function __construct(Produit $produit)
    {
        $this->produit = $produit;
    }

    public function getProduit()
    {
        return $this->produit;
    }
}
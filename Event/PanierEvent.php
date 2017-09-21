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
class PanierEvent extends Event
{
    const PANIER_INDEX = 'ac_marche_lunch.panier.index';

    public function __construct()
    {

    }
}
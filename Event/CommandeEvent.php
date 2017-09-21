<?php

namespace AcMarche\LunchBundle\Event;

use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 13/09/17
 * Time: 16:053
 */
class CommandeEvent extends Event
{
    const COMMANDE_NEW = 'ac_marche_lunch.commande.new';
    const COMMANDE_PAYE = 'ac_marche_lunch.commande.paye';
    const COMMANDE_VALIDE = 'ac_marche_lunch.commande.valide';
    const COMMANDE_LIVRE = 'ac_marche_lunch.commande.livre';

    protected $commande;

    public function __construct(CommandeInterface $commande)
    {
        $this->commande = $commande;
    }

    public function getCommande()
    {
        return $this->commande;
    }
}
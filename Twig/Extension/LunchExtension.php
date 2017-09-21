<?php

namespace AcMarche\LunchBundle\Twig\Extension;

use AcMarche\LunchBundle\Entity\Produit;
use AcMarche\LunchBundle\Service\ProduitUtil;

class LunchExtension extends \Twig_Extension
{
    protected $produitUtil;

    public function __construct(ProduitUtil $produitUtil)
    {
        $this->produitUtil = $produitUtil;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('prixTvac', array($this, 'tvacFilter')),
            new \Twig_SimpleFilter('etapeCheck', array($this, 'etapeFilter')),
        );
    }

    public function tvacFilter(Produit $produit)
    {
       return $this->produitUtil->getPrixTvac($produit);
    }

    public function etapeFilter(bool $boolean)
    {
        if($boolean == true)
            return 'success';

       return 'warning';
    }

}

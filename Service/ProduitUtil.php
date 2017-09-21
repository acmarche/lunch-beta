<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/07/17
 * Time: 17:00
 */

namespace AcMarche\LunchBundle\Service;

use AcMarche\LunchBundle\Entity\InterfaceDef\ProduitInterface;
use Doctrine\Common\Persistence\ObjectManager;

class ProduitUtil
{
    private $manager;
    private $paramsUtil;

    function __construct(ObjectManager $manager, ParamsUtil $paramsUtil)
    {
        $this->manager = $manager;
        $this->paramsUtil = $paramsUtil;
    }

    /**
     * @param ProduitInterface $produit
     * @return bool
     */
    function checkDisponible(ProduitInterface $produit)
    {
        return !$produit->getIndisponible();
    }

    /**
     * Disponible ? En stock ?
     * @param ProduitInterface $produit
     * @return bool
     */
    function canBuy(ProduitInterface $produit)
    {
        if ($this->checkDisponible($produit))
            if ($this->checkStock($produit))
                return true;

        return false;
    }

    function canDisplayOnsite(ProduitInterface $produit)
    {
        if ($this->checkDisponible($produit))
                return true;

        return false;
    }

    /**
     * Retourne le pourcentage de la tva
     * @param ProduitInterface $produit
     * @return mixed
     */
    public function getTvaApplicable(ProduitInterface $produit)
    {
        if ($produit->getTvaApplicable())
            return $produit->getTvaApplicable();

        $commerce = $produit->getCommerce();
        if ($commerce->getTvaApplicable())
            return $commerce->getTvaApplicable();

        return $this->paramsUtil->getDefaultTva();
    }

    /**
     * Retourne le montant de la tva
     * @param ProduitInterface $produit
     * @return mixed
     */
    public function getMontantTva(ProduitInterface $produit)
    {
        $pourcentageTva = $this->getTvaApplicable($produit);

        return $this->calculTva($produit->getPrixHtva(), $pourcentageTva);
    }

    /**
     * Calcul le montant de la tva d'un produit
     * @param $prix
     * @param $tva
     * @return float
     */
    public function calculTva($prix, $tva)
    {
        return $this->getRound($prix * ($tva / 100));
    }

    /**
     * @param ProduitInterface $produit
     * @return mixed
     */
    public function getPrixTvac(ProduitInterface $produit)
    {
        return $this->getMontantTva($produit) + $produit->getPrixHtva();
    }

    /**
     *
     * @param ProduitInterface $produit
     * @param $quantite
     * @return mixed
     */
    public function getPrixTvacByQuantite(ProduitInterface $produit, $quantite)
    {
        $prixTvac = $this->getPrixTvac($produit);
        return $quantite * $prixTvac;
    }

    /**
     * @param ProduitInterface $produit
     * @param $quantite
     * @return mixed
     */
    public function getPrixHtvaByQuantite(ProduitInterface $produit, $quantite)
    {
        return $quantite * $produit->getPrixHtva();
    }

    /**
     * @param $prix
     * @return string
     */
    public function getRound($prix)
    {
        //return round($prix + 0.01) - 0.01;
        return number_format($prix, 2);
    }

    /**
     * Pour donner a stripe
     * @param $prix
     * @return mixed
     */
    public function getInCent($prix)
    {
        return preg_replace("#[^0-9]#", "", $prix);
    }

    /**
     * @param $quantite
     * @return bool
     */
    public function checkQuantite($quantite)
    {
        if ($quantite < 1) {
            return false;
        }

        return true;
    }

    /**
     * @param ProduitInterface $produit
     * @return bool
     */
    public function checkStock(ProduitInterface $produit)
    {
        if ($produit->getQuantiteStock() == 0) {
            return false;
        }

        return true;
    }
}

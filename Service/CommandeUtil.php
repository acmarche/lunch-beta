<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/07/17
 * Time: 17:00
 */

namespace AcMarche\LunchBundle\Service;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Entity\CommandeLunch;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeInterface;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommerceInterface;
use AcMarche\LunchBundle\Entity\InterfaceDef\ProduitInterface;
use AcMarche\SecurityBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;

class CommandeUtil
{
    private $manager;
    private $produitUtil;
    private $supplementUtil;
    private $stripeUtil;

    function __construct(
        ObjectManager $manager,
        ProduitUtil $produitUtil,
        SupplementsUtil $supplementsUtil,
        StripeUtil $stripeUtil
    ) {
        $this->manager = $manager;
        $this->produitUtil = $produitUtil;
        $this->stripeUtil = $stripeUtil;
        $this->supplementUtil = $supplementsUtil;
    }

    /**
     * @param Commande $commande
     * @return ['totalHtva','totalSupplementsHtva','montantTva','total']
     */
    public function getCoutsCommande(CommandeInterface $commande)
    {
        $couts = [];
        $couts['montantTva'] = $this->getMontantTvaCommande($commande);
        $couts['totalHtva'] = $this->getTotalHtvaCommande($commande);
        $couts['totalTvac'] = $this->getTotalTvacCommande($commande);
        $couts['fraisTransaction'] = $this->stripeUtil->calculFraisTransaction($couts['totalTvac']);
        $couts['total'] = $couts['totalTvac'] + $couts['fraisTransaction'];
        // $couts['totalSupplementsHtva'] = $this->supplementUtil->getCoutSupplementsByCommande($commande);
        $couts['totalSupplementsHtva'] = 0;//@todo

        return $couts;
    }

    /**
     * Tva sur chaque produits
     *
     * @param CommandeInterface $commande
     * @return int|mixed
     */
    public function getMontantTvaCommande(CommandeInterface $commande)
    {
        $total = 0;
        foreach ($commande->getCommandeProduits() as $commandeProduit) {
            $produit = $commandeProduit->getProduit();
            $montantTva = $this->produitUtil->getMontantTva($produit);
            $total += $montantTva * $commandeProduit->getQuantite();
        }

        return $total;
    }

    /**
     * Total de la commande sans la tva
     * @param CommandeInterface $commande
     * @return int|mixed
     */
    public function getTotalHtvaCommande(CommandeInterface $commande)
    {
        $total = 0;
        foreach ($commande->getCommandeProduits() as $commandeProduit) {
            $produit = $commandeProduit->getProduit();
            $total += $this->produitUtil->getPrixHtvaByQuantite($produit, $commandeProduit->getQuantite());
        }

        return $total;
    }

    /**
     * Total de la commande avec la tva
     * @param CommandeInterface $commande
     * @return int|mixed
     */
    public function getTotalTvacCommande(CommandeInterface $commande)
    {
        $commandeProduits = $commande->getCommandeProduits();
        $total = 0;
        foreach ($commandeProduits as $commandeProduit) {
            $produit = $commandeProduit->getProduit();
            $total += $this->produitUtil->getPrixTvacByQuantite($produit, $commandeProduit->getQuantite());
            // $total += $this->supplementUtil->getCoutSupplements($commandeProduit);
        }

        return $total;
    }

    /**
     * Crée une commande
     *
     * @param CommerceInterface $commerce
     * @param ProduitInterface $produit
     * @param User $user
     * @return CommandeInterface|CommandeLunch
     */
    public function createCommande(CommerceInterface $commerce, ProduitInterface $produit, User $user)
    {
        if ($produit->getIsFood()) {
            $commande = new CommandeLunch();
        } else {
            $commande = new Commande();
        }

        $commande->setCommerce($commerce);
        $commande->setUser($user->getUsername());
        $this->manager->persist($commande);

        return $commande;
    }
}

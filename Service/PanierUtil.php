<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/09/17
 * Time: 14:53
 */

namespace AcMarche\LunchBundle\Service;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Entity\CommandeLunch;
use AcMarche\LunchBundle\Entity\CommandeProduit;
use AcMarche\LunchBundle\Entity\CommandeProduitLunch;
use AcMarche\LunchBundle\Entity\Commerce;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeInterface;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeProduitInterface;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommerceInterface;
use AcMarche\LunchBundle\Entity\InterfaceDef\ProduitInterface;
use AcMarche\SecurityBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Config\Definition\Exception\Exception;

class PanierUtil
{
    private $manager;
    private $produitUtil;
    private $commandeUtil;

    /**
     * StripeUtil constructor.
     */
    public function __construct(ObjectManager $manager, ProduitUtil $produitUtil, CommandeUtil $commandeUtil)
    {
        $this->manager = $manager;
        $this->produitUtil = $produitUtil;
        $this->commandeUtil = $commandeUtil;
    }

    /**
     * @param ProduitInterface $produit
     * @param CommerceInterface $commerce
     * @param User $user
     * @param $quantite
     * @return CommandeProduit|CommandeProduitInterface|null
     */
    public function addProduit(ProduitInterface $produit, CommerceInterface $commerce, User $user, $quantite)
    {
        if (!$this->produitUtil->checkStock($produit)) {
            throw new Exception('Plus de stock disponible');
        }

        if (!$this->checkProduitIsOwnedCommerce($produit, $commerce)) {
            throw new Exception("Le produit n'appartient pas à ce commerce");
        }

        if (!$commande = $this->commandeExistPanier($commerce, $produit, $user)) {
            $commande = $this->commandeUtil->createCommande($commerce, $produit, $user);
        }

        if (!$commandeProduit = $this->produitExistPanier($produit, $commerce, $user)) {
            $commandeProduit = $this->createCommandeProduit($produit, $commande, $quantite);
        }

        $this->updateProduit($commandeProduit, $quantite);

        return $commandeProduit;
    }

    /**
     * @param CommandeProduitInterface $commandeProduit
     * @param $quantite
     * @return CommandeProduitInterface
     */
    public function updateProduit(CommandeProduitInterface $commandeProduit, $quantite)
    {
        if (!$this->produitUtil->checkQuantite($quantite)) {
            throw new Exception('Quantité minimum = 1');
        }

        $commandeProduit->setQuantite($quantite);
        $this->manager->flush();

        return $commandeProduit;
    }

    /**
     * @param CommandeProduitInterface $commandeProduit
     */
    public function removeCommandeProduit(CommandeProduitInterface $commandeProduit)
    {
        $this->manager->remove($commandeProduit);
        $this->manager->flush();
    }

    /**
     * Utilise lors d'un update
     *
     * @param CommandeProduitInterface $commandeProduit
     * @param User $user
     */
    public function checkCommandeProduit(CommandeProduitInterface $commandeProduit, User $user)
    {
        $commande = $commandeProduit->getCommande();

        if (!$commande) {
            throw new Exception('Commande introuvable');
        }

        if ($commande->getPaye()) {
            throw new Exception('Commande déjà payée');
        }

        $produit = $commandeProduit->getProduit();

        if (!$produit) {
            throw new Exception('Produit introuvable');
        }

        if (!$this->produitUtil->checkStock($produit)) {
            throw new Exception('Plus de stock disponible');
        }

        $commerce = $commande->getCommerce();
        if (!$commerce) {
            throw new Exception('Commerce introuvable');
        }

        if (!$this->canAccessCommandeProduit($commandeProduit, $user)) {
            throw new Exception("Pas d'accès à cette commande");
        }

        if (!$this->commandeProduitExistInPanier($commandeProduit)) {
            throw new Exception("Commande Produit inexsistante");
        }

    }

    /**
     * @param CommandeInterface[] $commandes
     */
    public function clean($commandes)
    {
        foreach ($commandes as $commande) {
            foreach ($commande->getCommandeProduits() as $commandeProduit) {
                $this->manager->remove($commandeProduit);
            }
            $this->manager->remove($commande);
        }
        $this->manager->flush();
    }

    /**
     * Pour le panier : add or update
     *
     * @param ProduitInterface $produit
     * @param CommerceInterface $commerce
     * @param User $user
     * @return CommandeProduitInterface|null
     */
    public function produitExistPanier(ProduitInterface $produit, CommerceInterface $commerce, User $user)
    {
        if ($produit->getIsFood()) {
            return $this->manager->getRepository(CommandeProduitLunch::class)->existInPanier(
                $user,
                $commerce,
                $produit
            );
        } else {
            return $this->manager->getRepository(CommandeProduit::class)->existInPanier($user, $commerce, $produit);
        }
    }

    /**
     * Commande non paye suivant le user et le commerce
     *
     * @param CommerceInterface $commerce
     * @param ProduitInterface $produit
     * @param User $user
     * @return CommandeInterface[]|null
     */
    public function commandeExistPanier(CommerceInterface $commerce, ProduitInterface $produit, User $user)
    {
        if ($produit->getIsFood()) {
            return $this->manager->getRepository(CommandeLunch::class)->commandeExistPanier($user, $commerce);
        } else {
            return $this->manager->getRepository(Commande::class)->commandeExistPanier($user, $commerce);
        }
    }

    /**
     * Verifie que le produit apparatient au commerce
     * @param ProduitInterface $produit
     * @param CommerceInterface $commerce
     * @return bool
     */
    public function checkProduitIsOwnedCommerce(ProduitInterface $produit, CommerceInterface $commerce)
    {
        if (!$commerceFrom = $produit->getCommerce()) {
            return false;
        }

        if ($commerceFrom->getId() != $commerce->getId()) {
            return false;
        }

        return true;
    }

    /**
     * Retourne un CommerceEntity ou null
     * @param $commerceId
     * @return CommerceInterface|null
     */
    public function checkCommerce($commerceId)
    {
        return $this->manager->getRepository(Commerce::class)->findOneBy(
            [
                'id' => $commerceId,
                'indisponible' => false,
            ]
        );
    }

    /**
     * @param ProduitInterface $produit
     * @param CommandeInterface $commande
     * @param integer $quantite
     * @return CommandeProduit
     */
    public function createCommandeProduit(ProduitInterface $produit, CommandeInterface $commande, $quantite)
    {
        if ($produit->getIsFood()) {
            $commandeProduit = new CommandeProduitLunch();
        } else {
            $commandeProduit = new CommandeProduit();
        }
        $commandeProduit->setCommande($commande);
        $commandeProduit->setProduit($produit);
        $commandeProduit->setQuantite($quantite);
        $this->manager->persist($commandeProduit);

        return $commandeProduit;
    }

    /**
     * @param CommandeProduitInterface $commandeProduit
     * @param User $user
     * @return bool
     */
    public function canAccessCommandeProduit(CommandeProduitInterface $commandeProduit, User $user)
    {
        $commande = $commandeProduit->getCommande();
        if (!$commande) {
            return false;
        }

        if ($commande->getUser() != $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     *
     * @param CommandeProduitInterface $commandeProduit
     * @return CommandeProduit|null
     */
    public function commandeProduitExistInPanier(CommandeProduitInterface $commandeProduit)
    {
        if (!$produit = $commandeProduit->getProduit()) {
            return null;
        }

        if ($produit->getIsFood()) {
            return $this->manager->getRepository(CommandeProduitLunch::class)->commandProduitExistInPanier(
                $commandeProduit
            );
        } else {
            return $this->manager->getRepository(CommandeProduit::class)->commandProduitExistInPanier(
                $commandeProduit
            );
        }
    }

    /**
     *
     * LE METTRE EN EVENT
     *
     * Lorsqu'on va sur la page index du panier
     * check produit rupture stok
     * check produit non disponible
     * clean commande sans produit
     *
     * @param CommandeInterface[] $commandes
     * @param User $user
     * @return array
     */
    public function checkPanier($commandes, User $user)
    {
        $data = [];

        foreach ($commandes as $commande) {
            foreach ($commande->getCommandeProduits() as $commandeProduit) {
                if (!$this->produitUtil->checkStock($commandeProduit->getProduit())) {
                    $this->manager->remove($commandeProduit);
                    $data["rupture"] = $commandeProduit;
                }
                if (!$this->produitUtil->checkDisponible($commandeProduit->getProduit())) {
                    $this->manager->remove($commandeProduit);
                    $data["indisponible"] = $commandeProduit;
                }
            }
        }
        $this->manager->flush();
        $this->cleanCommandeWithoutProduit($commandes);
        $this->manager->flush();
        //refresh
        $data["commandes"] = $commandes = $this->manager->getRepository(Commande::class)->getPanier($user);

        return $data;
    }

    /**
     * Lorsqu'on va sur le panier, toute commande sans produit est supprimee
     * @param $commandes CommandeInterface[]
     * @return CommandeInterface[]
     */
    public function cleanCommandeWithoutProduit($commandes)
    {
        foreach ($commandes as $key => $commande) {
            if (count($commande->getCommandeProduits()) < 1) {
                $this->manager->remove($commande);
                unset($commandes[$key]);
            }
        }
        $this->manager->flush();

        return $commandes;
    }

}
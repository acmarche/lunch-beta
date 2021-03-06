<?php

namespace AcMarche\LunchBundle\Repository;

use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeProduitInterface;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommerceInterface;
use AcMarche\LunchBundle\Entity\InterfaceDef\ProduitInterface;
use AcMarche\SecurityBundle\Entity\User;

/**
 * CommandeProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommandeProduitRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Check si la commande produit existe dans le panier
     *
     * User = null pour non connecte
     * @param User $user
     * @param CommerceInterface $commerce
     * @param ProduitInterface $produit
     * @return CommandeProduitInterface|null
     */
    public function existInPanier(User $user = null, CommerceInterface $commerce, ProduitInterface $produit)
    {
        if (!$user) {
            return null;
        }

        $qb = $this->createQueryBuilder('commandeProduit');
        $qb->leftJoin('commandeProduit.commande', 'commande', 'WITH');
        $qb->leftJoin('commandeProduit.produit', 'produit', 'WITH');
        $qb->leftJoin('commande.commerce', 'commerce', 'WITH');
        $qb->addSelect('commande', 'commerce', 'produit');

        $qb->andWhere('commande.user = :username')
            ->setParameter('username', $user->getUsername());

        $qb->andWhere('produit = :produit')
            ->setParameter('produit', $produit);

        $qb->andWhere('commerce = :commerce')
            ->setParameter('commerce', $commerce);

        $qb->andWhere('commande.paye = :paye')
            ->setParameter('paye', 0);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * Utiliser pour l'update et le delete d'un produit du panier
     * User = null pour non connecte
     * @param User $user
     * @param CommandeProduitInterface $commandeProduit
     * @return CommandeProduitInterface|null
     */
    public function commandProduitExistInPanier(CommandeProduitInterface $commandeProduit)
    {
        $qb = $this->createQueryBuilder('commandeProduit');

        $qb->andWhere('commandeProduit = :cmdp')
            ->setParameter('cmdp', $commandeProduit);

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }

}

<?php

namespace AcMarche\LunchBundle\Repository;

use AcMarche\LunchBundle\Entity\Commerce;
use AcMarche\LunchBundle\Entity\Produit;
use AcMarche\SecurityBundle\Entity\User;

/**
 * ProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $args
     * @return Produit[]
     */
    public function search($args)
    {
        $qb = $this->createQueryBuilder('produit');
        $qb->leftJoin('produit.commerce', 'commerce', 'WITH');
        $qb->leftJoin('produit.categorie', 'categorie', 'WITH');
        $qb->leftJoin('produit.ingredients', 'ingredients', 'WITH');
        $qb->leftJoin('produit.supplements', 'supplements', 'WITH');
        $qb->addSelect('commerce', 'categorie', 'ingredients', 'supplements');

        $motclef = isset($args['motclef']) ? $args['motclef'] : null;
        $commerce = isset($args['commerce']) ? $args['commerce'] : null;
        $indisponible = isset($args['indisponible']) ? $args['indisponible'] : 3;
        $quantite_stock = isset($args['quantite_stock']) ? $args['quantite_stock'] : 1;

        if ($motclef) {
            $qb->andWhere('produit.nom LIKE :clef')
                ->setParameter('clef', '%'.$motclef.'%');
        }

        if ($commerce) {
            $qb->andWhere('produit.commerce = :commerce')
                ->setParameter('commerce', $commerce);
        }

        switch ($indisponible) {
            case 1:
                $qb->andWhere('produit.indisponible = :indisponible')
                    ->setParameter('indisponible', 1);
                break;
            case 2:
                //je prends les deux
                break;
            default:
                $qb->andWhere('produit.indisponible = :indisponible')
                    ->setParameter('indisponible', 0);
        }

        $qb->addOrderBy('produit.nom');

        $query = $qb->getQuery();

        $results = $query->getResult();

        return $results;
    }

    /**
     * @param User $user
     * @return Produit[]
     */
    public function getOwnedByUser(User $user)
    {
        $em = $this->getEntityManager();
        $commerces = $em->getRepository(Commerce::class)->getCommercesOwnedByUser(
            $user
        );

        if (!$commerces) {
            return [];
        }
        $args = [];
        $args['indisponible'] = 2;//tout
        $args['commerce'] = $commerces;

        return $this->search($args);
    }


}

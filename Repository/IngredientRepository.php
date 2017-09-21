<?php

namespace AcMarche\LunchBundle\Repository;

use AcMarche\LunchBundle\Entity\Commerce;
use AcMarche\LunchBundle\Entity\Ingredient;
use AcMarche\SecurityBundle\Entity\User;

/**
 * IngredientRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class IngredientRepository extends \Doctrine\ORM\EntityRepository
{
    public function search($args)
    {
        $qb = $this->createQueryBuilder('ingredient');
        $qb->leftJoin('ingredient.produits', 'produits', 'WITH');
        $qb->leftJoin('ingredient.commerce', 'commerce', 'WITH');
        $qb->addSelect('commerce', 'produits');

        $motclef = isset($args['motclef']) ? $args['motclef'] : null;
        $commerce = isset($args['commerce']) ? $args['commerce'] : null;

        if ($motclef) {
            $qb->andWhere('ingredient.nom LIKE :clef')
                ->setParameter('clef', '%' . $motclef . '%');
        }

        if ($commerce) {
            $qb->andWhere('commerce = :commerce')
                ->setParameter('commerce', $commerce);
        }

        $qb->addOrderBy('ingredient.nom');

        $query = $qb->getQuery();

        $results = $query->getResult();

        return $results;
    }

    public function getCommerceForForm(Commerce $commerce)
    {
        $qb = $this->createQueryBuilder('ingredient');

        $qb->andWhere('ingredient.commerce = :id')
            ->setParameter('id', $commerce);

        $qb->addOrderBy('ingredient.nom', 'ASC');

        return $qb;
    }

    /**
     * @param User $user
     * @return Ingredient[]
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

        return $this->search(['commerce' => $commerces]);
    }
}

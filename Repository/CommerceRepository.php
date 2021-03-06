<?php

namespace AcMarche\LunchBundle\Repository;

use AcMarche\LunchBundle\Entity\Commerce;
use AcMarche\LunchBundle\Tests\Controller\Admin\CommerceControllerTest;
use AcMarche\SecurityBundle\Entity\User;

/**
 * CommerceRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommerceRepository extends \Doctrine\ORM\EntityRepository
{
    public function search($args)
    {
        $qb = $this->createQueryBuilder('commerce');
        $qb->leftJoin('commerce.produits', 'produits', 'WITH');
        $qb->leftJoin('commerce.commandes', 'commandes', 'WITH');
        $qb->leftJoin('commerce.ingredients', 'ingredients', 'WITH');

        $qb->addSelect('produits', 'commandes', 'ingredients');

        $motclef = isset($args['motclef']) ? $args['motclef'] : null;
        $user = isset($args['user']) ? $args['user'] : null;
        $nom = isset($args['nom']) ? $args['nom'] : null;

        if ($motclef) {
            $qb->andWhere('commerce.nom LIKE :clef')
                ->setParameter('clef', '%'.$motclef.'%');
        }

        if ($user) {
            $qb->andWhere('commerce.user = :user')
                ->setParameter('user', $user);
        }

        if ($nom) {
            $qb->andWhere('commerce.nom LIKE :nom')
                ->setParameter('nom', $nom);
        }

        $qb->addOrderBy('commerce.nom');

        $query = $qb->getQuery();

        $results = $query->getResult();

        return $results;
    }

    /**
     * @param Commerce $commerce
     * @return Commerce|Null
     */
    public function getNext(Commerce $commerce)
    {
        $qb = $this->createQueryBuilder('commerce');

        $qb->andWhere('commerce.nom > :nom')
            ->setParameter('nom', $commerce->getNom());

        $qb->setMaxResults(1);

        $query = $qb->getQuery();

        $results = $query->getOneOrNullResult();

        return $results;
    }

    /**
     * @param Commerce $commerce
     * @return Commerce|Null
     */
    public function getPrevious(Commerce $commerce)
    {
        $qb = $this->createQueryBuilder('commerce');

        $qb->andWhere('commerce.nom < :nom')
            ->setParameter('nom', $commerce->getNom());

        $qb->setMaxResults(1);

        $query = $qb->getQuery();

        $results = $query->getOneOrNullResult();

        return $results;
    }

    /**
     * Pour remplir select du form
     * @return array
     */
    public function getForSearch(User $user = null)
    {
        $qb = $this->createQueryBuilder('commerce');

        if ($user) {
            $qb->andWhere('commerce.user = :user')
                ->setParameter('user', $user->getUsername());
        }

        $qb->orderBy('commerce.nom');
        $query = $qb->getQuery();

        $results = $query->getResult();
        $commerces = array();

        foreach ($results as $commerce) {
            $commerces[$commerce->getNom()] = $commerce->getId();
        }

        return $commerces;
    }

    /**
     * @param User $user
     * @return array
     */
    public function getCommercesOwnedByUser(User $user)
    {
        $commerces = $this->findBy(
            [
                'user' => $user->getUsername(),
            ]
        );

        if (!$commerces) {
            return [];
        }

        return $commerces;
    }

}

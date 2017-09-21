<?php

namespace AcMarche\LunchBundle\DataFixtures\ORM;

use AcMarche\LunchBundle\Entity\LieuLivraison;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;


class LoadLivraison extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadLieuxLivraison($manager);
    }

    public function loadLieuxLivraison(ObjectManager $manager)
    {
        $lieulivaison = new LieuLivraison();
        $lieulivaison->setNom("Wex");
        $lieulivaison->setRue("Route du wex");
        $lieulivaison->setNumero(5);
        $lieulivaison->setCodePostal(6900);
        $lieulivaison->setLocalite("Marche");

        $manager->persist($lieulivaison);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }

}

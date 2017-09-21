<?php

namespace AcMarche\LunchBundle\DataFixtures\ORM;

use AcMarche\LunchBundle\Entity\Categorie;
use AcMarche\LunchBundle\Entity\Ingredient;
use AcMarche\LunchBundle\Entity\LieuLivraison;
use AcMarche\LunchBundle\Entity\Supplement;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;


class LoadCategorie extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $this->loadCats(FixtureConstant::CATEGORIES, $manager);
        $this->loadCats(FixtureLunchConstant::CATEGORIES, $manager);

        $this->loadLieuxLivraison($manager);
    }

    public function loadRoots(ObjectManager $manager)
    {
        $image = rand(1, 31);
        $categorie = new Categorie();
        $categorie->setNom("Lunch");
        $categorie->setDescription(
            'Toutes la partie nourritue.'
        );
        $categorie->setCreated(new \DateTime());
        $categorie->setUpdated(new \DateTime());
        $categorie->setImageName($image.'.jpg');
        $manager->persist($categorie);
        $this->addReference("lunch", $categorie);

        $image = rand(1, 31);
        $categorie = new Categorie();
        $categorie->setNom("Ecommerce");
        $categorie->setDescription(
            'Toutes la partie objets.'
        );
        $categorie->setCreated(new \DateTime());
        $categorie->setUpdated(new \DateTime());
        $categorie->setImageName($image.'.jpg');
        $manager->persist($categorie);
        $this->addReference("ecommerce", $categorie);

        $manager->flush();
    }

    public function loadCats($categories, ObjectManager $manager)
    {

        foreach ($categories as $category) {
            $image = rand(1, 31);
            $categorie = new Categorie();
            $categorie->setNom($category);
            $categorie->setDescription(
                'Sea nut perspicacity under omni piste natures mirror of
                    there with consequent.'
            );
            $categorie->setCreated(new \DateTime());
            $categorie->setUpdated(new \DateTime());
            $categorie->setImageName($image.'.jpg');
            $manager->persist($categorie);
            $this->addReference($category, $categorie);
        }

        $manager->flush();
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

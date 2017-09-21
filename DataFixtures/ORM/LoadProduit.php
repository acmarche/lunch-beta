<?php

namespace AcMarche\LunchBundle\DataFixtures\ORM;

use AcMarche\LunchBundle\Entity\Produit;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;


class LoadProduit extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;
    private $manager;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $produits = FixtureConstant::PRODUIS_JEUX;
        $prix = [8.25, 10.10, 5.70, 9.60, 15.0, 22.45, 32.5, 31.2, 3];
        $categorie = $this->getReference('Jeux');

        $this->loadDb($produits, $prix, $categorie);

        $prix = [40.25, 39.10, 73.70, 53.60, 23.0];
        $categorie = $this->getReference('Outils bricolages');
        $produits = FixtureConstant::PRODUIS_OUTILS;

        $this->loadDb($produits, $prix, $categorie);

        $prix = [4.25, 9.10, 2.70, 1.60, 2.0];
        $categorie = $this->getReference('Papeterie');
        $produits = FixtureConstant::PRODUIS_PAPETERIE;

        $this->loadDb($produits, $prix, $categorie);

        $manager->flush();
    }

    protected function loadDb($produits, $prix, $categorie)
    {
        foreach ($produits as $produitData) {

            $commerce = key($produitData);
            $nom = $produitData[$commerce];

            $clefPrix = rand(0, (count($prix) - 1));
            $image = rand(1, 31);

            $produit = new Produit();
            $produit->setCategorie($categorie);
            $produit->setNom($nom);
            $produit->setPrixHtva($prix[$clefPrix]);
            $produit->setImageName($image.'.jpg');
            $produit->setCommerce($this->getReference($commerce));
            $this->manager->persist($produit);

            if (!$this->hasReference("prod-".$nom)) {
                $this->addReference("prod-".$nom, $produit);
            }
        }
    }

    public function getOrder()
    {
        return 3;
    }

}

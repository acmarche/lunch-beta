<?php

namespace AcMarche\LunchBundle\DataFixtures\ORM;

use AcMarche\LunchBundle\Entity\Produit;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;


class LoadProduitLunch extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        $produits = FixtureLunchConstant::PRODUIS_LUNCH_CLASSIQUES;

        $prix = [2.25, 3.10, 2.70, 2.60, 3.0, 2.45, 3.5, 3.2, 3, 2.3, 2.35];
        $categorie = $this->getReference('Lunchs classiques');

        $this->loadDb($produits, $prix, $categorie);

        $prix = [3.25, 3.10, 3.70, 3.60, 3.0, 3.45, 3.5, 3.2];

        $categorie = $this->getReference('Sandichs spécialisés');

        $produits = FixtureLunchConstant::PRODUIS_LUNCH_SPECIALISE;

        $this->loadDb($produits, $prix, $categorie);

        $manager->flush();
    }

    protected function loadDb($produits, $prix, $categorie)
    {
        $ingredients = FixtureLunchConstant::INGREDIENTS;
        $supplements = FixtureLunchConstant::SUPLLEMENTS;

        foreach ($produits as $produitData) {

            $commerce = key($produitData);
            $nom = $produitData[$commerce];

            $clefPrix = rand(0, (count($prix) - 1));
            $countIngredients = rand(0, 4);
            $countSupplements = rand(0, 4);
            $image = rand(1, 31);

            $produit = new Produit();
            $produit->setCategorie($categorie);
            $produit->setIsFood(true);
            $produit->setNom($nom);
            $produit->setPrixHtva($prix[$clefPrix]);
            $produit->setImageName($image.'.jpg');
            $produit->setCommerce($this->getReference($commerce));
        /*    for ($i = 0; $i < $countIngredients; $i++) {
                $ingredient = rand(0, (count($ingredients) - 1));
                $produit->addIngredient($this->getReference("ingre-".$ingredients[$ingredient]));
            }
            for ($i = 0; $i < $countSupplements; $i++) {
                $supplement = rand(0, (count($supplements) - 1));
                $produit->addSupplement($this->getReference("supp-".$supplements[$supplement]));
            }*/
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

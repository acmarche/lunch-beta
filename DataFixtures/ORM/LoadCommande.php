<?php

namespace AcMarche\LunchBundle\DataFixtures\ORM;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Entity\CommandeLunch;
use AcMarche\LunchBundle\Entity\CommandeProduit;
use AcMarche\LunchBundle\Entity\CommandeProduitLunch;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;


class LoadCommande extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $this->loadCommande($manager);
        $this->loadCommandeNourritue($manager);

        $manager->flush();
    }

    private function loadCommandeNourritue(ObjectManager $manager)
    {
        $args = [
            'user' => 'homer',
            'commerce' => 'porte',
            'paye' => false,
            'livre' => false,
        ];

        $commande = $this->createCommande($args, true);
        $this->addProduit($commande, "prod-Poulet andalouse", true);

        $args  ['paye'] = true;

        $commande = $this->createCommande($args, true);
        $this->addProduit($commande, "prod-Tartare italien", true);

        $args  ['commerce'] = 'babe';
        $args  ['user'] = 'zora';
        $args  ['paye'] = true;

        $commande = $this->createCommande($args, true);
        $this->addProduit($commande, "prod-Ardennais", true);

        $args  ['paye'] = false;
        $commande = $this->createCommande($args, true);
        $this->addProduit($commande, "prod-Gouda", true);
    }

    public function loadCommande(ObjectManager $manager)
    {
        $this->manager = $manager;

        $args = [
            'user' => 'homer',
            'commerce' => 'lobet',
            'paye' => true,
            'livre' => false,
        ];

        $commande = $this->createCommande($args, false);
        $this->addProduit($commande, "prod-Marteau");

        $args  ['paye'] = false;
        $commande = $this->createCommande($args, false);
        $this->addProduit($commande, "prod-Scie");
    }

    protected function createCommande($args, $isFood = false)
    {
        if ($isFood) {
            $commande = new CommandeLunch();
        } else {
            $commande = new Commande();
        }
        $commande->setUser($args['user']);
        $commande->setCommerce($this->getReference($args['commerce']));
        $commande->setPaye($args['paye']);
        $commande->setLivre($args['livre']);
        $commande->setCreated(new \DateTime());
        $commande->setUpdated(new \DateTime());
        $this->manager->persist($commande);

        return $commande;
    }

    protected function addProduit(CommandeInterface $commande, $produitReference, $isFood = false)
    {
        if ($isFood) {
            $commandeProduit = new CommandeProduitLunch();
        } else {
            $commandeProduit = new CommandeProduit();
        }
        $commandeProduit->setCommande($commande);
        $commandeProduit->setProduit($this->getReference($produitReference));
        $commandeProduit->setQuantite(2);
        $this->manager->persist($commandeProduit);
    }


    public function getOrder()
    {
        return 4;
    }

}

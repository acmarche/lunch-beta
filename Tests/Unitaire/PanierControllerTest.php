<?php

namespace AcMarche\LunchBundle\Tests\Unitaire;

use AcMarche\LunchBundle\Entity\Commerce;
use AcMarche\LunchBundle\Entity\Produit;
use AcMarche\LunchBundle\Service\PanierUtil;
use AcMarche\LunchBundle\Service\ProduitUtil;
use AcMarche\LunchBundle\Tests\BaseUnit;
use Symfony\Bundle\FrameworkBundle\Client;

class PanierControllerTest extends BaseUnit
{
    private $produit;
    private $commerce;
    private $url;

    public function __construct()
    {
        parent::__construct();

        $commerce = $this->getCommerce(['user' => 'babe']);
        $produits = $commerce->getProduits();
        $produit = $produits[0];

        $url = '/produits/'.$produit->getId();

        $this->produit = $produit;
        $this->commerce = $commerce;
        $this->url = $url;
    }


    public function testAdd()
    {
        $panierUtil = $this->createMock(PanierUtil::class);
        $produitUtil = $this->createMock(ProduitUtil::class);

        $produitUtil->expects($this->once())->method('checkQuantite');

        $quantite = 0;
        $commercePorte = $this->getCommerce(['user' => 'porte']);
        $commerceBabe = $this->getCommerce(['user' => 'babe']);
        $user = $this->getUser(['username' => 'zora']);

        $produits = $this->getProduits(['commerce' => $commercePorte]);
        $produitPorte = $produits[0];

        $produits = $this->getProduits(['commerce' => $commercePorte]);
        $produitBabe = $produits[0];

        $panierUtil->expects($this->any())
            ->method('checkCommerce')
            ->willReturn($commercePorte);

        $this->assertTrue($produitUtil->checkQuantite(5));

        /*  try {

          } catch (\Exception $exception) {
              var_dump($exception);
              $this->assertEquals(
                  'Quantité minimum = 1',
                  $exception->getMessage()
              );
          }


          $panierUtil->checkProduitIsOwnedCommerce($produitBabe, $commercePorte);

          $panierUtil->commandeExistPanier($commercePorte, $produitPorte, $user);
          $commandeProduit = $panierUtil->produitExistPanier($produitPorte, $commercePorte, $user);


          try {
              $panierUtil->addProduit($produitPorte, $commercePorte, $user, $quantite);
          } catch (\Exception $exception) {
              var_dump($exception->getMessage());
          }

          dump($commandeProduit->getQuantite());*/

    }

    public function createProduitNoDb()
    {
        $produit = new Produit();
        $produit->setNom("Faux");
        $produit->setPrixHtva(5);

        return $produit;

    }
}

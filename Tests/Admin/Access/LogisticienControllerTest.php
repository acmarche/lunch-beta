<?php

namespace AcMarche\LunchBundle\Tests\Admin\Access;

use AcMarche\LunchBundle\Tests\BaseUnit;

class LogisticienControllerTest extends BaseUnit
{
    public function testIndex()
    {
        $url = '/admin/logisticien/';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceBabe, 403);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testShowProduitBabe()
    {
        $commerce = $this->getCommerce(['user' => 'babe']);
        $produits = $this->getProduits(['commerce' => $commerce]);
        $produit = $produits[0];

        $url = '/admin/produit/'.$produit->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceBabe, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testEditProduitBabe()
    {
        $commerce = $this->getCommerce(['user' => 'babe']);
        $produits = $this->getProduits(['commerce' => $commerce]);
        $produit = $produits[0];

        $url = '/admin/produit/'.$produit->getId().'/edit';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceBabe, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testCantViewlobet()
    {
        $commerce = $this->getCommerce(['user' => 'lobet']);

        $commande = $this->getCommande(
            [
                'valide' => false,
                'paye' => true,
                'commerce' => $commerce,
                'user' => 'homer',
            ]
        );

        $nom = "Commande ".$commande->getId();

        $url = '/admin/logisticien/';

        $crawler = $this->executeUrl($url, $this->logisticien, 200);

        $this->assertEquals(
            0,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );
    }

    public function testCantViewMemo()
    {
        $commerce = $this->getCommerce(['user' => 'memo']);

        $commande = $this->getCommande(
            [
                'valide' => false,
                'paye' => true,
                'commerce' => $commerce,
                'user' => 'homer',
            ]
        );

        $nom = "Commande ".$commande->getId();

        $url = '/admin/logisticien/';

        $crawler = $this->executeUrl($url, $this->logisticien, 200);

        $this->assertEquals(
            0,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );
    }

    public function testCanViewlobet()
    {
        $commerce = $this->getCommerce(['user' => 'lobet']);

        $commande = $this->getCommande(
            [
                'valide' => true,
                'paye' => true,
                'commerce' => $commerce,
                'user' => 'homer',
            ]
        );

        $nom = "Commande ".$commande->getId();

        $url = '/admin/logisticien/';

        $crawler = $this->executeUrl($url, $this->logisticien, 200);

        $this->assertEquals(
            1,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );
    }

    public function testCanViewMemo()
    {
        $commerce = $this->getCommerce(['user' => 'memo']);

        $commande = $this->getCommande(
            [
                'valide' => true,
                'paye' => true,
                'commerce' => $commerce,
                'user' => 'homer',
            ]
        );

        $nom = "Commande ".$commande->getId();

        $url = '/admin/logisticien/';

        $crawler = $this->executeUrl($url, $this->logisticien, 200);

        $this->assertEquals(
            1,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );
    }

    public function testCanViewMidi()
    {
        $commerce = $this->getCommerce(['user' => 'midi']);

        $commande = $this->getCommande(
            [
                'valide' => true,
                'paye' => true,
                'commerce' => $commerce,
                'user' => 'homer',
            ],
            true
        );

        $nom = "Commande lunch ".$commande->getId();

        $url = '/admin/logisticien/';

        $crawler = $this->executeUrl($url, $this->logisticien, 200);

        $this->assertEquals(
            1,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );
    }

}

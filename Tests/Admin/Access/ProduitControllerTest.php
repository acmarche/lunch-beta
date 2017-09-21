<?php

namespace AcMarche\LunchBundle\Tests\Admin\Access;

use AcMarche\LunchBundle\Tests\BaseUnit;

class ProduitControllerTest extends BaseUnit
{
    public function testIndex()
    {
        $url = '/admin/produit/';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceBabe, 200);
        $this->executeUrl($url, $this->commerceMemo, 200);
        $this->executeUrl($url, $this->commerceLobet, 200);
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
        $this->executeUrl($url, $this->commerceMemo, 403);
        $this->executeUrl($url, $this->commerceLobet, 403);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testShowProduitPorte()
    {
        $commerce = $this->getCommerce(['user' => 'porte']);
        $produits = $this->getProduits(['commerce' => $commerce]);
        $produit = $produits[0];

        $url = '/admin/produit/'.$produit->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceBabe, 403);
        $this->executeUrl($url, $this->commerceMemo, 403);
        $this->executeUrl($url, $this->commerceLobet, 403);
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
        $this->executeUrl($url, $this->commerceMemo, 403);
        $this->executeUrl($url, $this->commerceLobet, 403);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testEditProduitPorte()
    {
        $commerce = $this->getCommerce(['user' => 'porte']);
        $produits = $this->getProduits(['commerce' => $commerce]);
        $produit = $produits[0];

        $url = '/admin/produit/'.$produit->getId().'/edit';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceBabe, 403);
        $this->executeUrl($url, $this->commerceMemo, 403);
        $this->executeUrl($url, $this->commerceLobet, 403);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testShowProduitLobet()
    {
        $commerce = $this->getCommerce(['user' => 'lobet']);
        $produits = $this->getProduits(['commerce' => $commerce]);
        $produit = $produits[0];

        $url = '/admin/produit/'.$produit->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceBabe, 403);
        $this->executeUrl($url, $this->commerceMemo, 403);
        $this->executeUrl($url, $this->commerceLobet, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testEditProduitLobet()
    {
        $commerce = $this->getCommerce(['user' => 'lobet']);
        $produits = $this->getProduits(['commerce' => $commerce]);
        $produit = $produits[0];

        $url = '/admin/produit/'.$produit->getId().'/edit';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceBabe, 403);
        $this->executeUrl($url, $this->commerceMemo, 403);
        $this->executeUrl($url, $this->commerceLobet, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }


}

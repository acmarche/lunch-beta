<?php

namespace AcMarche\LunchBundle\Tests\Admin\Access;

use AcMarche\LunchBundle\Tests\BaseUnit;

class CommandeLunchControllerTest extends BaseUnit
{
    public function t2estIndex()
    {
        $url = '/admin/commande/lunch/';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceBabe, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testCommandePortePasPaye()
    {
        $porte = $this->getCommerce(['user' => 'porte']);

        $commande = $this->getCommande([
            'paye' => 0,
            'commerce' => $porte,
            'user' => 'homer'
        ], true);

        $url = '/admin/commande/lunch/' . $commande->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceBabe, 403);
        $this->executeUrl($url, $this->clientHomer, 200);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testCommandePortePaye()
    {
        $porte = $this->getCommerce(['user' => 'porte']);

        $commande = $this->getCommande([
            'paye' => true,
            'commerce' => $porte,
            'user' => 'homer'
        ], true);

        $url = '/admin/commande/lunch/' . $commande->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceBabe, 403);
        $this->executeUrl($url, $this->clientHomer, 200);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testCommandeBabePasPaye()
    {
        $babe = $this->getCommerce(['user' => 'babe']);

        $commande = $this->getCommande([
            'paye' => false,
            'commerce' => $babe,
            'user' => 'zora'
        ], true);

        $url = '/admin/commande/lunch/' . $commande->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceBabe, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 200);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testCommandeBabePaye()
    {
        $babe = $this->getCommerce(['user' => 'babe']);

        $commande = $this->getCommande([
            'paye' => true,
            'commerce' => $babe,
            'user' => 'zora'
        ], true);

        $url = '/admin/commande/lunch/' . $commande->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceBabe, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 200);
        $this->executeUrl($url, $this->anonyme, 302);
    }

}

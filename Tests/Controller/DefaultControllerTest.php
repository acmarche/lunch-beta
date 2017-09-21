<?php

namespace AcMarche\LunchBundle\Tests\Controller;

use AcMarche\LunchBundle\Tests\BaseUnit;

class DefaultControllerTest extends BaseUnit
{
    public function testIndex()
    {
        //     print_r($this->logisticien->getResponse()->getContent());
        $url = '/';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceBabe, 200);
        $this->executeUrl($url, $this->clientHomer, 200);
        $this->executeUrl($url, $this->clientZora, 200);
        $this->executeUrl($url, $this->anonyme, 200);
    }

    public function testUrls()
    {
        $urls = ['/produits/', '/categories/', '/commerces/', '/about'];

        foreach ($urls as $url) {
            $this->executeUrl($url, $this->admin, 200);
            $this->executeUrl($url, $this->logisticien, 200);
            $this->executeUrl($url, $this->commercePorte, 200);
            $this->executeUrl($url, $this->commerceBabe, 200);
            $this->executeUrl($url, $this->clientHomer, 200);
            $this->executeUrl($url, $this->clientZora, 200);
            $this->executeUrl($url, $this->anonyme, 200);
        }
    }

    public function testMonCompte()
    {
        $url = '/utilisateur/';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceBabe, 200);
        $this->executeUrl($url, $this->clientHomer, 200);
        $this->executeUrl($url, $this->clientZora, 200);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testCategorie()
    {
        $args = ['nom' => 'Lunchs chauds'];
        $categorie = $this->getCategorie($args);
        $url = '/categories/'.$categorie->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceBabe, 200);
        $this->executeUrl($url, $this->clientHomer, 200);
        $this->executeUrl($url, $this->clientZora, 200);
        $this->executeUrl($url, $this->anonyme, 200);
    }

}

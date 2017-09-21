<?php

namespace AcMarche\LunchBundle\Tests\Admin\Access;

use AcMarche\LunchBundle\Tests\BaseUnit;

class ValiderControllerTest extends BaseUnit
{
    public function testIndex()
    {
        $url = '/admin/validation/';

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->commercePorte, 200);
        $this->executeUrl($url, $this->commerceBabe, 200);
        $this->executeUrl($url, $this->commerceLobet, 200);
        $this->executeUrl($url, $this->commerceMemo, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testValidationlobet()
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

        $url = '/admin/validation/';

        $crawler = $this->executeUrl($url, $this->commerceLobet, 200);

        $this->assertEquals(
            1,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );

        $crawler = $this->executeUrl($url, $this->commerceMemo, 200);

        $this->assertEquals(
            0,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );

    }

    public function testValidationMemo()
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

        $url = '/admin/validation/';

        $crawler = $this->executeUrl($url, $this->commerceMemo, 200);

        $this->assertEquals(
            1,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );

        $crawler = $this->executeUrl($url, $this->commerceLobet, 200);

        $this->assertEquals(
            0,
            $crawler->filter('td:contains("'.$nom.'")')->count()
        );
    }


}

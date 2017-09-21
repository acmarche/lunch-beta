<?php

namespace AcMarche\LunchBundle\Tests\Admin\Traitement;

use AcMarche\LunchBundle\Tests\BaseUnit;

class LivraisonControllerTest extends BaseUnit
{
    public function testCommandeAccessTraiter()
    {
        $commande = $this->getCommande(['user' => 'zora', 'paye' => 1], true);
        $url = '/admin/logisticien/livrer/'.$commande->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->commerceBabe, 403);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testCommandeLunch()
    {
        $url = '/admin/logisticien/';
        $this->executeUrl($url, $this->logisticien, 200);

        $commande = $this->getCommande(['user' => 'zora', 'paye' => 1], true);

        $url = '/admin/commande/lunch/'.$commande->getId();

        $crawler = $this->executeUrl($url, $this->logisticien, 200);

        $crawler = $this->logisticien->click($crawler->selectLink('Traiter')->link());

        $form = $crawler->selectButton('Valider la livraison')->form(
            [
                'traiter_commande[livraisonRemarque]' => 'Ok fait',
            ]
        );
        $form['traiter_commande[livre]']->tick();

        $this->logisticien->submit($form);
        $crawler = $this->logisticien->followRedirect();

        $this->assertEquals(
            0,
            $crawler->filter('td:contains("Zora")')->count()
        );
    }


    public function testCommandeClassic()
    {
        $url = '/admin/logisticien/';
        $this->executeUrl($url, $this->logisticien, 200);

        $commerce = $this->getCommerce(['user' => 'lobet']);
        $commande = $this->getCommande(['user' => 'homer', 'paye' => 1, 'commerce' => $commerce]);

        $url = '/admin/commande/'.$commande->getId();

        $crawler = $this->executeUrl($url, $this->logisticien, 200);

        $crawler = $this->logisticien->click($crawler->selectLink('Traiter')->link());

        $form = $crawler->selectButton('Valider la livraison')->form(
            [
                'traiter_commande[livraisonRemarque]' => 'Ok classic',
            ]
        );
        $form['traiter_commande[livre]']->tick();

        $this->logisticien->submit($form);
        $crawler = $this->logisticien->followRedirect();

        $this->assertEquals(
            0,
            $crawler->filter('td:contains("Homer")')->count()
        );
    }

}

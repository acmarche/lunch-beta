<?php

namespace AcMarche\LunchBundle\Tests\Admin\Traitement;

use AcMarche\LunchBundle\Tests\BaseUnit;

class ArchiveControllerTest extends BaseUnit
{

    /**
     *
     */
    public function testArchive()
    {
        $url = '/admin/commande/archive/';
        $crawler = $this->executeUrl($url, $this->logisticien, 200);

        $form = $crawler->selectButton('Rechercher')->form(
            [
                'search_commande[user]' => 'zora',
            ]
        );

        $this->admin->submit($form);

        $this->assertEquals(
            1,
            $crawler->filter('td:contains("Zora")')->count()
        );
        $this->assertEquals(
            1,
            $crawler->filter('td:contains("Friterie Babe")')->count()
        );
    }
}

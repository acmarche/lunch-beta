<?php

namespace AcMarche\LunchBundle\Tests\Admin\Traitement;

use AcMarche\LunchBundle\Tests\BaseUnit;

class ExportControllerTest extends BaseUnit
{
    public function testCommandeNonPayePdf()
    {
        $commande = $this->getCommande(['user' => 'zora', 'paye' => 0],true);

        $url = '/export/pdf/commandelunch/'.$commande->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 200);
        $this->executeUrl($url, $this->commerceBabe, 200);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->logisticien, 403);
        $this->executeUrl($url, $this->anonyme, 302);
    }

    public function testCommandePayePdf()
    {
        $commande = $this->getCommande(['user' => 'zora', 'paye' => 1],true);

        $url = '/export/pdf/commandelunch/'.$commande->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 200);
        $this->executeUrl($url, $this->commerceBabe, 200);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->anonyme, 302);
    }

     public function testFacturePdf()
    {
        $commande = $this->getCommande(['user' => 'zora', 'paye' => 1],true);

        $url = '/facture/commandelunch/'.$commande->getId();

        $this->executeUrl($url, $this->admin, 200);
        $this->executeUrl($url, $this->clientHomer, 403);
        $this->executeUrl($url, $this->clientZora, 200);
        $this->executeUrl($url, $this->commerceBabe, 200);
        $this->executeUrl($url, $this->commercePorte, 403);
        $this->executeUrl($url, $this->logisticien, 200);
        $this->executeUrl($url, $this->anonyme, 302);
    }
}

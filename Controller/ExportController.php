<?php

namespace AcMarche\LunchBundle\Controller;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Entity\CommandeLunch;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeInterface;
use AcMarche\LunchBundle\Service\Bottin;
use AcMarche\LunchBundle\Service\CommandeUtil;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Export controller.
 *
 * @Route("/facture")
 *
 */
class ExportController extends Controller
{
    /**
     *
     * @Route("/commande/{id}", name="aclunch_commande_facture_pdf")
     * @Method("GET")
     * @Security("is_granted('show', commande)")
     */
    public function pdfAction(
        Commande $commande = null,
        CommandeUtil $commandeUtil,
        Bottin $bottin
    ) {
        $html = $this->getHttm($commande, $commandeUtil, $bottin);

        $name = 'Commande '.$commande->getId();

        $snappy = $this->get('knp_snappy.pdf');
        $snappy->setOption('footer-right', '[page]/[toPage]');

        return new Response(
            $snappy->getOutputFromHtml($html), 200, array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$name.'.pdf"',
            )
        );
    }

    /**
     *
     * @Route("/commandelunch/{id}", name="aclunch_commande_lunch_facture_pdf")
     * @Method("GET")
     * @Security("is_granted('show', commande)")
     */
    public function pdfLunchAction(
        CommandeLunch $commande = null,
        CommandeUtil $commandeUtil,
        Bottin $bottin
    ) {
        $html = $this->getHttm($commande, $commandeUtil, $bottin);

        $name = 'Facture-Commande-'.$commande->getId();

        $snappy = $this->get('knp_snappy.pdf');
        $snappy->setOption('footer-right', '[page]/[toPage]');

        return new Response(
            $snappy->getOutputFromHtml($html), 200, array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$name.'.pdf"',
            )
        );
    }

    protected function getHttm(CommandeInterface $commande, CommandeUtil $commandeUtil, Bottin $bottin)
    {

        $html = $this->renderView(
            '@AcMarcheLunch/Facture/head.html.twig',
            array(
                'title' => 'Facture',
            )
        );

        $fiche = false;
        $commerce = $commande->getCommerce();
        $couts = $commandeUtil->getCoutsCommande($commande);
        try {
            $fiche = $bottin->getFiche($commerce->getBottinId());
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        $html .= $this->renderView(
            '@AcMarcheLunch/Facture/facture.html.twig',
            array(
                'commerce' => $commerce,
                'fiche' => $fiche,
                'commande' => $commande,
                'couts' => $couts,
                'pdf' => true,
                'user' => $this->getUser(),
            )
        );

        $html .= $this->renderView('@AcMarcheLunch/Facture/foot.html.twig', array());

        return $html;
    }
}

<?php

namespace AcMarche\LunchBundle\Controller\Admin;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Entity\CommandeLunch;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeInterface;
use AcMarche\LunchBundle\Service\CommandeUtil;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Export controller.
 *
 * @Route("/export")
 *
 */
class ExportController extends Controller
{
    /**
     *
     * @Route("/pdf/commande/{id}", name="aclunch_admin_commande_export_pdf")
     * @Method("GET")
     * @Security("is_granted('show', commande)")
     */
    public function pdfAction(Request $request, Commande $commande = null, CommandeUtil $commandeUtil)
    {
        $html = $this->getHttm($request, $commande, $commandeUtil);

        $name = 'commandes';

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
     * @Route("/pdf/commandelunch/{id}", name="aclunch_admin_commande_lunch_export_pdf")
     * @Method("GET")
     * @Security("is_granted('show', commande)")
     */
    public function pdfLunchAction(
        Request $request,
        CommandeLunch $commande = null,
        CommandeUtil $commandeUtil
    ) {
        $html = $this->getHttm($request, $commande, $commandeUtil);

        $name = 'commandes';

        $snappy = $this->get('knp_snappy.pdf');
        $snappy->setOption('footer-right', '[page]/[toPage]');

        return new Response(
            $snappy->getOutputFromHtml($html), 200, array(
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$name.'.pdf"',
            )
        );
    }

    protected function getHttm(Request $request, CommandeInterface $commande = null, CommandeUtil $commandeUtil)
    {
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $args = [];
        $key = "commande_search";

        if ($commande) {
            $commandes = [$commande];
        } else {
            if ($session->has($key)) {
                $args = unserialize($session->get($key));
            } else {
                $args = [];
            }//commande du jour
            $commandes = $em->getRepository(Commande::class)->search($args);
        }

        $html = $this->renderView(
            '@AcMarcheLunch/Admin/Pdf/head.html.twig',
            array(
                'title' => 'Liste des interventions',
            )
        );

        foreach ($commandes as $commande) {
            $couts = $commandeUtil->getCoutsCommande($commande);

            $html .= $this->renderView(
                '@AcMarcheLunch/Admin/Pdf/line.html.twig',
                array(
                    'commande' => $commande,
                    'couts' => $couts,
                    'pdf' => true,
                )
            );
        }
        $html .= $this->renderView('@AcMarcheLunch/Admin/Pdf/foot.html.twig', array());

        return $html;
    }
}

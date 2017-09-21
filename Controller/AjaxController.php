<?php

namespace AcMarche\LunchBundle\Controller;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Entity\CommandeLunch;
use AcMarche\LunchBundle\Service\CommandeUtil;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AjaxController
 * @Route("/ajax")
 *
 */
class AjaxController extends AbstractController
{
    /**
     * @Route("/commande/{id}", name="aclunch_ajax_getcommande")
     * @todo secure
     */
    public function commandeAction(Commande $commande, CommandeUtil $commandeUtil)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        //     $commandeUtil = $this->get('ac_marche_lunch.commandeUtil');

        /* if (!$this->isCsrfTokenValid('panierlunch', $request->request->get('token'))) {
             return $this->redirectToRoute('aclunch_home');
         }
         $quantiteProduit = $request->request->get('quantiteProduit');*/

        if ($commande->getUser() != $user->getUsername()) {
            return new JsonResponse(['error' => 'Interdit']);
        }

        $couts = $commandeUtil->getCoutsCommande($commande);

        return new JsonResponse(['data' => $couts]);
    }

    /**
     * @Route("/resume", name="aclunch_panier_resume")
     * @Method("GET")
     */
    public function resumeAction(CommandeUtil $commandeUtil)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $commandes = $em->getRepository(CommandeLunch::class)->getPanier($user);

        $totalTvac = $countProduits = 0;

        if ($commandes) {
            foreach ($commandes as $commande) {
                foreach ($commande->getCommandeProduits() as $commandeProduit) {
                    $countProduits += $commandeProduit->getQuantite();
                }
                if ($countProduits > 0) {
                    $couts = $commandeUtil->getCoutsCommande($commande);
                    $totalTvac += $couts['totalTvac'];
                }
            }
        }

        return $this->render(
            '@AcMarcheLunch/Panier/resume.html.twig',
            [
                'totalTvac' => $totalTvac,
                'nombreArticles' => $countProduits,
            ]
        );
    }

    /**
     * @Route("/list", name="aclunch_panier_list")
     * @Method("GET")
     */
    public function listAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $commandes = $em->getRepository(CommandeLunch::class)->getPanier($user);
        $commandeProduits = [];
        $countProduits = 0;

        if ($commandes) {
            foreach ($commandes as $commande) {
                $countProduits += count($commande->getCommandeProduits());
                foreach ($commande->getCommandeProduits() as $commandeProduit) {
                    $commandeProduits[] = $commandeProduit;
                }
            }
        }

        return $this->render(
            '@AcMarcheLunch/Panier/list.html.twig',
            [
                'nombreProduits' => $countProduits,
                'commandeProduits' => $commandeProduits,
            ]
        );
    }


}

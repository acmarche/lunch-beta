<?php

namespace AcMarche\LunchBundle\Controller;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Form\LivraisonType;
use AcMarche\LunchBundle\Service\CommandeUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class LivraisonController
 * @package AcMarche\LunchBundle\Controller
 * @Route("/livraison")
 * @Security("has_role('ROLE_LUNCH_CLIENT')")
 */
class LivraisonController extends Controller
{
    /**
     * @Route("/", name="aclunch_livraison")
     * @Template()
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $dateFutur = $this->getDateFutur();
        $commandes = $em->getRepository(Commande::class)->getPanier($user);

        if(count($commandes) ==0) {
             return $this->redirectToRoute('aclunch_panier');
        }

        $form = $this->createForm(LivraisonType::class, ['dateLivraison' => $dateFutur]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $dateLivraison = $data['dateLivraison'];
            $lieuLivraison = $data['lieuLivraison'];

            if ($dateFutur->format('d-m-Y') > $dateLivraison->format('d-m-Y')) {
                $this->addFlash('error', 'Date incorrecte');
                return $this->redirectToRoute('aclunch_livraison');
            }

            foreach ($commandes as $commande) {
                $commande->setDateLivraison($dateLivraison);
                $commande->setLieuLivraison($lieuLivraison);
                $em->persist($commande);
                $em->flush();

                return $this->redirectToRoute('aclunch_livraison_paiement');
            }
        }

        return [
            'livraison_form' => $form->createView(),
        ];
    }

    /**
     * @Route("/paiement", name="aclunch_livraison_paiement")
     * @Template()
     */
    public function paiementAction(CommandeUtil $commandeUtil)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $commandes = $em->getRepository(Commande::class)->getPanier($user);

        foreach ($commandes as $commande) {
            $commande->setCouts($commandeUtil->getCoutsCommande($commande));
        }

        return [
            'commandes' => $commandes,
        ];
    }

    /**
     * @return \DateTime|static
     */
    protected function getDateFutur()
    {
        $today = new \DateTime();
        $dayNumber = $today->format('w');//de 0 a 6
        $hour = $today->format('G');//de 0 a 23
        $minute = $today->format('i');//de 0 a 59
        //todo we

        if ($hour > 10) {
            return $today->modify('+1 day');
        }

        return $today;
    }

}

<?php

namespace AcMarche\LunchBundle\Controller;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Event\CommandeEvent;
use AcMarche\LunchBundle\Service\CommandeUtil;
use AcMarche\LunchBundle\Service\ProduitUtil;
use AcMarche\LunchBundle\Service\StripeUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Stripe\Charge;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PaiementController
 * @package AcMarche\LunchBundle\Controller
 * @Route("/paiement")
 * @Security("has_role('ROLE_LUNCH_CLIENT')")
 */
class PaiementController extends Controller
{
    /**
     *
     * @Route("/validation/{id}", name="aclunch_paiement_validation")
     * @Method("POST")
     */
    public function validationAction(Request $request, Commande $commande, CommandeUtil $commandeUtil, StripeUtil $stripeUtil, ProduitUtil $produitUtil)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $stripeToken = $request->request->get('stripeToken');
        $stripeTokenType = $request->request->get('stripeTokenType');
        $stripeEmail = $request->request->get('stripeEmail');
        $clientIp = $request->request->get('clientIp');
        $token = $request->request->get('token');

        $created = $token['created'];
        /*   $livemod = $token->livemod;
           $type = $token->type;//card
           $used = $token->used;
           $clientIp = $token->client_ip;
           $card = $token->card; //object*/

        $commande->setCouts($commandeUtil->getCoutsCommande($commande));

        $isFood = '';//todo def

        $charge = $stripeUtil->chargeCard($stripeToken, $commande);

        if ($charge instanceof Charge) {
            $stripeUtil->createEntityCharge($charge, $commande, $isFood);
            $this->finalyseComande($commande, $produitUtil);
            $em->flush();

            $event = new CommandeEvent($commande);

            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(CommandeEvent::COMMANDE_PAYE, $event);

            return new JsonResponse(['data' => 'Paiement ok']);
        } else {
            return new JsonResponse(['error' => $charge]);
        }
    }

    protected function finalyseComande(Commande $commande, ProduitUtil $produitUtil)
    {
        $em = $this->getDoctrine()->getManager();

        $commande->setPaye(true);
        $commande->setCommerceNom($commande->getCommerce()->getNom());
        foreach ($commande->getCommandeProduits() as $commandeProduit) {
            $commandeProduit->setTva($produitUtil->getTvaApplicable($commandeProduit->getProduit()));
            $commandeProduit->setPrixHtva($commandeProduit->getProduit()->getPrixHtva());
            $commandeProduit->setProduitNom($commandeProduit->getProduit()->getNom());
        }

        $em->persist($commande);
    }

}

<?php

namespace AcMarche\LunchBundle\Controller;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Entity\CommandeLunch;
use AcMarche\LunchBundle\Entity\CommandeProduit;
use AcMarche\LunchBundle\Entity\CommandeProduitLunch;
use AcMarche\LunchBundle\Entity\Commerce;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeProduitInterface;
use AcMarche\LunchBundle\Entity\Produit;
use AcMarche\LunchBundle\Event\PanierEvent;
use AcMarche\LunchBundle\Service\CommandeUtil;
use AcMarche\LunchBundle\Service\PanierUtil;
use AcMarche\LunchBundle\Service\ProduitUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PanierController
 * @package AcMarche\LunchBundle\Controller
 * @Route("/panier")
 *
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/", name="aclunch_panier")
     * @Security("has_role('ROLE_LUNCH_CLIENT')")
     * @Template()
     */
    public function indexAction(CommandeUtil $commandeUtil, PanierUtil $panierUtil)
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->dispatch(PanierEvent::PANIER_INDEX, new PanierEvent());

        $user = $this->getUser();
        $ruptures = $indisponibles = $commandes = null;

        $form = $this->createDeleteForm();

        $em = $this->getDoctrine()->getManager();

        $commandes = $em->getRepository(Commande::class)->getPanier($user);
        $commandesLunch = $em->getRepository(CommandeLunch::class)->getPanier($user);

        /* @todo check
         *  $data = $panierUtil->checkPanier($commandes, $user);
         *
         * if (count($data) > 0) {
         * $ruptures = isset($data['rupture']) ? $data['rupture'] : null;
         * $indisponibles = isset($data['indisponible']) ? $data['indisponible'] : null;
         * }*/

        foreach ($commandes as $commande) {
            $commande->setCouts($commandeUtil->getCoutsCommande($commande));
        }

        foreach ($commandesLunch as $commande) {
            $commande->setCouts($commandeUtil->getCoutsCommande($commande));
        }

        return [
            'ruptures' => $ruptures,
            'indisponibles' => $indisponibles,
            'commandes' => $commandesLunch,
            'commandesLunch' => $commandesLunch,
            'formDelete' => $form->createView(),
        ];
    }


    /**
     * Ajoute un produit dans le panier
     *
     * @Route("/add/{id}", name="aclunch_panier_add")
     * @Method("POST")
     * @Security("has_role('ROLE_LUNCH_CLIENT')")
     */
    public function addAction(Request $request, Produit $produit, PanierUtil $panierUtil)
    {
        $user = $this->getUser();
        $token = $request->request->get('token');

        if (!$this->isCsrfTokenValid($produit, $token)) {
            return new JsonResponse(['data' => ['error' => 'Token invalide']], 403);
        }

        $quantite = intval($request->request->get('quantiteProduit'));
        $commerceId = intval($request->request->get('commerce'));

        if (!$commerce = $panierUtil->checkCommerce($commerceId)) {
            return new JsonResponse(['data' => ['error' => 'Commerce inconnu']]);
        }

        try {
            $panierUtil->addProduit($produit, $commerce, $user, $quantite);
        } catch (\Exception $exception) {
            return new JsonResponse(['data' => ['error' => $exception->getMessage()]]);
        }

        $mot = " ajouté";
        if ($quantite > 1) {
            $mot = " ajoutés";
        }

        return new JsonResponse(['data' => $quantite.$mot]);
    }

    /**
     *
     * @Route("/update/{id}", name="aclunch_panier_update")
     * @Method("POST")
     * Security("is_granted('edit', commandeProduit)") @todo ajax ?
     */
    public function updateAction(
        Request $request,
        CommandeProduitLunch $commandeProduit,
        PanierUtil $panierUtil,
        CommandeUtil $commandeUtil,
        ProduitUtil $produitUtil
    ) {
        $user = $this->getUser();
        $commande = $commandeProduit->getCommande();

       // var_dump($request->request->get('token'));
      //  $t = $this->container->get('security.csrf.token_manager');
      //  var_dump($t->getToken($commandeProduit));

        if (!$this->isCsrfTokenValid($commandeProduit, $request->request->get('token'))) {
            return new JsonResponse(['data' => ['error' => 'Token invalide']], 403);
        }

        $quantite = intval($request->request->get('quantiteProduit'));

        try {
            $panierUtil->checkCommandeProduit($commandeProduit, $user);
            $panierUtil->updateProduit($commandeProduit, $quantite);
        } catch (\Exception $exception) {
            return new JsonResponse(['data' => ['error' => $exception->getMessage()]]);
        }

        if ($quantite) {
            $newPrix = $produitUtil->getRound(
                $produitUtil->getPrixTvacByQuantite($commandeProduit->getProduit(), $quantite)
            );

            $couts = $commandeUtil->getCoutsCommande($commande);
            $total = $produitUtil->getRound($couts['total']);

            return new JsonResponse(
                [
                    'data' => [
                        'produit' => $newPrix.' €',
                        'commande' => $total.' €',
                    ],
                ]
            );
        }

        return new JsonResponse(['data' => ['error' => 'Erreur inconnue']]);
    }

    /**
     *
     * @Route("/delete", name="aclunch_panier_produit_delete")
     * @Method("DELETE")
     * @Security("has_role('ROLE_LUNCH_CLIENT')")
     */
    public function deleteAction(Request $request, PanierUtil $panierUtil)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $commandeProduitId = $request->request->get('commandeProduit');
        $commandeProduit = $em->getRepository(CommandeProduit::class)->find($commandeProduitId);

        if (!$commandeProduit) {
            $this->addFlash('danger', 'Produit introuvable');

            return $this->redirectToRoute('aclunch_panier');
        }

        $form = $this->createDeleteForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (!$panierUtil->canAccessCommandeProduit($commandeProduit, $user)) {
                $this->addFlash('danger', "Vous n'avez pas accès a cette commande.");

                return $this->redirectToRoute('aclunch_panier');
            }

            if (!$panierUtil->commandeProduitExistInPanier($commandeProduit)) {
                $this->addFlash('danger', "Ce produit ne se trouve pas dans votre panier");

                return $this->redirectToRoute('aclunch_panier');
            }

            $panierUtil->removeCommandeProduit($commandeProduit);

            $this->addFlash('success', 'Le produit a bien été supprimé du panier.');
        }

        return $this->redirectToRoute('aclunch_panier');
    }

    private function createDeleteForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('aclunch_panier_produit_delete'))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Vider le panier
     * @Route("/vider", name="aclunch_panier_vider")
     * @Method("POST")
     * @Security("has_role('ROLE_LUNCH_CLIENT')")
     */
    public function viderAction(Request $request, PanierUtil $panierUtil)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $commandes = $em->getRepository(Commande::class)->getPanier($user);

        $form = $this->createViderForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $panierUtil->clean($commandes);
            $this->addFlash('success', 'La panier a bien été vidé.');
        }

        return $this->redirectToRoute('aclunch_panier');
    }

    private function createViderForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('aclunch_panier_vider'))
            ->setMethod('POST')
            ->getForm();
    }

    /**
     * Ajoute un produit dans le panier
     *
     * @Route("/test/", name="aclunch_panier_test")
     * @Method("GET")
     * @Security("has_role('ROLE_LUNCH_CLIENT')")
     * @Template()
     */
    public function testAction(Request $request, PanierUtil $panierUtil)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produit::class)->findOneBy(['nom' => 'Anglais']);
        $commerce = $em->getRepository(Commerce::class)->findOneBy(['nom' => 'Bonne porte']);

        $user = $this->getUser();

        $quantite = 3;

        if (!$panierUtil->checkCommerce($commerce->getId())) {
            throw new \Exception('Commerce inconnu ou inactif');
        }

        $panierUtil->commandeExistPanier($commerce, $produit, $user);
        $commandeProduit = $panierUtil->produitExistPanier($produit, $commerce, $user);

        try {
            $commandeProduit = $panierUtil->addProduit($produit, $commerce, $user, $quantite);
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }

        $result = [];

        $result["food"]['id1'] = $commandeProduit->getId();
        $result["food"]['q1'] = $commandeProduit->getQuantite();

        $commandeProduit = $panierUtil->updateProduit($commandeProduit, 6);

        $result["food"]['id2'] = $commandeProduit->getId();
        $result["food"]['q2'] = $commandeProduit->getQuantite();

        $result = $this->testFood($panierUtil, $result);

        return ['result' => $result];
    }

    protected function testFood(PanierUtil $panierUtil, $result)
    {
        $em = $this->getDoctrine()->getManager();
        $produit = $em->getRepository(Produit::class)->findOneBy(['nom' => 'Cheval bascule']);
        $commerce = $em->getRepository(Commerce::class)->findOneBy(['nom' => 'Boite à Malice']);

        $user = $this->getUser();

        $quantite = 3;

        if (!$panierUtil->checkCommerce($commerce->getId())) {
            throw new \Exception('Commerce inconnu ou inactif');
        }

        $panierUtil->commandeExistPanier($commerce, $produit, $user);
        $commandeProduit = $panierUtil->produitExistPanier($produit, $commerce, $user);

        try {
            $commandeProduit = $panierUtil->addProduit($produit, $commerce, $user, $quantite);
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
        }

        $result["classic"]['id1'] = $commandeProduit->getId();
        $result["classic"]['q1'] = $commandeProduit->getQuantite();

        $commandeProduit = $panierUtil->updateProduit($commandeProduit, 6);

        $result["classic"]['id2'] = $commandeProduit->getId();
        $result["classic"]['q2'] = $commandeProduit->getQuantite();

        return $result;

    }


}

<?php

namespace AcMarche\LunchBundle\Controller;

use AcMarche\LunchBundle\Entity\CommandeProduit;
use AcMarche\LunchBundle\Entity\Produit;
use AcMarche\LunchBundle\Service\ProduitUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ProduitController
 * @package AcMarche\LunchBundle\Controller
 * @Route("/produits")
 */
class ProduitController extends Controller
{
    /**
     * Lists all produit entities.
     *
     * @Route("/", name="aclunch_produit_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $args = [];
        $produits = $em->getRepository(Produit::class)->search($args);

        return [
            'produits' => $produits,
        ];
    }

    /**
     * Finds and displays a produit entity.
     *
     * @Route("/{id}", name="aclunch_produit_show")
     * @Method("GET")
     *
     * @Template()
     */
    public function showAction(Produit $produit, ProduitUtil $produitUtil)
    {
        if (!$produitUtil->canDisplayOnsite($produit)) {
            throw $this->createNotFoundException('The product does not exist');
        }

        $csrf = $this->get('security.csrf.token_manager');
        $token = $csrf->refreshToken($produit);

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $commerce = $produit->getCommerce();

        $commandeProduit = $em->getRepository(CommandeProduit::class)->existInPanier($user, $commerce, $produit);

        return [
            'token' => $token,
            'produit' => $produit,
            'commandeProduit' => $commandeProduit,
        ];
    }
}

<?php

namespace AcMarche\LunchBundle\Controller;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Form\UtilisateurType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UtilisateurController
 * @package AcMarche\LunchBundle\Controller
 * @Route("/utilisateur")
 * @Security("has_role('ROLE_LUNCH_CLIENT')")
 */
class UtilisateurController extends Controller
{
    /**
     * Finds and displays a utilisateur entity.
     *
     * @Route("/", name="aclunch_utilisateur_show")
     * @Method("GET")
     *
     * @Template()
     */
    public function showAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $commandes = $em->getRepository(Commande::class)->findBy(['user' => $user->getUsername()]);

        return [
            'user' => $user,
            'commandes' => $commandes
        ];
    }

    /**
     * Displays a form to edit an existing categorie entity.
     *
     * @Route("/edit", name="aclunch_utilisateur_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        $editForm = $this->createForm(UtilisateurType::class, $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Modification effectuée');

            return $this->redirectToRoute('aclunch_utilisateur_show');
        }

        return [
            'user' => $user,
            'form' => $editForm->createView()
        ];
    }
}

<?php

namespace AcMarche\LunchBundle\Controller\Admin;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Event\CommandeEvent;
use AcMarche\LunchBundle\Form\ValiderCommandeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;

/**
 * ValiderController
 *
 * @Route("/admin/validation")
 * @Security("has_role('ROLE_LUNCH_COMMERCE')")
 */
class ValiderController extends AbstractController
{
    /**
     * Liste des commandes à valider
     *
     * @Route("/", name="aclunch_admin_commande_valider_index")
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $commandes = $em->getRepository(Commande::class)->getCommandeAValider($user);

        return [
            'commandes' => $commandes,
        ];
    }

    /**
     * Finds and displays a commande entity.
     *
     * @Route("/{id}", name="aclunch_admin_commande_valider_edit")
     * @Method({"GET","POST"})
     * @Template()
     * @Security("is_granted('validate', commande)")
     */
    public function editAction(Request $request, Commande $commande, EventDispatcher $eventDispatcher)
    {
        if ($commande->getValide()) {
            throw $this->createAccessDeniedException('La commande a déjà été validée');
        }

        $form = $this->createForm(ValiderCommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            $event = new CommandeEvent($commande);
            $eventDispatcher->dispatch(CommandeEvent::COMMANDE_VALIDE, $event);

            $this->addFlash('success', 'La commande a bien été validée');

            return $this->redirectToRoute('aclunch_admin_commande_show', ['id'=>$commande->getId()]);
        }

        return [
            'commande' => $commande,
            'form' => $form->createView(),
        ];
    }

}

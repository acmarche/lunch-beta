<?php

namespace AcMarche\LunchBundle\Controller\Admin;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Entity\CommandeLunch;
use AcMarche\LunchBundle\Entity\Commerce;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeInterface;
use AcMarche\LunchBundle\Form\CommandeType;
use AcMarche\LunchBundle\Form\Search\SearchCommandeType;
use AcMarche\LunchBundle\Service\CommandeUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Commande controller.
 *
 * @Route("/admin/commande")
 *
 */
class CommandeController extends AbstractController
{
    /**
     * Lists all commande entities.
     *
     * @Route("/", name="aclunch_admin_commande_index")
     * @Method("GET")
     * @Template()
     * @Security("has_role('ROLE_LUNCH_COMMERCE')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($user->hasRole('ROLE_LUNCH_ADMIN')) {
            $commandes = $em->getRepository(Commande::class)->search(['paye' => 2]);
            $commandesLunch = $em->getRepository(CommandeLunch::class)->search(['paye' => 2]);
        } else {
            $commandes = $em->getRepository(Commande::class)->getCommandeALivrerByCommerce($user);
            $commandesLunch = $em->getRepository(CommandeLunch::class)->getCommandeALivrerByCommerce($user);
        }

        return [
            'commandes' => $commandes,
            'commandesLunch' => $commandesLunch,
        ];
    }

    /**
     * Finds and displays a commande entity.
     *
     * @Route("/{id}", name="aclunch_admin_commande_show")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('show', commande)")
     */
    public function showAction(Commande $commande, CommandeUtil $commandeUtil)
    {
        $deleteForm = $this->createDeleteForm($commande);
        $couts = $commandeUtil->getCoutsCommande($commande);

        return [
            'commande' => $commande,
            'couts' => $couts,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Finds and displays a commande entity.
     *
     * @Route("/lunch/{id}", name="aclunch_admin_commande_lunch_show")
     * @Method("GET")
     * @Template()
     * @Security("is_granted('show', commande)")
     */
    public function showLunchAction(CommandeLunch $commande, CommandeUtil $commandeUtil)
    {
        $deleteForm = $this->createDeleteForm($commande);
        $couts = $commandeUtil->getCoutsCommande($commande);

        return [
            'commande' => $commande,
            'couts' => $couts,
            'delete_form' => $deleteForm->createView(),
        ];
    }


    /**
     * Displays a form to edit an existing commande entity.
     *
     * @Route("/{id}/edit", name="aclunch_admin_commande_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('EDIT', commande)")
     * @Template()
     */
    public function editAction(Request $request, CommandeInterface $commande)
    {
        $editForm = $this->createForm(CommandeType::class, $commande);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('aclunch_admin_commande_index');
        }

        return [
            'commande' => $commande,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a commande entity.
     *
     * @Route("/{id}", name="aclunch_admin_commande_delete")
     * @Method("DELETE")
     * @Security("is_granted('DELETE', commande)")
     */
    public function deleteAction(Request $request, CommandeInterface $commande)
    {
        $form = $this->createDeleteForm($commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commande);
            $em->flush();
        }

        return $this->redirectToRoute('aclunch_admin_commande_index');
    }

    /**
     * Creates a form to delete a commande entity.
     *
     * @param Commande $commande The commande entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(CommandeInterface $commande)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('aclunch_admin_commande_delete', array('id' => $commande->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete', 'attr' => array('class' => 'btn-danger')))
            ->getForm();
    }

    /**
     * Liste des commandes traitees
     *
     * @Route("/archive/", name="aclunch_admin_commande_archive")
     * @Method("GET")
     * @Security("has_role('ROLE_LUNCH_COMMERCE') or has_role('ROLE_LUNCH_LOGISTICIEN')")
     * @Template()
     */
    public function archiveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $session = $request->getSession();
        $key = "commande_search";
        $search = false;
        $args = [
            'paye' => 1,
            'livre' => 1,
            'valide' => 1,
        ];

        if ($session->has($key)) {
            $search = true;
            $args = unserialize($session->get($key));
        }

        $form = $this->createForm(
            SearchCommandeType::class,
            $args,
            [
                'method' => 'GET',
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('raz')->isClicked()) {
                $session->remove($key);
                $this->addFlash('info', 'La recherche a bien été réinitialisée.');

                return $this->redirectToRoute('aclunch_admin_commande_archive');
            }

            $search = true;
            $args = $form->getData();
            $commerces = [];
            if ($user->hasRole('ROLE_LUNCH_COMMERCE') and !isset($args['commerce'])) {
                foreach ($em->getRepository(Commerce::class)->getCommercesOwnedByUser(
                    $user
                ) as $commerce) {
                    $commerces[] = $commerce->getId();
                }
                $args['commerces'] = $commerces;
            }

            $session->set($key, serialize($args));
        }

        $commandes = $em->getRepository(Commande::class)->search($args);
        $commandesLunch = $em->getRepository(CommandeLunch::class)->search($args);

        return [
            'search' => $search,
            'search_form' => $form->createView(),
            'commandes' => $commandes,
            'commandesLunch' => $commandesLunch,
        ];
    }
}

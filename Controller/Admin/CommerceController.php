<?php

namespace AcMarche\LunchBundle\Controller\Admin;

use AcMarche\LunchBundle\Entity\Commerce;
use AcMarche\LunchBundle\Form\CommerceType;
use AcMarche\LunchBundle\Service\Bottin;
use AcMarche\LunchBundle\Service\FilterQuery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Commerce controller.
 *
 * @Route("/admin/commerce")
 * @Security("has_role('ROLE_LUNCH_COMMERCE') or has_role('ROLE_LUNCH_LOGISTICIEN')")
 */
class CommerceController extends AbstractController
{
    /**
     * Lists all commerce entities.
     *
     * @Route("/", name="aclunch_admin_commerce_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(FilterQuery $filterQuery)
    {
        $em = $this->getDoctrine()->getManager();

        $args = $filterQuery->getAllCommerces($this->getUser());
        $commerces = $em->getRepository(Commerce::class)->search($args);

        return [
            'commerces' => $commerces,
        ];
    }

    /**
     * Creates a new commerce entity.
     *
     * @Route("/new", name="aclunch_admin_commerce_new")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_LUNCH_ADMIN')")
     * @Template()
     */
    public function newAction(Request $request, Bottin $bottin)
    {
        try {
            $fiches = $bottin->getFichesForForm();
        } catch (\Exception $exception) {
            $this->addFlash('error', 'Fiches impossibles a obtenir');
            $fiches = [];
        }

        $commerce = new Commerce();
        $form = $this->createForm(
            CommerceType::class,
            $commerce,
            [
                'fiches' => $fiches,
            ]
        )->add('Create', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commerce);
            $em->flush();

            $this->addFlash('success', 'Le commerce a bin été ajouté');

            return $this->redirectToRoute('aclunch_admin_commerce_show', array('id' => $commerce->getId()));
        }

        return [
            'commerce' => $commerce,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a commerce entity.
     *
     * @Route("/{id}", name="aclunch_admin_commerce_show")
     * @Method("GET")
     * @Security("is_granted('show', commerce)")
     * @Template()
     */
    public function showAction(Commerce $commerce, Bottin $bottin)
    {
        $deleteForm = $this->createDeleteForm($commerce);

        $error = $fiche = false;
        try {
            $fiche = $bottin->getFiche($commerce->getBottinId());
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return [
            'error' => $error,
            'fiche' => $fiche,
            'commerce' => $commerce,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing commerce entity.
     *
     * @Route("/{id}/edit", name="aclunch_admin_commerce_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('edit', commerce)")
     * @Template()
     */
    public function editAction(Request $request, Commerce $commerce, Bottin $bottin)
    {
        try {
            $fiches = $bottin->getFichesForForm();
        } catch (\Exception $exception) {
            $this->addFlash('error', 'Fiches impossibles a obtenir');
            $fiches = [];
        }

        $editForm = $this->createForm(
            CommerceType::class,
            $commerce,
            [
                'requiredImage' => false,
                'fiches' => $fiches,
            ]
        )
            ->add('Update', SubmitType::class);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le commerce a bin été mis à jour');

            return $this->redirectToRoute('aclunch_admin_commerce_show', array('id' => $commerce->getId()));
        }

        return [
            'commerce' => $commerce,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a commerce entity.
     *
     * @Route("/{id}", name="aclunch_admin_commerce_delete")
     * @Security("is_granted('delete', commerce)")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Commerce $commerce)
    {
        $form = $this->createDeleteForm($commerce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commerce);
            $em->flush();
        }

        return $this->redirectToRoute('aclunch_admin_commerce_index');
    }

    /**
     * Creates a form to delete a commerce entity.
     *
     * @param Commerce $commerce The commerce entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Commerce $commerce)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('aclunch_admin_commerce_delete', array('id' => $commerce->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete', 'attr' => array('class' => 'btn-danger')))
            ->getForm();
    }
}

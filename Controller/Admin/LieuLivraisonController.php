<?php

namespace AcMarche\LunchBundle\Controller\Admin;

use AcMarche\LunchBundle\Entity\LieuLivraison;
use AcMarche\LunchBundle\Form\LieuLivraisonType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Lieu Livraison controller.
 *
 * @Route("/admin/lieulivraison")
 * @Security("has_role('ROLE_LUNCH_ADMIN')")
 */
class LieuLivraisonController extends AbstractController
{
    /**
     * Lists all lieulivraison entities.
     *
     * @Route("/", name="aclunch_admin_lieulivraison_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lieulivraisons = $em->getRepository(LieuLivraison::class)->findAll();

        return [
            'lieulivraisons' => $lieulivraisons,
        ];
    }

    /**
     * Creates a new lieulivraison entity.
     *
     * @Route("/new", name="aclunch_admin_lieulivraison_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $lieulivraison = new LieuLivraison();
        $form = $this->createForm(LieuLivraisonType::class, $lieulivraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($lieulivraison);
            $em->flush();

            $this->addFlash('success', 'Le lieux de livraison a bien été ajouté');

            return $this->redirectToRoute('aclunch_admin_lieulivraison_index');
        }

        return [
            'lieulivraison' => $lieulivraison,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a lieulivraison entity.
     *
     * @Route("/{id}", name="aclunch_admin_lieulivraison_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(LieuLivraison $lieulivraison)
    {
        $deleteForm = $this->createDeleteForm($lieulivraison);

        return [
            'lieulivraison' => $lieulivraison,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing lieulivraison entity.
     *
     * @Route("/{id}/edit", name="aclunch_admin_lieulivraison_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, LieuLivraison $lieulivraison)
    {
        $editForm = $this->createForm(LieuLivraisonType::class, $lieulivraison);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le lieux de livraison bien été modifié');

            return $this->redirectToRoute('aclunch_admin_lieulivraison_show', ['id' => $lieulivraison->getId()]);
        }

        return [
            'lieulivraison' => $lieulivraison,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a lieulivraison entity.
     *
     * @Route("/{id}", name="aclunch_admin_lieulivraison_delete")
     * @Method("DELETE")
     *
     */
    public function deleteAction(Request $request, LieuLivraison $lieulivraison)
    {
        $form = $this->createDeleteForm($lieulivraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($lieulivraison);
            $em->flush();

            $this->addFlash('success', 'Le lieux de livraison a bien été supprimé');
        }

        return $this->redirectToRoute('aclunch_admin_lieulivraison_index');
    }

    /**
     * Creates a form to delete a lieulivraison entity.
     *
     * @param LieuLivraison $lieulivraison The lieulivraison entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LieuLivraison $lieulivraison)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('aclunch_admin_lieulivraison_delete', array('id' => $lieulivraison->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete', 'attr' => array('class' => 'btn-danger')))
            ->getForm();
    }
}

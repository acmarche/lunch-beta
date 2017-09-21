<?php

namespace AcMarche\LunchBundle\Controller\Admin;

use AcMarche\LunchBundle\Entity\Commerce;
use AcMarche\LunchBundle\Entity\Supplement;
use AcMarche\LunchBundle\Form\SupplementType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Supplement controller.
 *
 * @Route("/admin/supplement")
 * @Security("has_role('ROLE_LUNCH_COMMERCE')")
 */
class SupplementController extends AbstractController
{
    /**
     * Lists all supplement entities.
     *
     * @Route("/", name="aclunch_admin_supplement_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($user->hasRole('ROLE_LUNCH_ADMIN')) {
            $supplements = $em->getRepository(Supplement::class)->findAll();
        } else {
            $supplements = $em->getRepository(Supplement::class)->getOwnedByUser($user);
        }

        return [
            'supplements' => $supplements,
        ];
    }

    /**
     * Creates a new supplement entity.
     *
     * @Route("/new/{commerce}", name="aclunch_admin_supplement_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('addsupplement', commerce)")
     * @Template()
     */
    public function newAction(Request $request, Commerce $commerce)
    {
        $supplement = new Supplement();
        $supplement->setCommerce($commerce);

        $form = $this->createForm(SupplementType::class, $supplement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($supplement);
            $em->flush();

            $this->addFlash('success', "Le supplément a bien été ajouté");

            $commerce = $supplement->getCommerce();

            return $this->redirectToRoute('aclunch_admin_commerce_show', array('id' => $commerce->getId()));
        }

        return [
            'supplement' => $supplement,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a supplement entity.
     *
     * @Route("/{id}", name="aclunch_admin_supplement_show")
     * @Method("GET")
     * @Security("is_granted('show', supplement)")
     * @Template()
     */
    public function showAction(Supplement $supplement)
    {
        $deleteForm = $this->createDeleteForm($supplement);

        return [
            'supplement' => $supplement,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing supplement entity.
     *
     * @Route("/{id}/edit", name="aclunch_admin_supplement_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('edit', supplement)")
     * @Template()
     */
    public function editAction(Request $request, Supplement $supplement)
    {
        $editForm = $this->createForm(SupplementType::class, $supplement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "Le supplément a bien été modifié");

            return $this->redirectToRoute('aclunch_admin_supplement_show', array('id' => $supplement->getId()));
        }

        return [
            'supplement' => $supplement,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a supplement entity.
     *
     * @Route("/{id}", name="aclunch_admin_supplement_delete")
     * @Security("is_granted('delete', supplement)")
     * @Method("DELETE")
     *
     */
    public function deleteAction(Request $request, Supplement $supplement)
    {
        $form = $this->createDeleteForm($supplement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $commerce = $supplement->getCommerce();
            $em->remove($supplement);
            $em->flush();

            return $this->redirectToRoute('aclunch_admin_commerce_show', array('id' => $commerce->getId()));
        }

        return $this->redirectToRoute('aclunch_admin_commerce_index');
    }

    /**
     * Creates a form to delete a supplement entity.
     *
     * @param Supplement $supplement The supplement entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Supplement $supplement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('aclunch_admin_supplement_delete', array('id' => $supplement->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete', 'attr' => array('class' => 'btn-danger')))
            ->getForm();
    }
}

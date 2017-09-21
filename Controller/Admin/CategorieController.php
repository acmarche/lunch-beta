<?php

namespace AcMarche\LunchBundle\Controller\Admin;

use AcMarche\LunchBundle\Entity\Categorie;
use AcMarche\LunchBundle\Form\CategorieType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Categorie controller.
 *
 * @Route("/admin/categorie")
 * @Security("has_role('ROLE_LUNCH_COMMERCE')")
 */
class CategorieController extends AbstractController
{
    /**
     * Lists all categorie entities.
     *
     * @Route("/", name="aclunch_admin_categorie_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();

        if ($user->hasRole('ROLE_LUNCH_ADMIN')) {
     //       $categories = $em->getRepository(Categorie::class)->search([]);
        } else {
    //        $categories = $em->getRepository(Categorie::class)->getOwnedByUser($user);
        }
        $categories = $em->getRepository(Categorie::class)->search([]);

        return [
            'categories' => $categories,
        ];
    }

    /**
     * Creates a new categorie entity.
     *
     * @Route("/new", name="aclunch_admin_categorie_new")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function newAction(Request $request)
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', 'La catégorie a bin été ajoutée');

            return $this->redirectToRoute('aclunch_admin_categorie_index');
        }

        return [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a categorie entity.
     *
     * @Route("/{id}", name="aclunch_admin_categorie_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Categorie $categorie)
    {
        $deleteForm = $this->createDeleteForm($categorie);

        return [
            'categorie' => $categorie,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing categorie entity.
     *
     * @Route("/{id}/edit", name="aclunch_admin_categorie_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function editAction(Request $request, Categorie $categorie)
    {
        $editForm = $this->createForm(CategorieType::class, $categorie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La catégorie a bin été modifiée');

            return $this->redirectToRoute('aclunch_admin_categorie_show', ['id' => $categorie->getId()]);
        }

        return [
            'categorie' => $categorie,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a categorie entity.
     *
     * @Route("/{id}", name="aclunch_admin_categorie_delete")
     * @Method("DELETE")
     *
     */
    public function deleteAction(Request $request, Categorie $categorie)
    {
        $form = $this->createDeleteForm($categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categorie);
            $em->flush();

            $this->addFlash('success', 'La catégorie a bin été supprimée');
        }

        return $this->redirectToRoute('aclunch_admin_categorie_index');
    }

    /**
     * Creates a form to delete a categorie entity.
     *
     * @param Categorie $categorie The categorie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Categorie $categorie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('aclunch_admin_categorie_delete', array('id' => $categorie->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete', 'attr' => array('class' => 'btn-danger')))
            ->getForm();
    }
}

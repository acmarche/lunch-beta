<?php

namespace AcMarche\LunchBundle\Controller\Admin;

use AcMarche\LunchBundle\Entity\Commerce;
use AcMarche\LunchBundle\Entity\Ingredient;
use AcMarche\LunchBundle\Form\IngredientType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ingredient controller.
 *
 * @Route("/admin/ingredient")
 * @Security("has_role('ROLE_LUNCH_COMMERCE')")
 */
class IngredientController extends AbstractController
{
    /**
     * Lists all ingredient entities.
     *
     * @Route("/", name="aclunch_admin_ingredient_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($user->hasRole('ROLE_LUNCH_ADMIN')) {
            $ingredients = $em->getRepository(Ingredient::class)->findAll();
        } else {
            $ingredients = $em->getRepository(Ingredient::class)->getOwnedByUser($user);
        }

        return [
            'ingredients' => $ingredients,
        ];
    }

    /**
     * Creates a new ingredient entity.
     *
     * @Route("/new/{commerce}", name="aclunch_admin_ingredient_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('addingredient', commerce)")
     * @Template()
     */
    public function newAction(Request $request, Commerce $commerce)
    {
        $ingredient = new Ingredient();
        $ingredient->setCommerce($commerce);

        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ingredient);
            $em->flush();

            $this->addFlash('success', "L'ingrédient a bien été ajouté");

            $commerce = $ingredient->getCommerce();


            return $this->redirectToRoute('aclunch_admin_commerce_show', array('id' => $commerce->getId()));
        }

        return [
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a ingredient entity.
     *
     * @Route("/{id}", name="aclunch_admin_ingredient_show")
     * @Method("GET")
     * @Security("is_granted('show', ingredient)")
     * @Template()
     */
    public function showAction(Ingredient $ingredient)
    {
        $deleteForm = $this->createDeleteForm($ingredient);

        return [
            'ingredient' => $ingredient,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing ingredient entity.
     *
     * @Route("/{id}/edit", name="aclunch_admin_ingredient_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('edit', ingredient)")
     * @Template()
     */
    public function editAction(Request $request, Ingredient $ingredient)
    {
        $editForm = $this->createForm(IngredientType::class, $ingredient);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', "L'ingrédient a bien été modifié");

            return $this->redirectToRoute('aclunch_admin_ingredient_show', array('id' => $ingredient->getId()));
        }

        return [
            'ingredient' => $ingredient,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a ingredient entity.
     *
     * @Route("/{id}", name="aclunch_admin_ingredient_delete")
     * @Security("is_granted('delete', ingredient)")
     * @Method("DELETE")
     *@Security("is_granted('delete', ingredient)")
     */
    public function deleteAction(Request $request, Ingredient $ingredient)
    {
        $form = $this->createDeleteForm($ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $commerce = $ingredient->getCommerce();
            $em->remove($ingredient);
            $em->flush();

            return $this->redirectToRoute('aclunch_admin_commerce_show', array('id' => $commerce->getId()));
        }

        return $this->redirectToRoute('aclunch_admin_commerce_index');
    }

    /**
     * Creates a form to delete a ingredient entity.
     *
     * @param Ingredient $ingredient The ingredient entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ingredient $ingredient)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('aclunch_admin_ingredient_delete', array('id' => $ingredient->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete', 'attr' => array('class' => 'btn-danger')))
            ->getForm();
    }
}

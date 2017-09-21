<?php

namespace AcMarche\LunchBundle\Controller\Admin;

use AcMarche\LunchBundle\Entity\Commerce;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommerceInterface;
use AcMarche\LunchBundle\Entity\InterfaceDef\ProduitInterface;
use AcMarche\LunchBundle\Entity\Produit;
use AcMarche\LunchBundle\Event\ProduitEvent;
use AcMarche\LunchBundle\Form\ProduitClassicType;
use AcMarche\LunchBundle\Form\ProduitLunchType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Produit controller.
 *
 * @Route("/admin/produit")
 * @Security("has_role('ROLE_LUNCH_COMMERCE')")
 */
class ProduitController extends AbstractController
{
    /**
     * Lists all produit entities.
     *
     * @Route("/", name="aclunch_admin_produit_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($user->hasRole('ROLE_LUNCH_ADMIN')) {
            $args = ['indisponible' => 2];
            $produits = $em->getRepository(Produit::class)->search($args);
        } else {
            $produits = $em->getRepository(Produit::class)->getOwnedByUser($user);
        }

        return [
            'produits' => $produits,
        ];
    }

    /**
     * Creates a new produit entity.
     *
     * @Route("/new/{commerce}/{food}", name="aclunch_admin_produit_new")
     * @Method({"GET", "POST"})
     * @Security("is_granted('addproduit', commerce)")
     *
     */
    public function newAction(Request $request, Commerce $commerce, $food = false)
    {
        $produit = new Produit();
        $produit->setIsFood($food);
        $produit->setCommerce($commerce);

        $form = $this->createFormProduit($produit, $commerce);
        $form->add('Create', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();

            $dispatcher = new EventDispatcher();
            $dispatcher->dispatch(ProduitEvent::PRODUIT_NEW, new ProduitEvent($produit));

            $this->addFlash('success', 'Le produit a bien été ajouté');
            $commerce = $produit->getCommerce();

            return $this->redirectToRoute('aclunch_admin_commerce_show', array('id' => $commerce->getId()));
        }

        return $this->render(
            '@AcMarcheLunch/Admin/Produit/new.html.twig',
            array(
                'produit' => $produit,
                'form' => $form->createView(),
            )
        );

    }

    /**
     * Finds and displays a produit entity.
     *
     * @Route("/{id}", name="aclunch_admin_produit_show")
     * @Method("GET")
     * @Security("is_granted('show', produit)")
     * @Template()
     */
    public function showAction(Produit $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);

        return [
            'produit' => $produit,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing produit entity.
     *
     * @Route("/{id}/edit", name="aclunch_admin_produit_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('edit', produit)")
     * @Template()
     */
    public function editAction(Request $request, Produit $produit)
    {
        $editForm = $this->createFormProduit($produit, $produit->getCommerce(), false);
        $editForm->add('Update', SubmitType::class);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Le produit a bien été mis à jour');

            return $this->redirectToRoute('aclunch_admin_produit_show', array('id' => $produit->getId()));
        }

        return [
            'produit' => $produit,
            'edit_form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a produit entity.
     *
     * @Route("/{id}", name="aclunch_admin_produit_delete")
     * @Method("DELETE")
     * @Security("is_granted('delete', produit)")
     */
    public function deleteAction(Request $request, Produit $produit)
    {
        $form = $this->createDeleteForm($produit);
        $form->handleRequest($request);
        $commerce = $produit->getCommerce();

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($produit);
            $em->flush();
        }

        return $this->redirectToRoute('aclunch_admin_commerce_show', array('id' => $commerce->getId()));
    }

    private function createFormProduit(ProduitInterface $produit, CommerceInterface $commerce, $requiredImage = true)
    {
        if ($produit->getIsFood()) {
            $produit->setIsFood(true);

            return $form = $this->createForm(
                ProduitLunchType::class,
                $produit,
                [
                    'requiredImage' => $requiredImage,
                    'commerce' => $commerce,
                ]
            );

        } else {

            return $this->createForm(
                ProduitClassicType::class,
                $produit,
                [
                    'requiredImage' => $requiredImage,
                    'commerce' => $commerce,
                ]
            );

        }

    }

    /**
     * Creates a form to delete a produit entity.
     *
     * @param Produit $produit The produit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Produit $produit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('aclunch_admin_produit_delete', array('id' => $produit->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete', 'attr' => array('class' => 'btn-danger')))
            ->getForm();
    }
}

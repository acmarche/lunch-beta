<?php

namespace AcMarche\LunchBundle\Controller;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Service\CommandeUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Commande controller.
 *
 * @Route("/commande")
 *
 */
class CommandeController extends Controller
{
    /**
     * Finds and displays a commande entity.
     *
     * @Route("/{id}", name="aclunch_commande_show")
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
     * Deletes a commande entity.
     *
     * @Route("/{id}", name="aclunch_admin_commande_delete")
     * @Method("DELETE")
     *
     */
    public function deleteAction(Request $request, Commande $commande)
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
    private function createDeleteForm(Commande $commande)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('aclunch_admin_commande_delete', array('id' => $commande->getId())))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete', 'attr' => array('class' => 'btn-danger')))
            ->getForm();
    }
}

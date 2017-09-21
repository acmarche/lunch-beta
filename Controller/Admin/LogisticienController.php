<?php

namespace AcMarche\LunchBundle\Controller\Admin;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Entity\CommandeLunch;
use AcMarche\LunchBundle\Event\CommandeEvent;
use AcMarche\LunchBundle\Form\TraiterCommandeType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;

/**
 * Logisticien controller.
 *
 * @Route("/admin/logisticien")
 * @Security("has_role('ROLE_LUNCH_LOGISTICIEN')")
 */
class LogisticienController extends AbstractController
{
    /**
     * Liste des commandes a traiter
     *
     * @Route("/", name="aclunch_logisticien_commande_index")
     * @Method("GET")
     * @Template()
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $commandes = $em->getRepository(Commande::class)->getCommandeALivrer();
        $commandesLunch = $em->getRepository(CommandeLunch::class)->getCommandeALivrer();

        return [
            'commandes' => $commandes,
            'commandesLunch' => $commandesLunch,
        ];
    }

    /**
     * Finds and displays a commande entity.
     *
     * @Route("/livrer/{id}", name="aclunch_logisticien_commande_livrer")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function livrerAction(Request $request, Commande $commande, EventDispatcher $eventDispatcher)
    {
        if ($commande->getLivre()) {
            throw $this->createAccessDeniedException('La commande a déjà été traitée');
        }

        $form = $this->createForm(TraiterCommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            $event = new CommandeEvent($commande);

            $eventDispatcher->dispatch(CommandeEvent::COMMANDE_LIVRE, $event);

            $this->addFlash('success', 'La commande a bien été traitée');

            return $this->redirectToRoute('aclunch_logisticien_commande_index');
        }

        return [
            'commande' => $commande,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a commande entity.
     *
     * @Route("/livrer/lunch/{id}", name="aclunch_logisticien_commande_lunch_livrer")
     * @Method({"GET","POST"})
     * @Template("@AcMarcheLunch/Admin/Logisticien/livrer.html.twig")
     */
    public function livreLunchAction(
        Request $request,
        CommandeLunch $commande,
        EventDispatcher $eventDispatcher
    ) {
        if ($commande->getLivre()) {
            throw $this->createAccessDeniedException('La commande a déjà été traitée');
        }

        $form = $this->createForm(TraiterCommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commande);
            $em->flush();

            $event = new CommandeEvent($commande);

            $eventDispatcher->dispatch(CommandeEvent::COMMANDE_LIVRE, $event);

            $this->addFlash('success', 'La commande a bien été traitée');

            return $this->redirectToRoute('aclunch_logisticien_commande_index');
        }

        return [
            'commande' => $commande,
            'form' => $form->createView(),
        ];
    }


}

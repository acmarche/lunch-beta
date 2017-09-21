<?php

namespace AcMarche\LunchBundle\Controller;

use AcMarche\LunchBundle\Entity\Commerce;
use AcMarche\LunchBundle\Form\ContactCommerceType;
use AcMarche\LunchBundle\Service\Bottin;
use AcMarche\LunchBundle\Service\Mailer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CommerceController
 * @package AcMarche\LunchBundle\Controller
 * @Route("/commerces")
 */
class CommerceController extends Controller
{
    /**
     * Lists all commerce entities.
     *
     * @Route("/", name="aclunch_commerce_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Bottin $bottin)
    {
        $em = $this->getDoctrine()->getManager();

        $commerces = $em->getRepository(commerce::class)->findAll();
        try {
            $bottin->populateMetas($commerces);
        } catch (\Exception $exception) {

        }

        return [
            'commerces' => $commerces,
        ];
    }

    /**
     * Finds and displays a commerce entity.
     *
     * @Route("/{id}", name="aclunch_commerce_show")
     * @Method({"GET","POST"})
     *
     *
     * @Template()
     */
    public function showAction(Request $request, Commerce $commerce, Bottin $bottin, Mailer $mailer)
    {
        if ($commerce->getIndisponible()) {
            throw $this->createNotFoundException('Le commerce does not exist');
        }

        $error = $fiche = false;
        try {
            $fiche = $bottin->getFiche($commerce->getBottinId());
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        $em = $this->getDoctrine()->getManager();
        $next = $em->getRepository(commerce::class)->getNext($commerce);
        $previous = $em->getRepository(commerce::class)->getPrevious($commerce);

        $form = $this->createForm(ContactCommerceType::class, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $mailer->sendNewContact($form);
            $this->addFlash('success', 'Email envoyé');

            return $this->redirectToRoute('aclunch_commerce_show', ['id' => $commerce->getId()]);
        }

        return [
            'fiche' => $fiche,
            'commerce' => $commerce,
            'next' => $next,
            'previous' => $previous,
            'form' => $form->createView(),
        ];
    }
}

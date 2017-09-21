<?php

namespace AcMarche\LunchBundle\Controller;

use AcMarche\LunchBundle\Entity\Commerce;
use AcMarche\LunchBundle\Entity\Ingredient;
use AcMarche\LunchBundle\Entity\Produit;
use AcMarche\LunchBundle\Form\Search\SearchAdvancedType;
use AcMarche\LunchBundle\Form\Search\SearchInlineType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class SearchController
 * @package AcMarche\LunchBundle\Controller
 * @Route("/search")
 *
 */
class SearchController extends AbstractController
{
    /**
     * Finds and displays a search entity.
     *
     * @Route("/", name="aclunch_search")
     * @Method({"GET","POST"})
     *
     * @Template()
     */
    public function indexAction(Request $request, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $ingredients = $produits = $commerces = [];
        $key = "lunch_search";

        if ($session->has($key))
            $data = unserialize($session->get($key));

        $motclef = $request->get('motclef');

        if ($motclef)
            $data['motclef'] = $motclef;

        $form = $this->createForm(SearchAdvancedType::class, $data, [
            'em' => $em
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->get('raz')->isClicked()) {
                $session->remove($key);
                $this->addFlash('info', 'La recherche a bien été réinitialisée.');
                return $this->redirectToRoute('aclunch_search');
            }

            $dataForm = $form->getData();
            $data['motclef'] = $dataForm['motclef'];
            $data['commerce'] = $dataForm['commerce'];
        }
    //    dump($data);
        if (count($data) > 0) {
            $session->set($key, serialize($data));
            $ingredients = $em->getRepository(Ingredient::class)->search($data);
            $produits = $em->getRepository(Produit::class)->search($data);
            $commerces = $em->getRepository(Commerce::class)->search($data);
        }

        return [
            'form' => $form->createView(),
            'produits' => $produits,
            'commerces' => $commerces,
            'ingredients' => $ingredients
        ];
    }

    /**
     * Displays a form to edit an existing categorie entity.
     *
     * @Route("/edit", name="aclunch_search_edit")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function autocompleteAction(Request $request)
    {
        $user = $this->getUser();
        $editForm = $this->createForm(SearchInlineType::class, $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Modification effectuée');

            return $this->redirectToRoute('aclunch_search_show');
        }

        return [
            'user' => $user,
            'form' => $editForm->createView()
        ];
    }
}

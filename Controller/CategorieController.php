<?php

namespace AcMarche\LunchBundle\Controller;

use AcMarche\LunchBundle\Entity\Categorie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class CategorieController
 * @package AcMarche\LunchBundle\Controller
 * @Route("/categories")
 */
class CategorieController extends Controller
{
    /**
     * Lists all categorie entities.
     *
     * @Route("/", name="aclunch_categorie_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(categorie::class)->findAll();

        return [
            'categories' => $categories,
        ];
    }

    /**
     * Finds and displays a categorie entity.
     *
     * @Route("/{id}", name="aclunch_categorie_show")
     * @Method("GET")
     *
     * @Template()
     */
    public function showAction(Categorie $categorie)
    {
        return [
            'categorie' => $categorie,
        ];
    }

    /**
     * Affiche dans le menu top
     * @param $image
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function menuAction($image)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(categorie::class)->findAll();

        if ($image) {
            return $this->render(
                '@AcMarcheLunch/Categorie/menu-image.html.twig',
                array('categories' => $categories)
            );
        } else {
            return $this->render(
                '@AcMarcheLunch/Categorie/menu.html.twig',
                array('categories' => $categories)
            );
        }
    }
}

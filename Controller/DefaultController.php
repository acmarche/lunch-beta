<?php

namespace AcMarche\LunchBundle\Controller;

use AcMarche\LunchBundle\Entity\Produit;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 * @package AcMarche\LunchBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="aclunch_home")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * Lists all produit entities.
     *
     * @Route("/about", name="aclunch_about")
     * @Method("GET")
     * @Template()
     */
    public function aboutAction()
    {
        return [

        ];
    }

    /**
     * Lists all produit entities.
     *
     * @Route("/contact", name="aclunch_contact")
     * @Method("GET")
     * @Template()
     */
    public function contactAction()
    {
        return [

        ];
    }

}

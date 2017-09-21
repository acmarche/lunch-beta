<?php

namespace AcMarche\LunchBundle\Controller\Admin;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Entity\CommandeLunch;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class DefaultController
 * @package AcMarche\LunchBundle\Controller
 * @Route("/admin")
 * @Security("has_role('ROLE_LUNCH_COMMERCE') or has_role('ROLE_LUNCH_LOGISTICIEN')")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="aclunch_admin_home")
     * @Template()
     */
    public function indexAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        if ($user->hasRole('ROLE_LUNCH_ADMIN')) {
            $commandes = $em->getRepository(Commande::class)->search(['paye' => 2]);
            $commandesLunch = $em->getRepository(CommandeLunch::class)->search(['paye' => 2]);
        } elseif ($user->hasRole('ROLE_LUNCH_COMMERCE')) {
            $commandes = $em->getRepository(Commande::class)->getCommandeAValider($user);
            $commandesLunch = $em->getRepository(CommandeLunch::class)->getCommandeALivrerByCommerce($user);
        } else {
            $commandes = $em->getRepository(Commande::class)->getCommandeALivrer();
            $commandesLunch = $em->getRepository(CommandeLunch::class)->getCommandeALivrer();
        }

        return [
            'commandes' => $commandes,
            'commandesLunch' => $commandesLunch,
        ];

    }

    /**
     * @Route("/doc", name="aclunch_admin_doc")
     * @Template()
     */
    public function docAction()
    {
        return [

        ];
    }
}

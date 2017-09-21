<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/07/17
 * Time: 12:10
 */

namespace AcMarche\LunchBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use AcMarche\LunchBundle\Service\Mailer;
use AcMarche\SecurityBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;


class PanierSubscriber implements EventSubscriberInterface
{
    public function __construct(
        ObjectManager $em,
        Mailer $mailer,
        TokenStorageInterface $tokenStorage,
        Session $session
    ) {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->token = $tokenStorage;
        $this->session = $session;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            PanierEvent::PANIER_INDEX => 'panierIndex',
        ];

    }

    public function panierIndex(PanierEvent $event)
    {
        $from = $this->token->getToken()->getUser();

    }
}
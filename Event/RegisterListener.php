<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 18/08/17
 * Time: 16:37
 */


namespace AcMarche\LunchBundle\Event;

use AcMarche\LunchBundle\Service\Mailer;
use AcMarche\SecurityBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Doctrine\GroupManager;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegisterListener implements EventSubscriberInterface
{
    private $router;
    private $manager;
    private $groupManager;
    private $mailer;

    public function __construct(UrlGeneratorInterface $router, EntityManagerInterface $manager, GroupManager $groupManager, Mailer $mailer)
    {
        $this->router = $router;
        $this->manager = $manager;
        $this->groupManager = $groupManager;
        $this->mailer = $mailer;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => [
                ['onRegistrationSuccess', -10],
            ],
            FOSUserEvents::REGISTRATION_CONFIRMED => 'onRegisterSuccess',
        );
    }

    public function onRegisterSuccess(FormEvent $event)
    {

    }

    public function onRegistrationSuccess(FormEvent $event)
    {
        $form = $event->getForm();
        $user = $form->getData();

        $parent = $this->groupManager->findGroupBy(['name' => 'LUNCH_CLIENT']);
        if ($parent) {
            $user->addGroup($parent);

            $this->manager->persist($user);
            $this->manager->flush();
        }

        $this->sendMail($user);
        //$url = $this->router->generate('homepage');
        //$event->setResponse(new RedirectResponse($url));
    }

    private function sendMail(User $user)
    {
        $body = "Bienvenue, blabla, https://lunch.marche.be";

        $this->mailer->send("lunch@marche.be", $user->getEmail(), "Inscription sur lunch", $body);
    }
}
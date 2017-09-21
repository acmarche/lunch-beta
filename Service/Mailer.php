<?php

namespace AcMarche\LunchBundle\Service;

use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\Templating\EngineInterface;

Class Mailer
{
    private $mailer;
    private $twig;
    private $from;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        //    $this->from = $from;
    }

    public function send($from, $destinataires, $sujet, $body)
    {
        $mail = \Swift_Message::newInstance()
            ->setSubject($sujet)
            ->setFrom($from)
            ->setTo($destinataires);
        $mail->setBody($body);

        $this->mailer->send($mail);
    }

    /**
     * @todo sender user email
     * @param Form $form
     */
    public function sendNewContact(Form $form)
    {
        $from = $this->from;
        $sujet = "Contact sur lunch";
        $destinataires = [];

        $body = $this->twig->render(
            'Emails/contact.html.twig',
            array(
                'data' => $form->getData(),
            )
        );

        try {
            $this->send($from, $destinataires, $sujet, $body);
        } catch (\Swift_SwiftException $e) {

        }
    }

    public function sendCommandePaye(CommandeInterface $commande, $from, $to)
    {
        $sujet = "Nouvelle commande pour ".$commande->getCommerceNom()." de ".$commande->getUser();

        $body = $this->twig->render(
            '@AcMarcheLunch/Email/commande_new.html.twig',
            array(
                'commande' => $commande,
            )
        );

        try {
            $this->send($from, $to, $sujet, $body);
        } catch (\Swift_SwiftException $e) {

        }
    }

    public function sendCommandeLivre(CommandeInterface $commande, $from, $to)
    {
        $sujet = "Livraison de la commande pour ".$commande->getCommerceNom()." de ".$commande->getUser();

        $body = $this->twig->render(
            '@AcMarcheLunch/Email/commande_livre.html.twig',
            array(
                'commande' => $commande,
            )
        );

        try {
            $this->send($from, $to, $sujet, $body);
        } catch (\Swift_SwiftException $e) {

        }
    }

}

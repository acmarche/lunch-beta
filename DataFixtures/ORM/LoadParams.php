<?php

namespace AcMarche\LunchBundle\DataFixtures\ORM;

use AcMarche\LunchBundle\Entity\Params;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;


class LoadParams extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $defaultTva = new Params();
        $defaultTva->setNom('default_tva');
        $defaultTva->setValeur(21);
        $manager->persist($defaultTva);

        $defaultEmail = new Params();
        $defaultEmail->setNom('email_master');
        $defaultEmail->setValeur('lunch@marche.be');
        $manager->persist($defaultEmail);

        $defaultSecret = new Params();
        $defaultSecret->setNom('stripe_secret_key');
        $defaultSecret->setValeur('sk_test_MFL4TARoNv2vdQjnZ99W12Uu');
        $manager->persist($defaultSecret);

        $defaultkey = new Params();
        $defaultkey->setNom('stripe_public_key');
        $defaultkey->setValeur('pk_test_3f6yquEdxKKO1FSTyNw8zFhd');
        $manager->persist($defaultkey);

        $defaultkey = new Params();
        $defaultkey->setNom('sms_login');
        $defaultkey->setValeur('MYACMF');
        $manager->persist($defaultkey);

        $defaultkey = new Params();
        $defaultkey->setNom('sms_mdp');
        $defaultkey->setValeur('Informatique6900');
        $manager->persist($defaultkey);

        $manager->flush();

    }


    public function getOrder()
    {
        return 6;
    }

}

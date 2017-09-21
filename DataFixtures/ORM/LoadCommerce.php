<?php

namespace AcMarche\LunchBundle\DataFixtures\ORM;

use AcMarche\LunchBundle\Entity\Commerce;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;


class LoadCommerce extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $porte = new Commerce();
        $porte->setBottinId(178);
        $porte->setCreated(new \DateTime());
        $porte->setUpdated(new \DateTime());
        $porte->setNom("Bonne porte");
        $porte->setUser("porte");
        $porte->setNumeroTva("BE 02489.140.983");
        $porte->setIban("BE87 1030 3882 2094");
        $porte->setImageName("porte.jpg");
        $manager->persist($porte);
        $this->addReference("porte", $porte);

        $delice = new Commerce();
        $delice->setBottinId(892);
        $delice->setCreated(new \DateTime());
        $delice->setUpdated(new \DateTime());
        $delice->setNom("Delice");
        $delice->setNumeroTva("BE 02489.140.983");
        $delice->setIban("BE87 1030 3882 2094");
        $delice->setImageName("delice.jpg");
        $manager->persist($delice);
        $this->addReference("delice", $delice);

        $babe = new Commerce();
        $babe->setBottinId(894);
        $babe->setCreated(new \DateTime());
        $babe->setUpdated(new \DateTime());
        $babe->setNom("Friterie Babe");
        $babe->setUser("babe");
        $babe->setNumeroTva("BE 02489.140.983");
        $babe->setIban("BE87 1030 3882 2094");
        $babe->setImageName("babe.png");
        $manager->persist($babe);
        $this->addReference("babe", $babe);

        $dagobert = new Commerce();
        $dagobert->setBottinId(905);
        $dagobert->setCreated(new \DateTime());
        $dagobert->setUpdated(new \DateTime());
        $dagobert->setNom("Le Dagobert");
        $dagobert->setNumeroTva("BE 02489.140.983");
        $dagobert->setIban("BE87 1030 3882 2094");
        $dagobert->setImageName("dagobert.jpg");
        $manager->persist($dagobert);
        $this->addReference("dagobert", $dagobert);

        $midi = new Commerce();
        $midi->setBottinId(906);
        $midi->setCreated(new \DateTime());
        $midi->setUpdated(new \DateTime());
        $midi->setNom("Le Midi 30");
        $midi->setUser("midi");
        $midi->setNumeroTva("BE 02489.140.983");
        $midi->setIban("BE87 1030 3882 2094");
        $midi->setImageName("midi.jpg");
        $manager->persist($midi);
        $this->addReference("midi", $midi);

        $vin = new Commerce();
        $vin->setBottinId(906);
        $vin->setCreated(new \DateTime());
        $vin->setUpdated(new \DateTime());
        $vin->setNom("La cave des Sommeliers");
        $vin->setNumeroTva("BE 02489.140.983");
        $vin->setIban("BE87 1030 3882 2094");
        $vin->setImageName("vin.jpg");
        $manager->persist($vin);
        $this->addReference("vin", $vin);

        $lobet = new Commerce();
        $lobet->setBottinId(906);
        $lobet->setCreated(new \DateTime());
        $lobet->setUpdated(new \DateTime());
        $lobet->setNom("Outillages Lobet");
        $lobet->setUser("lobet");
        $lobet->setNumeroTva("BE 02489.140.983");
        $lobet->setIban("BE87 1030 3882 2094");
        $lobet->setImageName("lobet.jpg");
        $manager->persist($lobet);
        $this->addReference("lobet", $lobet);

        $malice = new Commerce();
        $malice->setBottinId(906);
        $malice->setCreated(new \DateTime());
        $malice->setUpdated(new \DateTime());
        $malice->setNom("Boite à Malice");
        $malice->setNumeroTva("BE 02489.140.983");
        $malice->setIban("BE87 1030 3882 2094");
        $malice->setImageName("malice.jpg");
        $manager->persist($malice);
        $this->addReference("malice", $malice);

        $memo = new Commerce();
        $memo->setBottinId(906);
        $memo->setCreated(new \DateTime());
        $memo->setUpdated(new \DateTime());
        $memo->setNom("Bureau memo");
        $memo->setUser("memo");
        $memo->setNumeroTva("BE 02489.140.983");
        $memo->setIban("BE87 1030 3882 2094");
        $memo->setImageName("memo.jpg");
        $manager->persist($memo);
        $this->addReference("memo", $memo);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }

}

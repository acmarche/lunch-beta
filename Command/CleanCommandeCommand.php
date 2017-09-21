<?php

namespace AcMarche\LunchBundle\Command;

use AcMarche\LunchBundle\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanCommandeCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('aclunch:cleancommande')
            ->setDescription('Supprime les commandes qui n\'ont aucun produits');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');
        $commandeUtil = $container->get('ac_marche_lunch.commandeUtil');

        $em = $doctrine->getManager();
        $commandes = $em->getRepository(Commande::class)->getCommandeObsolete(new \DateTime());
        $count = count($commandes);

        $commandeUtil->cleanCommandeWithoutProduit($commandes);

        $em->flush();
        $output->writeln($count . ' commandes effacees');
    }

}

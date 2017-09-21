<?php

namespace AcMarche\LunchBundle\Command;

use AcMarche\LunchBundle\Entity\Categorie;
use AcMarche\LunchBundle\Entity\Commerce;
use AcMarche\LunchBundle\Entity\Produit;
use AcMarche\SecurityBundle\Entity\Group;
use AcMarche\SecurityBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitUserCommand extends ContainerAwareCommand
{
    private $output;
    private $em;
    private $userManager;

    protected function configure()
    {
        $this
            ->setName('aclunch:inituser')
            ->setDescription('Encode les groupes et l\'utilisateur admin')
            ->addArgument('test', InputArgument::OPTIONAL, 'For phpunit');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $doctrine = $container->get('doctrine');

        $this->em = $doctrine->getManager();
        $this->userManager = $container->get('fos_user.user_manager');
        $this->output = $output;

        $this->createDefaultAccount();

        $test = $input->getArgument('test');
        if ($test) {
            $this->createForTest();
        }
    }

    protected function createDefaultAccount()
    {
        $groupAdmin = $this->em->getRepository(Group::class)->findOneBy(['name' => 'LUNCH_ADMIN']);
        $groupCommerce = $this->em->getRepository(Group::class)->findOneBy(['name' => 'LUNCH_COMMERCE']);
        $groupClient = $this->em->getRepository(Group::class)->findOneBy(['name' => 'LUNCH_CLIENT']);
        $groupLogisticien = $this->em->getRepository(Group::class)->findOneBy(['name' => 'LUNCH_LOGISTICIEN']);
        $adminUser = $this->em->getRepository(User::class)->findOneBy(['username' => 'admin']);
        $commerceUser = $this->em->getRepository(User::class)->findOneBy(['username' => 'porte']);
        $logisticienUser = $this->em->getRepository(User::class)->findOneBy(['username' => 'logisticien']);
        $clientUser = $this->em->getRepository(User::class)->findOneBy(['username' => 'homer']);
        $admin_acmarche = $this->em->getRepository(Group::class)->findOneBy(['name' => 'ACMARCHE_ADMIN']);

        if (!$groupAdmin) {
            $groupAdmin = new Group("LUNCH_ADMIN");
            $groupAdmin->setTitle('Administrateur de l\'applis lunch');
            $groupAdmin->setDescription('Dispose de tous les droits');
            $groupAdmin->addRole("ROLE_LUNCH_ADMIN");
            $groupAdmin->addRole("ROLE_LUNCH");
            $groupAdmin->addRole("ROLE_LUNCH_COMMERCE");
            $groupAdmin->addRole("ROLE_LUNCH_LOGISTICIEN");
            $groupAdmin->addRole("ROLE_LUNCH_CLIENT");
            $this->em->persist($groupAdmin);
            $this->em->flush();
            $this->output->writeln('Groupe LUNCH_ADMIN créé');
        }

        if (!$groupCommerce) {
            $groupCommerce = new Group("LUNCH_COMMERCE");
            $groupCommerce->setTitle('Accès commerçant lunch');
            $groupCommerce->setDescription('Dispose de tous les droits sur son commerce');
            $groupCommerce->addRole("ROLE_LUNCH_COMMERCE");
            $groupCommerce->addRole("ROLE_LUNCH_CLIENT");
            $groupCommerce->addRole("ROLE_LUNCH");
            $this->em->persist($groupCommerce);
            $this->em->flush();
            $this->output->writeln('Groupe LUNCH_COMMERCE créé');
        }

        if (!$groupClient) {
            $groupClient = new Group("LUNCH_CLIENT");
            $groupClient->setTitle('Accès commerçant lunch');
            $groupClient->setDescription('Peut commander des paniers');
            $groupClient->addRole("ROLE_LUNCH_CLIENT");
            $groupClient->addRole("ROLE_LUNCH");
            $this->em->persist($groupClient);
            $this->em->flush();
            $this->output->writeln('Groupe LUNCH_CLIENT créé');
        }

        if (!$groupLogisticien) {
            $groupLogisticien = new Group("LUNCH_LOGISTICIEN");
            $groupLogisticien->setTitle('Accès logisticien lunch');
            $groupLogisticien->setDescription('Consulte les commandes finalisées');
            $groupLogisticien->addRole("ROLE_LUNCH_LOGISTICIEN");
            $groupLogisticien->addRole("ROLE_LUNCH_CLIENT");
            $groupLogisticien->addRole("ROLE_LUNCH");
            $this->em->persist($groupLogisticien);
            $this->em->flush();
            $this->output->writeln('Groupe LUNCH_LOGISTICIEN créé');
        }

        if (!$adminUser) {
            $adminUser = $this->userManager->createUser();
            $adminUser->setUsername('admin');
            $adminUser->setNom('Admin');
            $adminUser->setPrenom('admin');
            $adminUser->setPlainPassword('admin');
            $adminUser->setEnabled(1);
            $adminUser->setEmail("admin@domain.be");
            $adminUser->setSmsNumero('32476662615');
            $this->em->persist($adminUser);
            $this->output->writeln("L'utilisateur admin a bien été créé");
        }

        if (!$commerceUser) {
            $commerceUser = $this->userManager->createUser();
            $commerceUser->setUsername('porte');
            $commerceUser->setNom('Bonne');
            $commerceUser->setPrenom('Porte');
            $commerceUser->setPlainPassword('porte');
            $commerceUser->setEnabled(1);
            $commerceUser->setEmail("porte@domain.be");
            $commerceUser->setSmsNumero('32476662615');
            $this->em->persist($commerceUser);
            $this->output->writeln("L'utilisateur porte a bien été créé");
        }

        if (!$logisticienUser) {
            $logisticienUser = $this->userManager->createUser();
            $logisticienUser->setUsername('logisticien');
            $logisticienUser->setNom('Criquielion');
            $logisticienUser->setPrenom('Claude');
            $logisticienUser->setPlainPassword('logisticien');
            $logisticienUser->setEnabled(1);
            $logisticienUser->setEmail("logisticien@domain.be");
            $logisticienUser->setSmsNumero('32476662615');
            $this->em->persist($logisticienUser);
            $this->output->writeln("L'utilisateur logisticien a bien été créé");
        }

        if (!$clientUser) {
            $clientUser = $this->userManager->createUser();
            $clientUser->setUsername('homer');
            $clientUser->setNom('Simpson');
            $clientUser->setPrenom('Homer');
            $clientUser->setPlainPassword('homer');
            $clientUser->setEnabled(1);
            $clientUser->setEmail("homer@domain.be");
            $clientUser->setSmsNumero('32476662615');
            $this->em->persist($clientUser);
            $this->output->writeln("L'utilisateur homer a bien été créé");
        }

        if (!$commerceUser->hasGroup($groupCommerce)) {
            $commerceUser->addGroup($groupCommerce);
            $this->em->persist($commerceUser);
            $this->output->writeln("L'utilisateur porte a été ajouté dans le groupe commerce");
        }

        if (!$logisticienUser->hasGroup($groupLogisticien)) {
            $logisticienUser->addGroup($groupLogisticien);
            $this->em->persist($logisticienUser);
            $this->output->writeln("L'utilisateur logisticien a été ajouté dans le groupe logisticien");
        }

        if (!$clientUser->hasGroup($groupClient)) {
            $clientUser->addGroup($groupClient);
            $this->em->persist($clientUser);
            $this->output->writeln("L'utilisateur homer a été ajouté dans le groupe client");
        }

        if (!$adminUser->hasGroup($groupAdmin)) {
            $adminUser->addGroup($groupAdmin);
            $this->em->persist($adminUser);
            $this->output->writeln("L'utilisateur admin a été ajouté dans le groupe admin");
        }

        if ($admin_acmarche) {
            if (!$adminUser->hasGroup($admin_acmarche)) {
                $adminUser->addGroup($admin_acmarche);
                $this->em->persist($admin_acmarche);
                $this->output->writeln("L'utilisateur admin a été ajouté dans le groupe acadmin");
            }
        } else {
            $this->output->writeln("Créé le groupe admin_acmarche avec la commande acsecurity:initdata ");
        }

        $this->em->flush();
    }

    protected function createForTest()
    {
        $commerceLobet = $this->em->getRepository(User::class)->findOneBy(['username' => 'lobet']);
        $commerceBabe = $this->em->getRepository(User::class)->findOneBy(['username' => 'babe']);
        $commerceMemo = $this->em->getRepository(User::class)->findOneBy(['username' => 'memo']);
        $clientZora = $this->em->getRepository(User::class)->findOneBy(['username' => 'zora']);
        $groupCommerce = $this->em->getRepository(Group::class)->findOneBy(['name' => 'LUNCH_COMMERCE']);
        $groupClient = $this->em->getRepository(Group::class)->findOneBy(['name' => 'LUNCH_CLIENT']);
        $categorie = $this->em->getRepository(Categorie::class)->findOneBy(['nom' => 'Lunchs classiques']);
        $babe = $this->em->getRepository(Commerce::class)->findOneBy(['nom' => 'Friterie Babe']);

        if (!$commerceBabe) {
            $commerceBabe = $this->userManager->createUser();
            $commerceBabe->setUsername('babe');
            $commerceBabe->setNom('Babe');
            $commerceBabe->setPrenom('Frite');
            $commerceBabe->setPlainPassword('babe');
            $commerceBabe->setEnabled(1);
            $commerceBabe->setEmail("babe@domain.be");
            $this->em->persist($commerceBabe);
            $this->output->writeln("L'utilisateur babe a bien été créé");
        }

        if (!$commerceLobet) {
            $commerceLobet = $this->userManager->createUser();
            $commerceLobet->setUsername('lobet');
            $commerceLobet->setNom('Lobet');
            $commerceLobet->setPrenom('Outils');
            $commerceLobet->setPlainPassword('lobet');
            $commerceLobet->setEnabled(1);
            $commerceLobet->setEmail("lobet@domain.be");
            $this->em->persist($commerceLobet);
            $this->output->writeln("L'utilisateur lobet a bien été créé");
        }

        if (!$commerceMemo) {
            $commerceMemo = $this->userManager->createUser();
            $commerceMemo->setUsername('memo');
            $commerceMemo->setNom('Buerau');
            $commerceMemo->setPrenom('Memo');
            $commerceMemo->setPlainPassword('memo');
            $commerceMemo->setEnabled(1);
            $commerceMemo->setEmail("memo@domain.be");
            $this->em->persist($commerceMemo);
            $this->output->writeln("L'utilisateur memo a bien été créé");
        }

        if (!$clientZora) {
            $clientZora = $this->userManager->createUser();
            $clientZora->setUsername('zora');
            $clientZora->setNom('Simpson');
            $clientZora->setPrenom('Zora');
            $clientZora->setPlainPassword('zora');
            $clientZora->setEnabled(1);
            $clientZora->setEmail("zora@domain.be");
            $clientZora->setSmsNumero('32476662615');
            $this->em->persist($clientZora);
            $this->output->writeln("L'utilisateur zora a bien été créé");
        }

        if (!$commerceBabe->hasGroup($groupCommerce)) {
            $commerceBabe->addGroup($groupCommerce);
            $this->em->persist($commerceBabe);
            $this->output->writeln("L'utilisateur babe a été ajouté dans le groupe commerce");
        }

         if (!$commerceMemo->hasGroup($groupCommerce)) {
            $commerceMemo->addGroup($groupCommerce);
            $this->em->persist($commerceMemo);
            $this->output->writeln("L'utilisateur memo a été ajouté dans le groupe commerce");
        }

        if (!$commerceLobet->hasGroup($groupCommerce)) {
            $commerceLobet->addGroup($groupCommerce);
            $this->em->persist($commerceLobet);
            $this->output->writeln("L'utilisateur midi a été ajouté dans le groupe commerce");
        }

        if (!$clientZora->hasGroup($groupClient)) {
            $clientZora->addGroup($groupClient);
            $this->em->persist($clientZora);
            $this->output->writeln("L'utilisateur zora a été ajouté dans le groupe client");
        }

        $image = rand(1, 31);

        $produit = new Produit();
        $produit->setCategorie($categorie);
        $produit->setNom("Sans stock");
        $produit->setPrixHtva(1);
        $produit->setQuantiteStock(0);
        $produit->setImageName($image.'.jpg');
        $produit->setCommerce($babe);
        $this->em->persist($produit);

        $produit = new Produit();
        $produit->setCategorie($categorie);
        $produit->setNom("Indisponible");
        $produit->setPrixHtva(1);
        $produit->setIndisponible(true);
        $produit->setImageName($image.'.jpg');
        $produit->setCommerce($babe);
        $this->em->persist($produit);

        $this->em->flush();

    }
}
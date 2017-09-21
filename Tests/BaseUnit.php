<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 2/09/17
 * Time: 17:17
 */

namespace AcMarche\LunchBundle\Tests;

use AcMarche\LunchBundle\Entity\Categorie;
use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Entity\CommandeLunch;
use AcMarche\LunchBundle\Entity\CommandeProduit;
use AcMarche\LunchBundle\Entity\CommandeProduitLunch;
use AcMarche\LunchBundle\Entity\Commerce;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeInterface;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeProduitInterface;
use AcMarche\LunchBundle\Entity\Produit;
use AcMarche\SecurityBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;

class BaseUnit extends WebTestCase
{
    protected $admin;
    protected $logisticien;
    protected $commercePorte;
    protected $commerceLobet;
    protected $commerceMemo;
    protected $clientHomer;
    protected $commerceBabe;
    protected $clientZora;
    protected $anonyme;
    protected $container;
    protected $em;

    public function __construct()
    {
        $this->admin = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW' => 'admin',
            ]
        );

        $this->logisticien = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'logisticien',
                'PHP_AUTH_PW' => 'logisticien',
            ]
        );

        $this->commercePorte = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'porte',
                'PHP_AUTH_PW' => 'porte',
            ]
        );

        $this->commerceBabe = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'babe',
                'PHP_AUTH_PW' => 'babe',
            ]
        );

        $this->commerceLobet = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'lobet',
                'PHP_AUTH_PW' => 'lobet',
            ]
        );

        $this->commerceMemo = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'memo',
                'PHP_AUTH_PW' => 'memo',
            ]
        );

        $this->clientHomer = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'homer',
                'PHP_AUTH_PW' => 'homer',
            ]
        );

        $this->clientZora = static::createClient(
            [],
            [
                'PHP_AUTH_USER' => 'zora',
                'PHP_AUTH_PW' => 'zora',
            ]
        );

        $this->anonyme = static::createClient();

        $this->container = $this->anonyme->getContainer();
        $this->em = $this->container->get('doctrine')->getManager();

        parent::__construct();
    }

    /**
     * @param $url
     * @param Client $user
     * @param $codeAttendu
     * @return \Symfony\Component\DomCrawler\Crawler
     */
    public function executeUrl($url, Client $user, $codeAttendu)
    {
        $crawler = $user->request('GET', $url);
        $code = $user->getResponse()->getStatusCode();

        if ($code == 404) {
            // var_dump($url);
        }

        $this->assertEquals($codeAttendu, $code);

        return $crawler;
    }

    /**
     * @param $object
     * @return \Symfony\Component\Security\Csrf\CsrfToken
     */
    public function generateToken($object)
    {
        $csrf = $this->container->get('security.csrf.token_manager');

        return $csrf->refreshToken($object);
    }

    /**
     * @param $args
     * @return bool|null|Commerce
     */
    protected function getCommerce($args)
    {
        $commerce = $this->em->getRepository(Commerce::class)->findOneBy(
            $args
        );

        if (!$commerce) {
            $this->assertEquals(0, 'commerce non trouve');

            return false;
        }

        return $commerce;
    }

    /**
     * @param $username
     * @return CommandeInterface|bool
     */
    protected function getCommande($args, $isFood = false)
    {
        if ($isFood) {
            $commande = $this->em->getRepository(CommandeLunch::class)->findOneBy(
                $args
            );
        } else {
            $commande = $this->em->getRepository(Commande::class)->findOneBy(
                $args
            );
        }

        if (!$commande) {
            $this->assertEquals(0, 'commande non trouvee');

            return false;
        }

        return $commande;
    }

    /**
     * @param $args
     * @return bool|null|Produit[]
     */
    protected function getProduits($args)
    {
        $produits = $this->em->getRepository(Produit::class)->findBy(
            $args
        );

        if (count($produits) < 1) {
            $this->assertEquals(0, 'produits non trouve');

            return false;
        }

        return $produits;
    }

    /**
     * @param $args
     * @return bool|null|Categorie
     */
    protected function getCategorie($args)
    {
        $categorie = $this->em->getRepository(Categorie::class)->findOneBy(
            $args
        );

        if (!$categorie) {
            $this->assertEquals(0, 'categorie non trouve');

            return false;
        }

        return $categorie;
    }

    /**
     * @param $args
     * @return bool|null|CommandeProduitInterface
     */
    protected function getCommandeProduit($args, $isFood = false)
    {
        if ($isFood) {
            $commandeProduit = $this->em->getRepository(CommandeProduitLunch::class)->findOneBy(
                $args
            );
        } else {
            $commandeProduit = $this->em->getRepository(CommandeProduit::class)->findOneBy(
                $args
            );
        }

        if (!$commandeProduit) {
            $this->assertEquals(0, 'commandeProduit non trouve');

            return false;
        }

        return $commandeProduit;
    }

    /**
     * @param $args
     * @return bool|null|User
     */
    protected function getUser($args)
    {
        $user = $this->em->getRepository(User::class)->findOneBy(
            $args
        );

        if (!$user) {
            $this->assertEquals(0, 'user non trouve');

            return false;
        }

        return $user;
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }

}
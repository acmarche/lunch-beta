<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 1/09/17
 * Time: 14:53
 */

namespace AcMarche\LunchBundle\Service;

use AcMarche\LunchBundle\Entity\Client;
use AcMarche\LunchBundle\Entity\Commande;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommandeInterface;
use AcMarche\LunchBundle\Entity\StripeCharge;
use Doctrine\Common\Persistence\ObjectManager;

class StripeUtil
{
    private $secretKey;
    private $manager;
    private $produitUtil;
    private $paramsUtil;

    /**
     * StripeUtil constructor.
     */
    public function __construct(ParamsUtil $paramsUtil, ObjectManager $manager, ProduitUtil $produitUtil)
    {
        $this->paramsUtil = $paramsUtil;
        $this->secretKey = $paramsUtil->getStripeSecretKey();
        $this->manager = $manager;
        $this->produitUtil = $produitUtil;
    }

    /**
     * Frais transaction
     * 1,4 %  + 0,25 €
     * https://stripe.com/be/pricing
     * @param $montant
     * @return float
     */
    public function calculFraisTransaction($montant, $taux = 1.24, $charge = 0.25)
    {
        $frais = $this->produitUtil->calculTva($montant, $taux);

        return $frais + $charge;
    }

    /**
     * @param $token
     * @param CommandeInterface $commande
     * @return null|string|\Stripe\Charge
     */
    public function chargeCard($token, CommandeInterface $commande)
    {
        //set  your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey($this->secretKey);
        $couts = $commande->getCouts();

        if (!isset($couts['total'])) {
            return 'Coût non trouvé';
        }

        if ($couts['total'] < 1.00) {
            return 'Montant trop petit';
        }

        $description = "Commande num ".$commande->getId()." chez ".$commande->getCommerce()->getNom();

        $amount = $this->produitUtil->getInCent($couts['total']);

        // Charge the user's card:
        $charge = null;

        try {
            $charge = \Stripe\Charge::create(
                array(
                    "amount" => $amount,
                    "currency" => "eur",
                    "description" => $description,
                    "source" => $token,
                )
            );
        } catch (\Stripe\Error\Base $e) {
            // Code to do something with the $e exception object when an error occurs

            return $e->getMessage();
        } catch (\Exception $e) {
            // Catch any other non-Stripe exceptions

            return $e->getMessage();
        }

        return $charge;
    }

    /**
     * @param \Stripe\Charge $charge
     * @param Commande $commande
     * @return StripeCharge
     */
    public function createEntityCharge(\Stripe\Charge $charge, Commande $commande, bool $isFood)
    {
        $stripeCharge = new StripeCharge();
        $stripeCharge->setIdCharge($charge->id);
        $stripeCharge->setDescription($charge->description);
        $stripeCharge->setAmount($charge->amount);
        $stripeCharge->setAmountRefunded($charge->amount_refunded);
        $stripeCharge->setBalanceTransaction($charge->balance_transaction);
        $stripeCharge->setCreatedStripe($charge->created);
        $stripeCharge->setCurrency($charge->currency);
        $stripeCharge->setFailureCode($charge->failure_code);
        $stripeCharge->setFailureMessage($charge->failure_message);
        $stripeCharge->setObject($charge->object);
        $stripeCharge->setRefunded($charge->refunded);
        $stripeCharge->setStatus($charge->status);
        $stripeCharge->setPaid($charge->paid);
        if ($isFood) {
            $stripeCharge->setCommandeLunch($commande);
        } else {
            $stripeCharge->setCommande($commande);
        }
        $stripeCharge->setUser($commande->getUser());

        $this->manager->persist($stripeCharge);

        return $stripeCharge;
    }

    public function chargeAndCreateClient($token, $montant)
    {
        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        \Stripe\Stripe::setApiKey($this->secret_key);

        // Create a Customer:
        $customer = \Stripe\Customer::create(
            array(
                "email" => "paying.user@example.com",
                "source" => $token,
            )
        );

        // Charge the Customer instead of the card:
        $charge = \Stripe\Charge::create(
            array(
                "amount" => $montant,
                "currency" => "eur",
                "customer" => $customer->id,
            )
        );

        // YOUR CODE: Save the customer ID and other info in a database for later.
        $client = new Client();
        $client->setSource($token);
        $client->setEmail("paying.user@example.com");
        $client->setStripeId($customer->id);
        $client->setUser($this->getUser()->getUsername());

        $this->manager->persist($client);
        // $em->flush();
    }

    protected function chargeClient($token, $montant, $customerId)
    {
        // YOUR CODE (LATER): When it's time to charge the customer again, retrieve the customer ID.
        $charge = \Stripe\Charge::create(
            array(
                "amount" => 1500, // $15.00 this time
                "currency" => "eur",
                "customer" => $customerId,
            )
        );
    }

}
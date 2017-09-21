<?php

namespace AcMarche\LunchBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * StripeCharge
 *
 * @ORM\Table(name="stripe_charge")
 * @ORM\Entity(repositoryClass="AcMarche\LunchBundle\Repository\StripeChargeRepository")
 */
class StripeCharge
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $id_charge;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $object;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $amount;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $amount_refunded;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $balance_transaction;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $currency;

    /**
     * @var string
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $created_stripe;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $description;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $failure_code;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $failure_message;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $status;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $paid = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $refunded = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $user;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(name="updated", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated;

    /**
     * @ORM\OneToOne(targetEntity="AcMarche\LunchBundle\Entity\Commande", inversedBy="stripe_charge")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    protected $commande;

    /**
     * @ORM\OneToOne(targetEntity="CommandeLunch", inversedBy="stripe_charge")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    protected $commande_lunch;

    function __toString()
    {
        return $this->id_charge;
    }

    /**
     * STOP
     */


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idCharge
     *
     * @param string $idCharge
     *
     * @return StripeCharge
     */
    public function setIdCharge($idCharge)
    {
        $this->id_charge = $idCharge;

        return $this;
    }

    /**
     * Get idCharge
     *
     * @return string
     */
    public function getIdCharge()
    {
        return $this->id_charge;
    }

    /**
     * Set object
     *
     * @param string $object
     *
     * @return StripeCharge
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return StripeCharge
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set amountRefunded
     *
     * @param integer $amountRefunded
     *
     * @return StripeCharge
     */
    public function setAmountRefunded($amountRefunded)
    {
        $this->amount_refunded = $amountRefunded;

        return $this;
    }

    /**
     * Get amountRefunded
     *
     * @return integer
     */
    public function getAmountRefunded()
    {
        return $this->amount_refunded;
    }

    /**
     * Set balanceTransaction
     *
     * @param string $balanceTransaction
     *
     * @return StripeCharge
     */
    public function setBalanceTransaction($balanceTransaction)
    {
        $this->balance_transaction = $balanceTransaction;

        return $this;
    }

    /**
     * Get balanceTransaction
     *
     * @return string
     */
    public function getBalanceTransaction()
    {
        return $this->balance_transaction;
    }

    /**
     * Set currency
     *
     * @param string $currency
     *
     * @return StripeCharge
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set createdStripe
     *
     * @param integer $createdStripe
     *
     * @return StripeCharge
     */
    public function setCreatedStripe($createdStripe)
    {
        $this->created_stripe = $createdStripe;

        return $this;
    }

    /**
     * Get createdStripe
     *
     * @return integer
     */
    public function getCreatedStripe()
    {
        return $this->created_stripe;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return StripeCharge
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set failureCode
     *
     * @param string $failureCode
     *
     * @return StripeCharge
     */
    public function setFailureCode($failureCode)
    {
        $this->failure_code = $failureCode;

        return $this;
    }

    /**
     * Get failureCode
     *
     * @return string
     */
    public function getFailureCode()
    {
        return $this->failure_code;
    }

    /**
     * Set failureMessage
     *
     * @param string $failureMessage
     *
     * @return StripeCharge
     */
    public function setFailureMessage($failureMessage)
    {
        $this->failure_message = $failureMessage;

        return $this;
    }

    /**
     * Get failureMessage
     *
     * @return string
     */
    public function getFailureMessage()
    {
        return $this->failure_message;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return StripeCharge
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set paid
     *
     * @param boolean $paid
     *
     * @return StripeCharge
     */
    public function setPaid($paid)
    {
        $this->paid = $paid;

        return $this;
    }

    /**
     * Get paid
     *
     * @return boolean
     */
    public function getPaid()
    {
        return $this->paid;
    }

    /**
     * Set refunded
     *
     * @param boolean $refunded
     *
     * @return StripeCharge
     */
    public function setRefunded($refunded)
    {
        $this->refunded = $refunded;

        return $this;
    }

    /**
     * Get refunded
     *
     * @return boolean
     */
    public function getRefunded()
    {
        return $this->refunded;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return StripeCharge
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return StripeCharge
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return StripeCharge
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set commande
     *
     * @param \AcMarche\LunchBundle\Entity\Commande $commande
     *
     * @return StripeCharge
     */
    public function setCommande(\AcMarche\LunchBundle\Entity\Commande $commande)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \AcMarche\LunchBundle\Entity\Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * Set commandeLunch
     *
     * @param \AcMarche\LunchBundle\Entity\CommandeLunch $commandeLunch
     *
     * @return StripeCharge
     */
    public function setCommandeLunch(\AcMarche\LunchBundle\Entity\CommandeLunch $commandeLunch)
    {
        $this->commande_lunch = $commandeLunch;

        return $this;
    }

    /**
     * Get commandeLunch
     *
     * @return \AcMarche\LunchBundle\Entity\CommandeLunch
     */
    public function getCommandeLunch()
    {
        return $this->commande_lunch;
    }
}

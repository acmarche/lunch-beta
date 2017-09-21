<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 16/09/17
 * Time: 15:25
 */

namespace AcMarche\LunchBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Class BaseCommande
 * @package AcMarche\LunchBundle\Entity
 * ORM\Entity()//pour generation setter/getter
 */
abstract class BaseCommande
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
     * @ORM\Column(type="string", length=255)
     */
    protected $user;

    /**
     * Pour sauvegarde
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $commerce_nom;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $valide = 0;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $cloture = 0;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $paye = 0;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $livre = 0;

    /**
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $date_livraison;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $livraison_remarque;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $valide_remarque;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    protected $updated;

    /**
     * Pas stocke en bd
     * Voir CommandeUtil
     * @var []
     */
    protected $couts;

    /**
     * @return mixed
     */
    public function getCouts()
    {
        return $this->couts;
    }

    /**
     * @param mixed $couts
     */
    public function setCouts($couts)
    {
        $this->couts = $couts;
    }

    public function __toString()
    {
        return "Commande ".$this->getId();
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
     * Set user
     *
     * @param string $user
     *
     * @return BaseCommande
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
     * Set commerceNom
     *
     * @param string $commerceNom
     *
     * @return BaseCommande
     */
    public function setCommerceNom($commerceNom)
    {
        $this->commerce_nom = $commerceNom;

        return $this;
    }

    /**
     * Get commerceNom
     *
     * @return string
     */
    public function getCommerceNom()
    {
        return $this->commerce_nom;
    }

    /**
     * Set valide
     *
     * @param boolean $valide
     *
     * @return BaseCommande
     */
    public function setValide($valide)
    {
        $this->valide = $valide;

        return $this;
    }

    /**
     * Get valide
     *
     * @return boolean
     */
    public function getValide()
    {
        return $this->valide;
    }

    /**
     * Set cloture
     *
     * @param boolean $cloture
     *
     * @return BaseCommande
     */
    public function setCloture($cloture)
    {
        $this->cloture = $cloture;

        return $this;
    }

    /**
     * Get cloture
     *
     * @return boolean
     */
    public function getCloture()
    {
        return $this->cloture;
    }

    /**
     * Set paye
     *
     * @param boolean $paye
     *
     * @return BaseCommande
     */
    public function setPaye($paye)
    {
        $this->paye = $paye;

        return $this;
    }

    /**
     * Get paye
     *
     * @return boolean
     */
    public function getPaye()
    {
        return $this->paye;
    }

    /**
     * Set livre
     *
     * @param boolean $livre
     *
     * @return BaseCommande
     */
    public function setLivre($livre)
    {
        $this->livre = $livre;

        return $this;
    }

    /**
     * Get livre
     *
     * @return boolean
     */
    public function getLivre()
    {
        return $this->livre;
    }

    /**
     * Set dateLivraison
     *
     * @param \DateTime $dateLivraison
     *
     * @return BaseCommande
     */
    public function setDateLivraison($dateLivraison)
    {
        $this->date_livraison = $dateLivraison;

        return $this;
    }

    /**
     * Get dateLivraison
     *
     * @return \DateTime
     */
    public function getDateLivraison()
    {
        return $this->date_livraison;
    }

    /**
     * Set valideRemarque
     *
     * @param string $valideRemarque
     *
     * @return BaseCommande
     */
    public function setValideRemarque($valideRemarque)
    {
        $this->valide_remarque = $valideRemarque;

        return $this;
    }

    /**
     * Get valideRemarque
     *
     * @return string
     */
    public function getValideRemarque()
    {
        return $this->valide_remarque;
    }

    /**
     * Set livraisonRemarque
     *
     * @param string $livraisonRemarque
     *
     * @return BaseCommande
     */
    public function setLivraisonRemarque($livraisonRemarque)
    {
        $this->livraison_remarque = $livraisonRemarque;

        return $this;
    }

    /**
     * Get livraisonRemarque
     *
     * @return string
     */
    public function getLivraisonRemarque()
    {
        return $this->livraison_remarque;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return BaseCommande
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
     * @return BaseCommande
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
}

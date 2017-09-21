<?php

namespace AcMarche\LunchBundle\Entity;

use AcMarche\LunchBundle\Entity\Base\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Supplements
 *
 * @ORM\Table(name="supplement")
 * @ORM\Entity(repositoryClass="AcMarche\LunchBundle\Repository\SupplementRepository")
 */
class Supplement extends BaseEntity
{
    /**
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $prix;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $indisponible = false;

     /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $rupture_stock = false;

    /**
     * @ORM\ManyToMany(targetEntity="AcMarche\LunchBundle\Entity\Produit", mappedBy="supplements")
     *
     * @var
     */
    protected $produits;

     /**
      * Override pour inversedBy
      *
     * @ORM\ManyToOne(targetEntity="AcMarche\LunchBundle\Entity\Commerce", inversedBy="supplements")
     * @ORM\JoinColumn(nullable=false)
     * @var
     */
    protected $commerce;

     /**
     * ORM\OneToMany(targetEntity="AcMarche\LunchBundle\Entity\CommandeProduitSupplement", mappedBy="supplement")
     *
     * @var
     *
    protected $commande_produit_supplement;*/

    public function __toString() {
        return $this->nom.' (+'.$this->prix.' €)';
    }

    /**
     * STOP
     */

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set prix
     *
     * @param string $prix
     *
     * @return Supplement
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set indisponible
     *
     * @param boolean $indisponible
     *
     * @return Supplement
     */
    public function setIndisponible($indisponible)
    {
        $this->indisponible = $indisponible;

        return $this;
    }

    /**
     * Get indisponible
     *
     * @return boolean
     */
    public function getIndisponible()
    {
        return $this->indisponible;
    }

    /**
     * Set ruptureStock
     *
     * @param boolean $ruptureStock
     *
     * @return Supplement
     */
    public function setRuptureStock($ruptureStock)
    {
        $this->rupture_stock = $ruptureStock;

        return $this;
    }

    /**
     * Get ruptureStock
     *
     * @return boolean
     */
    public function getRuptureStock()
    {
        return $this->rupture_stock;
    }

    /**
     * Add produit
     *
     * @param \AcMarche\LunchBundle\Entity\Produit $produit
     *
     * @return Supplement
     */
    public function addProduit(\AcMarche\LunchBundle\Entity\Produit $produit)
    {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \AcMarche\LunchBundle\Entity\Produit $produit
     */
    public function removeProduit(\AcMarche\LunchBundle\Entity\Produit $produit)
    {
        $this->produits->removeElement($produit);
    }

    /**
     * Get produits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * Set commerce
     *
     * @param \AcMarche\LunchBundle\Entity\Commerce $commerce
     *
     * @return Supplement
     */
    public function setCommerce(\AcMarche\LunchBundle\Entity\Commerce $commerce)
    {
        $this->commerce = $commerce;

        return $this;
    }

    /**
     * Get commerce
     *
     * @return \AcMarche\LunchBundle\Entity\Commerce
     */
    public function getCommerce()
    {
        return $this->commerce;
    }
}

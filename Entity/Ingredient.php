<?php

namespace AcMarche\LunchBundle\Entity;

use AcMarche\LunchBundle\Entity\Base\BaseEntity;
use AcMarche\LunchBundle\Entity\TraitDef\CommerceTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ingredients
 *
 * @ORM\Table(name="ingredient")
 * @ORM\Entity(repositoryClass="AcMarche\LunchBundle\Repository\IngredientRepository")
 */
class Ingredient extends BaseEntity
{
    use CommerceTrait;

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
     * @var float
     *
     * @ORM\Column(type="integer")
     */
    protected $quantite_stock = -1;

    /**
     * @ORM\ManyToMany(targetEntity="AcMarche\LunchBundle\Entity\Produit", mappedBy="ingredients")
     *
     * @var
     */
    protected $produits;

    /**
     * Override pour inversedBy
     * @ORM\ManyToOne(targetEntity="AcMarche\LunchBundle\Entity\Commerce", inversedBy="ingredients")
     * @ORM\JoinColumn(nullable=false)
     * @var
     */
    protected $commerce;

    public function __toString()
    {
        return $this->nom;
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
     * Set indisponible
     *
     * @param boolean $indisponible
     *
     * @return Ingredient
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
     * @return Ingredient
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
     * Set quantiteStock
     *
     * @param integer $quantiteStock
     *
     * @return Ingredient
     */
    public function setQuantiteStock($quantiteStock)
    {
        $this->quantite_stock = $quantiteStock;

        return $this;
    }

    /**
     * Get quantiteStock
     *
     * @return integer
     */
    public function getQuantiteStock()
    {
        return $this->quantite_stock;
    }

    /**
     * Add produit
     *
     * @param \AcMarche\LunchBundle\Entity\Produit $produit
     *
     * @return Ingredient
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
}

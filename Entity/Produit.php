<?php

namespace AcMarche\LunchBundle\Entity;

use AcMarche\LunchBundle\Entity\Base\BaseProduit;
use AcMarche\LunchBundle\Entity\InterfaceDef\ProduitInterface;
use AcMarche\LunchBundle\Entity\TraitDef\CategoryTrait;
use AcMarche\LunchBundle\Entity\TraitDef\CommandeProduitTrait;
use AcMarche\LunchBundle\Entity\TraitDef\CommerceTrait;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="AcMarche\LunchBundle\Repository\ProduitRepository")
 * @Vich\Uploadable
 */
class Produit extends BaseProduit implements ProduitInterface
{
    use CategoryTrait;
    use CommerceTrait;
    use CommandeProduitTrait;

    /**
     * Surcharge pour mappedBy
     *
     * @ORM\OneToMany(targetEntity="AcMarche\LunchBundle\Entity\CommandeProduit", mappedBy="produit", cascade={"persist", "remove"})
     *
     */
    protected $commande_produits;

    /**
     * @ORM\ManyToMany(targetEntity="AcMarche\LunchBundle\Entity\Supplement", inversedBy="produits", cascade={"persist", "detach"})
     *
     */
    protected $supplements;
    /**
     * @ORM\ManyToMany(targetEntity="AcMarche\LunchBundle\Entity\Ingredient", inversedBy="produits", cascade={"persist", "detach"})
     *
     */
    protected $ingredients;

    /**
     * STOP
     */


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commande_produits = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ingredients = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add supplement
     *
     * @param \AcMarche\LunchBundle\Entity\Supplement $supplement
     *
     * @return Produit
     */
    public function addSupplement(\AcMarche\LunchBundle\Entity\Supplement $supplement)
    {
        $this->supplements[] = $supplement;

        return $this;
    }

    /**
     * Remove supplement
     *
     * @param \AcMarche\LunchBundle\Entity\Supplement $supplement
     */
    public function removeSupplement(\AcMarche\LunchBundle\Entity\Supplement $supplement)
    {
        $this->supplements->removeElement($supplement);
    }

    /**
     * Get supplements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSupplements()
    {
        return $this->supplements;
    }

    /**
     * Add ingredient
     *
     * @param \AcMarche\LunchBundle\Entity\Ingredient $ingredient
     *
     * @return Produit
     */
    public function addIngredient(\AcMarche\LunchBundle\Entity\Ingredient $ingredient)
    {
        $this->ingredients[] = $ingredient;

        return $this;
    }

    /**
     * Remove ingredient
     *
     * @param \AcMarche\LunchBundle\Entity\Ingredient $ingredient
     */
    public function removeIngredient(\AcMarche\LunchBundle\Entity\Ingredient $ingredient)
    {
        $this->ingredients->removeElement($ingredient);
    }

    /**
     * Get ingredients
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }
}

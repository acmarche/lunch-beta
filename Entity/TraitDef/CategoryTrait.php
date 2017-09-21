<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/09/17
 * Time: 17:00
 */

namespace AcMarche\LunchBundle\Entity\TraitDef;

use AcMarche\LunchBundle\Entity\InterfaceDef\CategoryInterface;
use AcMarche\LunchBundle\Entity\InterfaceDef\ProduitInterface;
use Doctrine\ORM\Mapping as ORM;

trait CategoryTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="AcMarche\LunchBundle\Entity\Categorie", inversedBy="produits")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    protected $categorie;

    /**
     * Set categorie
     *
     * @param CategoryInterface $categorie
     *
     * @return ProduitInterface
     */
    public function setCategorie(CategoryInterface $categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return CategoryInterface
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

}

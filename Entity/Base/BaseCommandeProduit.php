<?php

namespace AcMarche\LunchBundle\Entity\Base;

use AcMarche\LunchBundle\Entity\TraitDef\CommandeTrait;
use AcMarche\LunchBundle\Entity\TraitDef\ProduitTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * BaseCommandeProduit
 *
 * ORM\Entity() // pour entities
 */
abstract class BaseCommandeProduit
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
     * @ORM\Column(type="text", nullable=true)
     */
    protected $remarque;

    /**
     * Pour sauvegarde
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $produit_nom;

    /**
     * @ORM\Column(type="integer")
     * @var
     */
    protected $quantite;

    /**
     * Pour sauvegarde
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $prixHtva;

    /**
     * Pour sauvegarde
     *
     * @var string
     *
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $tvaApplique;


    public function __toString()
    {
        return "Commande Produit - ".$this->id;
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
     * Set remarque
     *
     * @param string $remarque
     *
     * @return BaseCommandeProduit
     */
    public function setRemarque($remarque)
    {
        $this->remarque = $remarque;

        return $this;
    }

    /**
     * Get remarque
     *
     * @return string
     */
    public function getRemarque()
    {
        return $this->remarque;
    }

    /**
     * Set produitNom
     *
     * @param string $produitNom
     *
     * @return BaseCommandeProduit
     */
    public function setProduitNom($produitNom)
    {
        $this->produit_nom = $produitNom;

        return $this;
    }

    /**
     * Get produitNom
     *
     * @return string
     */
    public function getProduitNom()
    {
        return $this->produit_nom;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return BaseCommandeProduit
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set prixHtva
     *
     * @param string $prixHtva
     *
     * @return BaseCommandeProduit
     */
    public function setPrixHtva($prixHtva)
    {
        $this->prixHtva = $prixHtva;

        return $this;
    }

    /**
     * Get prixHtva
     *
     * @return string
     */
    public function getPrixHtva()
    {
        return $this->prixHtva;
    }

    /**
     * Set tvaApplique
     *
     * @param string $tvaApplique
     *
     * @return BaseCommandeProduit
     */
    public function setTvaApplique($tvaApplique)
    {
        $this->tvaApplique = $tvaApplique;

        return $this;
    }

    /**
     * Get tvaApplique
     *
     * @return string
     */
    public function getTvaApplique()
    {
        return $this->tvaApplique;
    }
}

<?php

namespace AcMarche\LunchBundle\Entity;

use AcMarche\LunchBundle\Entity\Base\BaseEntity;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommerceInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Commerce
 *
 * @ORM\Table(name="commerce")
 * @ORM\Entity(repositoryClass="AcMarche\LunchBundle\Repository\CommerceRepository")
 * @Vich\Uploadable
 */
class Commerce extends BaseEntity implements CommerceInterface
{

    /**
     * @var int
     *
     * @ORM\Column(name="bottin_id", type="integer", nullable=true)
     */
    protected $bottinId;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $indisponible = false;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $tva_applicable;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $numero_tva;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=12, nullable=true)
     */
    protected $sms_numero;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Email()
     */
    protected $email_commande;

    /**
     * @var string
     * @Assert\Iban()
     * @ORM\Column(type="string", nullable=false)
     */
    protected $iban;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $livrasion_cout;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="AcMarche\LunchBundle\Entity\Produit", mappedBy="commerce")
     *
     * @var
     */
    protected $produits;

    /**
     * @ORM\OneToMany(targetEntity="AcMarche\LunchBundle\Entity\LieuLivraison", mappedBy="commerce")
     *
     * @var
     */
    protected $lieu_livraison;

    /**
     * @ORM\OneToMany(targetEntity="AcMarche\LunchBundle\Entity\Supplement", mappedBy="commerce")
     *
     * @var
     */
    protected $supplements;

    /**
     * @ORM\OneToMany(targetEntity="AcMarche\LunchBundle\Entity\Ingredient", mappedBy="commerce")
     *
     * @var
     */
    protected $ingredients;

    /**
     * @ORM\OneToMany(targetEntity="AcMarche\LunchBundle\Entity\Commande", mappedBy="commerce")
     *
     * @var
     */
    protected $commandes;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="acmarche_lunch_commerce_image", fileNameProperty="imageName", size="imageSize")
     *
     * @Assert\Image(
     *     detectCorrupted = true,
     *     corruptedMessage = "Product photo is corrupted. Upload it again."
     * )
     * @var File
     */
    protected $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    protected $imageName;

    /**
     * @ORM\Column(type="integer", nullable=true)
     *
     * @var integer
     */
    protected $imageSize;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Commerce
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->updated = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    protected $metas = [];

    /**
     * @return array
     */
    public function getMetas()
    {
        return $this->metas;
    }

    /**
     * @param array $metas
     */
    public function setMetas($metas)
    {
        $this->metas = $metas;
    }

    function __toString()
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
        $this->lieu_livraison = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ingredients = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commandes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set bottinId
     *
     * @param integer $bottinId
     *
     * @return Commerce
     */
    public function setBottinId($bottinId)
    {
        $this->bottinId = $bottinId;

        return $this;
    }

    /**
     * Get bottinId
     *
     * @return integer
     */
    public function getBottinId()
    {
        return $this->bottinId;
    }

    /**
     * Set indisponible
     *
     * @param boolean $indisponible
     *
     * @return Commerce
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
     * Set tvaApplicable
     *
     * @param string $tvaApplicable
     *
     * @return Commerce
     */
    public function setTvaApplicable($tvaApplicable)
    {
        $this->tva_applicable = $tvaApplicable;

        return $this;
    }

    /**
     * Get tvaApplicable
     *
     * @return string
     */
    public function getTvaApplicable()
    {
        return $this->tva_applicable;
    }

    /**
     * Set numeroTva
     *
     * @param string $numeroTva
     *
     * @return Commerce
     */
    public function setNumeroTva($numeroTva)
    {
        $this->numero_tva = $numeroTva;

        return $this;
    }

    /**
     * Get numeroTva
     *
     * @return string
     */
    public function getNumeroTva()
    {
        return $this->numero_tva;
    }

    /**
     * Set smsNumero
     *
     * @param string $smsNumero
     *
     * @return Commerce
     */
    public function setSmsNumero($smsNumero)
    {
        $this->sms_numero = $smsNumero;

        return $this;
    }

    /**
     * Get smsNumero
     *
     * @return string
     */
    public function getSmsNumero()
    {
        return $this->sms_numero;
    }

    /**
     * Set emailCommande
     *
     * @param string $emailCommande
     *
     * @return Commerce
     */
    public function setEmailCommande($emailCommande)
    {
        $this->email_commande = $emailCommande;

        return $this;
    }

    /**
     * Get emailCommande
     *
     * @return string
     */
    public function getEmailCommande()
    {
        return $this->email_commande;
    }

    /**
     * Set iban
     *
     * @param string $iban
     *
     * @return Commerce
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * Get iban
     *
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * Set livrasionCout
     *
     * @param string $livrasionCout
     *
     * @return Commerce
     */
    public function setLivrasionCout($livrasionCout)
    {
        $this->livrasion_cout = $livrasionCout;

        return $this;
    }

    /**
     * Get livrasionCout
     *
     * @return string
     */
    public function getLivrasionCout()
    {
        return $this->livrasion_cout;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return Commerce
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
     * Set imageName
     *
     * @param string $imageName
     *
     * @return Commerce
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * Set imageSize
     *
     * @param integer $imageSize
     *
     * @return Commerce
     */
    public function setImageSize($imageSize)
    {
        $this->imageSize = $imageSize;

        return $this;
    }

    /**
     * Get imageSize
     *
     * @return integer
     */
    public function getImageSize()
    {
        return $this->imageSize;
    }

    /**
     * Add produit
     *
     * @param \AcMarche\LunchBundle\Entity\Produit $produit
     *
     * @return Commerce
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
     * Add lieuLivraison
     *
     * @param \AcMarche\LunchBundle\Entity\LieuLivraison $lieuLivraison
     *
     * @return Commerce
     */
    public function addLieuLivraison(\AcMarche\LunchBundle\Entity\LieuLivraison $lieuLivraison)
    {
        $this->lieu_livraison[] = $lieuLivraison;

        return $this;
    }

    /**
     * Remove lieuLivraison
     *
     * @param \AcMarche\LunchBundle\Entity\LieuLivraison $lieuLivraison
     */
    public function removeLieuLivraison(\AcMarche\LunchBundle\Entity\LieuLivraison $lieuLivraison)
    {
        $this->lieu_livraison->removeElement($lieuLivraison);
    }

    /**
     * Get lieuLivraison
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLieuLivraison()
    {
        return $this->lieu_livraison;
    }

    /**
     * Add supplement
     *
     * @param \AcMarche\LunchBundle\Entity\Supplement $supplement
     *
     * @return Commerce
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
     * @return Commerce
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

    /**
     * Add commande
     *
     * @param \AcMarche\LunchBundle\Entity\Commande $commande
     *
     * @return Commerce
     */
    public function addCommande(\AcMarche\LunchBundle\Entity\Commande $commande)
    {
        $this->commandes[] = $commande;

        return $this;
    }

    /**
     * Remove commande
     *
     * @param \AcMarche\LunchBundle\Entity\Commande $commande
     */
    public function removeCommande(\AcMarche\LunchBundle\Entity\Commande $commande)
    {
        $this->commandes->removeElement($commande);
    }

    /**
     * Get commandes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandes()
    {
        return $this->commandes;
    }
}

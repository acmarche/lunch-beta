<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/09/17
 * Time: 19:22
 */

namespace AcMarche\LunchBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BaseProduit
 * @package AcMarche\LunchBundle\Entity
 * ORM\Entity() //pour generation setter/getter
 */
abstract class BaseProduit extends BaseEntity
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $reference;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * Retirer de la vente le produit
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $indisponible = false;

    /**
     * Lunch ou pas
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $isFood = false;

    /**
     * -1 = infinis
     * @var float
     *
     * @ORM\Column(type="integer")
     */
    protected $quantite_stock = -1;

    /**
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $prix_htva;

    /**
     * Non stocke en Bd
     * @var float
     */
    protected $prix_tvac;

    /**
     * @var string
     *
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $tva_applicable;

    /******************
     * IMAGE
     * ****************
     */

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="acmarche_lunch_produit_image", fileNameProperty="imageName", size="imageSize")
     *
     * @Assert\Image(
     *     detectCorrupted = true,
     *     corruptedMessage = "Product photo is corrupted. Upload it again."
     * )
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
     * @return BaseProduit
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

    /**
     * @return float
     */
    public function getPrixTvac()
    {
        return $this->prix_tvac;
    }

    /**
     * @param float $prixTvac
     */
    public function setPrixTvac($prixTvac)
    {
        $this->prix_tvac = $prixTvac;
    }

    public function __toString()
    {
        return $this->getNom();
    }


    /**
     * STOP
     */


    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return BaseProduit
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return BaseProduit
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
     * Set indisponible
     *
     * @param boolean $indisponible
     *
     * @return BaseProduit
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
     * Set isFood
     *
     * @param boolean $isFood
     *
     * @return BaseProduit
     */
    public function setIsFood($isFood)
    {
        $this->isFood = $isFood;

        return $this;
    }

    /**
     * Get isFood
     *
     * @return boolean
     */
    public function getIsFood()
    {
        return $this->isFood;
    }

    /**
     * Set quantiteStock
     *
     * @param integer $quantiteStock
     *
     * @return BaseProduit
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
     * Set prixHtva
     *
     * @param string $prixHtva
     *
     * @return BaseProduit
     */
    public function setPrixHtva($prixHtva)
    {
        $this->prix_htva = $prixHtva;

        return $this;
    }

    /**
     * Get prixHtva
     *
     * @return string
     */
    public function getPrixHtva()
    {
        return $this->prix_htva;
    }

    /**
     * Set tvaApplicable
     *
     * @param string $tvaApplicable
     *
     * @return BaseProduit
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
     * Set imageName
     *
     * @param string $imageName
     *
     * @return BaseProduit
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
     * @return BaseProduit
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
}

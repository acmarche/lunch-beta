<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/09/17
 * Time: 17:02
 */

namespace AcMarche\LunchBundle\Entity\InterfaceDef;


interface ProduitInterface
{
    public function __toString();

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getNom();

    /**
     * @param string $nom
     */
    public function setNom($nom);

    /**
     * @return mixed
     */
    public function getCreated();

    /**
     * @param mixed $created
     */
    public function setCreated($created);

    /**
     * @return mixed
     */
    public function getUpdated();

    /**
     * @param mixed $updated
     */
    public function setUpdated($updated);

    /**
     * @return float
     */
    public function getPrixTvac();

    /**
     * @param float $prixTvac
     */
    public function setPrixTvac($prixTvac);

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return ProduitInterface
     */
    public function setReference($reference);

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference();

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ProduitInterface
     */
    public function setDescription($description);

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set indisponible
     *
     * @param boolean $indisponible
     *
     * @return ProduitInterface
     */
    public function setIndisponible($indisponible);

    /**
     * Get indisponible
     *
     * @return boolean
     */
    public function getIndisponible();

    /**
     * Set quantiteStock
     *
     * @param integer $quantiteStock
     *
     * @return ProduitInterface
     */
    public function setQuantiteStock($quantiteStock);

    /**
     * Get quantiteStock
     *
     * @return integer
     */
    public function getQuantiteStock();

    /**
     * Set prixHtva
     *
     * @param string $prixHtva
     *
     * @return ProduitInterface
     */
    public function setPrixHtva($prixHtva);

    /**
     * Get prixHtva
     *
     * @return float
     */
    public function getPrixHtva();

    /**
     * Set tvaApplicable
     *
     * @param string $tvaApplicable
     *
     * @return ProduitInterface
     */
    public function setTvaApplicable($tvaApplicable);

    /**
     * Get tvaApplicable
     *
     * @return string
     */
    public function getTvaApplicable();

    /**
     * Set imageName
     *
     * @param string $imageName
     *
     * @return ProduitInterface
     */
    public function setImageName($imageName);

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName();

    /**
     * Set imageSize
     *
     * @param integer $imageSize
     *
     * @return ProduitInterface
     */
    public function setImageSize($imageSize);

    /**
     * Get imageSize
     *
     * @return integer
     */
    public function getImageSize();

    /**
     * Set commerce
     *
     * @param CategoryInterface $commerce
     */
    public function setCommerce(CommerceInterface $commerce);

    /**
     * Get commerce
     *
     * @return CommerceInterface
     */
    public function getCommerce();

     /**
     * Set isFood
     *
     * @param boolean $isFood
     *
     * @return ProduitInterface
     */
    public function setIsFood($isFood);

    /**
     * Get isFood
     *
     * @return boolean
     */
    public function getIsFood();
}
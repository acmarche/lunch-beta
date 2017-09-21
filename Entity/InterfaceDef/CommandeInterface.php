<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/09/17
 * Time: 17:02
 */

namespace AcMarche\LunchBundle\Entity\InterfaceDef;


use AcMarche\LunchBundle\Entity\LieuLivraison;

interface CommandeInterface
{
    public function __toString();

    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set user
     *
     * @param string $user
     *
     * @return CommerceInterface
     */
    public function setUser($user);

    /**
     * Get user
     *
     * @return string
     */
    public function getUser();

    /**
     * Set commerceNom
     *
     * @param string $commerceNom
     *
     * @return CommerceInterface
     */
    public function setCommerceNom($commerceNom);

    /**
     * Get commerceNom
     *
     * @return string
     */
    public function getCommerceNom();

    /**
     * Set cloture
     *
     * @param boolean $cloture
     *
     * @return CommerceInterface
     */
    public function setCloture($cloture);

    /**
     * Get cloture
     *
     * @return boolean
     */
    public function getCloture();

    /**
     * Set paye
     *
     * @param boolean $paye
     *
     * @return CommerceInterface
     */
    public function setPaye($paye);

    /**
     * Get paye
     *
     * @return boolean
     */
    public function getPaye();

    /**
     * Set livre
     *
     * @param boolean $livre
     *
     * @return CommerceInterface
     */
    public function setLivre($livre);

    /**
     * Get livre
     *
     * @return boolean
     */
    public function getLivre();

    /**
     * Set dateLivraison
     *
     * @param \DateTime $dateLivraison
     *
     * @return CommerceInterface
     */
    public function setDateLivraison($dateLivraison);

    /**
     * Get dateLivraison
     *
     * @return \DateTime
     */
    public function getDateLivraison();

    /**
     * Set livraisonRemarque
     *
     * @param string $livraisonRemarque
     *
     * @return CommerceInterface
     */
    public function setLivraisonRemarque($livraisonRemarque);

    /**
     * Get livraisonRemarque
     *
     * @return string
     */
    public function getLivraisonRemarque();

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return CommerceInterface
     */
    public function setCreated($created);

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated();

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return CommerceInterface
     */
    public function setUpdated($updated);

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated();

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
     * Get commandeProduits
     *
     * @return CommandeProduitInterface[]
     */
    public function getCommandeProduits();

    /**
     * @return mixed
     */
    public function getCouts();

    /**
     * @param mixed $couts
     */
    public function setCouts($couts);

    /**
     * Set lieuLivraison
     *
     * @param \AcMarche\LunchBundle\Entity\LieuLivraison $lieuLivraison
     *
     * @return CommandeInterface
     */
    public function setLieuLivraison(LieuLivraison $lieuLivraison = null);

    /**
     * Get lieuLivraison
     *
     * @return \AcMarche\LunchBundle\Entity\LieuLivraison
     */
    public function getLieuLivraison();

    /**
     * Set valide
     *
     * @param boolean $valide
     *
     * @return CommandeInterface
     */
    public function setValide($valide);

    /**
     * Get valide
     *
     * @return boolean
     */
    public function getValide();

    /**
     * Set valideRemarque
     *
     * @param string $valideRemarque
     *
     * @return CommandeInterface
     */
    public function setValideRemarque($valideRemarque);

    /**
     * Get valideRemarque
     *
     * @return string
     */
    public function getValideRemarque();

    /**
     * Get isFood
     * Raccourcis pour les templates
     * @return boolean
     */
    public function getisFood();
}
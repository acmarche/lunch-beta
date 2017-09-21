<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/09/17
 * Time: 17:02
 */

namespace AcMarche\LunchBundle\Entity\InterfaceDef;


interface CommandeProduitInterface
{

    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set commande
     *
     * @param CommandeInterface $commande
     *
     */
    public function setCommande(CommandeInterface $commande);

    /**
     * Get commerce
     *
     * @return CommandeInterface
     */
    public function getCommande();

    /**
     * @return ProduitInterface
     */
    public function getProduit();

    /**
     * @param ProduitInterface $produit
     */
    public function setProduit(ProduitInterface $produit);


    /**
     * Set remarque
     *
     * @param string $remarque
     *
     * @return CommandeProduitInterface
     */
    public function setRemarque($remarque);

    /**
     * Get remarque
     *
     * @return string
     */
    public function getRemarque();

    /**
     * Set produitNom
     *
     * @param string $produitNom
     *
     * @return CommandeProduitInterface
     */
    public function setProduitNom($produitNom);

    /**
     * Get produitNom
     *
     * @return string
     */
    public function getProduitNom();

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return CommandeProduitInterface
     */
    public function setQuantite($quantite);

    /**
     * Get quantite
     *
     * @return integer
     */
    public function getQuantite();

    /**
     * Set prixHtva
     *
     * @param string $prixHtva
     *
     * @return CommandeProduitInterface
     */
    public function setPrixHtva($prixHtva);

    /**
     * Get prixHtva
     *
     * @return string
     */
    public function getPrixHtva();

    /**
     * Set tvaApplique
     *
     * @param string $tvaApplique
     *
     * @return CommandeProduitInterface
     */
    public function setTvaApplique($tvaApplique);

    /**
     * Get tvaApplique
     *
     * @return string
     */
    public function getTvaApplique();

}
<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 5/09/17
 * Time: 16:21
 */

namespace AcMarche\LunchBundle\Entity;

use AcMarche\LunchBundle\Entity\InterfaceDef\LieuLivraisonInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commerce
 *
 * @ORM\Table(name="lieu_livraison")
 * @ORM\Entity(repositoryClass="AcMarche\LunchBundle\Repository\LieuxLivraisonRepository")
 *
 */
class LieuLivraison implements LieuLivraisonInterface
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
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $nom;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $rue;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $numero;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $code_postal;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $localite;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="AcMarche\LunchBundle\Entity\Commande", mappedBy="lieu_livraison", cascade={"persist"})
     *
     */
    protected $commandes;

    /**
     * @ORM\ManyToOne(targetEntity="AcMarche\LunchBundle\Entity\Commerce", inversedBy="lieu_livraison")
     * @ORM\JoinColumn(nullable=true)
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
        $this->commandes = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set nom
     *
     * @param string $nom
     *
     * @return LieuLivraison
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set rue
     *
     * @param string $rue
     *
     * @return LieuLivraison
     */
    public function setRue($rue)
    {
        $this->rue = $rue;

        return $this;
    }

    /**
     * Get rue
     *
     * @return string
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return LieuLivraison
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set codePostal
     *
     * @param string $codePostal
     *
     * @return LieuLivraison
     */
    public function setCodePostal($codePostal)
    {
        $this->code_postal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return string
     */
    public function getCodePostal()
    {
        return $this->code_postal;
    }

    /**
     * Set localite
     *
     * @param string $localite
     *
     * @return LieuLivraison
     */
    public function setLocalite($localite)
    {
        $this->localite = $localite;

        return $this;
    }

    /**
     * Get localite
     *
     * @return string
     */
    public function getLocalite()
    {
        return $this->localite;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return LieuLivraison
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
     * Add commande
     *
     * @param \AcMarche\LunchBundle\Entity\Commande $commande
     *
     * @return LieuLivraison
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

    /**
     * Set commerce
     *
     * @param \AcMarche\LunchBundle\Entity\Commerce $commerce
     *
     * @return LieuLivraison
     */
    public function setCommerce(\AcMarche\LunchBundle\Entity\Commerce $commerce = null)
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

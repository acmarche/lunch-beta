<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/09/17
 * Time: 17:00
 */

namespace AcMarche\LunchBundle\Entity\TraitDef;

use AcMarche\LunchBundle\Entity\InterfaceDef\CategoryInterface;
use AcMarche\LunchBundle\Entity\InterfaceDef\CommerceInterface;
use Doctrine\ORM\Mapping as ORM;

trait CommerceTrait
{

    /**
     * @ORM\ManyToOne(targetEntity="AcMarche\LunchBundle\Entity\Commerce", inversedBy="produits")
     * @ORM\JoinColumn(nullable=false)
     * @var
     */
    protected $commerce;

    /**
     * Set commerce
     *
     * @param CategoryInterface $commerce
     */
    public function setCommerce(CommerceInterface $commerce)
    {
        $this->commerce = $commerce;
    }

    /**
     * Get commerce
     *
     * @return CommerceInterface
     */
    public function getCommerce()
    {
        return $this->commerce;
    }

}

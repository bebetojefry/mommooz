<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeliveryCharge
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class DeliveryCharge
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="price_from", type="float")
     */
    private $priceFrom;

    /**
     * @var float
     *
     * @ORM\Column(name="price_to", type="float")
     */
    private $priceTo;

    /**
     * @var float
     *
     * @ORM\Column(name="charge", type="float")
     */
    private $charge;


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
     * Set priceFrom
     *
     * @param string $priceFrom
     *
     * @return DeliveryCharge
     */
    public function setPriceFrom($priceFrom)
    {
        $this->priceFrom = $priceFrom;

        return $this;
    }

    /**
     * Get priceFrom
     *
     * @return string
     */
    public function getPriceFrom()
    {
        return $this->priceFrom;
    }

    /**
     * Set priceTo
     *
     * @param float $priceTo
     *
     * @return DeliveryCharge
     */
    public function setPriceTo($priceTo)
    {
        $this->priceTo = $priceTo;

        return $this;
    }

    /**
     * Get priceTo
     *
     * @return float
     */
    public function getPriceTo()
    {
        return $this->priceTo;
    }

    /**
     * Set charge
     *
     * @param float $charge
     *
     * @return DeliveryCharge
     */
    public function setCharge($charge)
    {
        $this->charge = $charge;

        return $this;
    }

    /**
     * Get charge
     *
     * @return float
     */
    public function getCharge()
    {
        return $this->charge;
    }
}


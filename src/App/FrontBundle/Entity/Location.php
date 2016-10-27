<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Location
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\FrontBundle\Entity\LocationRepository")
 */
class Location
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
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="locations"))
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="local_name", type="string", length=255)
     */
    private $localName;

    /**
     * @var string
     *
     * @ORM\Column(name="pin_code", type="string", length=255)
     */
    private $pinCode;

    /**
     * @var float
     *
     * @ORM\Column(name="local_service_charge", type="float")
     */
    private $localServiceCharge;

    /**
     * @var float
     *
     * @ORM\Column(name="regional_service_charge", type="float")
     */
    private $regionalServiceCharge;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;


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
     * Set region
     *
     * @param string $region
     *
     * @return Location
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set localName
     *
     * @param string $localName
     *
     * @return Location
     */
    public function setLocalName($localName)
    {
        $this->localName = $localName;

        return $this;
    }

    /**
     * Get localName
     *
     * @return string
     */
    public function getLocalName()
    {
        return $this->localName;
    }

    /**
     * Set pinCode
     *
     * @param string $pinCode
     *
     * @return Location
     */
    public function setPinCode($pinCode)
    {
        $this->pinCode = $pinCode;

        return $this;
    }

    /**
     * Get pinCode
     *
     * @return string
     */
    public function getPinCode()
    {
        return $this->pinCode;
    }

    /**
     * Set localServiceCharge
     *
     * @param float $localServiceCharge
     *
     * @return Location
     */
    public function setLocalServiceCharge($localServiceCharge)
    {
        $this->localServiceCharge = $localServiceCharge;

        return $this;
    }

    /**
     * Get localServiceCharge
     *
     * @return float
     */
    public function getLocalServiceCharge()
    {
        return $this->localServiceCharge;
    }

    /**
     * Set regionalServiceCharge
     *
     * @param float $regionalServiceCharge
     *
     * @return Location
     */
    public function setRegionalServiceCharge($regionalServiceCharge)
    {
        $this->regionalServiceCharge = $regionalServiceCharge;

        return $this;
    }

    /**
     * Get regionalServiceCharge
     *
     * @return float
     */
    public function getRegionalServiceCharge()
    {
        return $this->regionalServiceCharge;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Location
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }
}

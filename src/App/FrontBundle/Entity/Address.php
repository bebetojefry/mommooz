<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\FrontBundle\Annotation as AppAnnotation;

/**
 * Address
 *
 * @ORM\Table()
 * @ORM\Entity
 * @AppAnnotation\CmsClass(displayName="User Address")
 */
class Address
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
     * @var string
     *
     * @ORM\Column(name="house", type="string", length=255)
     * @AppAnnotation\CmsProperty(displayName="House Name")
     */
    private $house;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=255)
     * @AppAnnotation\CmsProperty(displayName="Street Name")
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="landmark", type="string", length=255)
     * @AppAnnotation\CmsProperty(displayName="Land Mark")
     */
    private $landmark;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;
    
    /**
     * @var string
     *
     * @ORM\Column(name="pin", type="string", length=255)
     */
    private $pin;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_default", type="boolean", nullable=true)
     */
    private $default;

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
     * Set house
     *
     * @param string $house
     *
     * @return Address
     */
    public function setHouse($house)
    {
        $this->house = $house;
    
        return $this;
    }

    /**
     * Get house
     *
     * @return string
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * Set street
     *
     * @param string $street
     *
     * @return Address
     */
    public function setStreet($street)
    {
        $this->street = $street;
    
        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set landmark
     *
     * @param string $landmark
     *
     * @return Address
     */
    public function setLandmark($landmark)
    {
        $this->landmark = $landmark;
    
        return $this;
    }

    /**
     * Get landmark
     *
     * @return string
     */
    public function getLandmark()
    {
        return $this->landmark;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Address
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        if($this->state == null){
            $this->state = 'Kerala';
        }
        
        return $this->state;
    }

    /**
     * Set default
     *
     * @param boolean $default
     *
     * @return Address
     */
    public function setDefault($default)
    {
        $this->default = $default;
    
        return $this;
    }

    /**
     * Get default
     *
     * @return boolean
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Set pin
     *
     * @param string $pin
     *
     * @return Address
     */
    public function setPin($pin)
    {
        $this->pin = $pin;
    
        return $this;
    }

    /**
     * Get pin
     *
     * @return string
     */
    public function getPin()
    {
        return $this->pin;
    }
}

<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\FrontBundle\Entity\Region;
use Doctrine\Common\Collections\Collection;

/**
 * State
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class State
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
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="states"))
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="state_name", type="string", length=255)
     */
    private $stateName;
    
    /**
     * @var ArrayCollection|District[]
     *
     * @ORM\OneToMany(targetEntity="District", mappedBy="state")
     */
    private $districts;

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
     * Set country
     *
     * @param Country $country
     *
     * @return State
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set stateName
     *
     * @param string $stateName
     *
     * @return State
     */
    public function setStateName($stateName)
    {
        $this->stateName = $stateName;

        return $this;
    }

    /**
     * Get stateName
     *
     * @return string
     */
    public function getStateName()
    {
        return $this->stateName;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->districts = new ArrayCollection();
    }

    /**
     * Add district
     *
     * @param \App\FrontBundle\Entity\District $district
     *
     * @return State
     */
    public function addDistrict(\App\FrontBundle\Entity\District $district)
    {
        $this->districts[] = $district;

        return $this;
    }

    /**
     * Remove district
     *
     * @param \App\FrontBundle\Entity\District $district
     */
    public function removeDistrict(\App\FrontBundle\Entity\District $district)
    {
        $this->districts->removeElement($district);
    }

    /**
     * Get districts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDistricts()
    {
        return $this->districts;
    }
}

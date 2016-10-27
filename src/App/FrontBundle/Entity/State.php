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
     * @var ArrayCollection|Region[]
     *
     * @ORM\OneToMany(targetEntity="Region", mappedBy="state")
     */
    private $regions;

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
        $this->regions = new ArrayCollection();
    }

    /**
     * Add region
     *
     * @param Region $region
     *
     * @return State
     */
    public function addRegion(Region $region)
    {
        $this->regions[] = $region;

        return $this;
    }

    /**
     * Remove region
     *
     * @param Region $region
     */
    public function removeRegion(Region $region)
    {
        $this->regions->removeElement($region);
    }

    /**
     * Get regions
     *
     * @return Collection
     */
    public function getRegions()
    {
        return $this->regions;
    }
}

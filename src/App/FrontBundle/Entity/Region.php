<?php

namespace App\FrontBundle\Entity;
use App\FrontBundle\Entity\Location;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Region
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\FrontBundle\Entity\RegionRepository")
 */
class Region
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
     * @var District
     *
     * @ORM\ManyToOne(targetEntity="District", inversedBy="regions"))
     */
    private $district;

    /**
     * @var string
     *
     * @ORM\Column(name="region_name", type="string", length=255)
     */
    private $regionName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_on", type="datetime")
     */
    private $createdOn;
    
    /**
     * @var ArrayCollection|Location[]
     *
     * @ORM\OneToMany(targetEntity="Location", mappedBy="region")
     */
    private $locations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdOn = new \DateTime();
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
     * Set regionName
     *
     * @param string $regionName
     *
     * @return Region
     */
    public function setRegionName($regionName)
    {
        $this->regionName = $regionName;

        return $this;
    }

    /**
     * Get regionName
     *
     * @return string
     */
    public function getRegionName()
    {
        return $this->regionName;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Region
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

    /**
     * Set createdOn
     *
     * @param \DateTime $createdOn
     *
     * @return Region
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;

        return $this;
    }

    /**
     * Get createdOn
     *
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * Add location
     *
     * @param Location $location
     *
     * @return Region
     */
    public function addLocation(Location $location)
    {
        $this->locations[] = $location;

        return $this;
    }

    /**
     * Remove location
     *
     * @param Location $location
     */
    public function removeLocation(Location $location)
    {
        $this->locations->removeElement($location);
    }

    /**
     * Get locations
     *
     * @return Collection
     */
    public function getLocations()
    {
        return $this->locations;
    }

    /**
     * Set district
     *
     * @param \App\FrontBundle\Entity\District $district
     *
     * @return Region
     */
    public function setDistrict(\App\FrontBundle\Entity\District $district = null)
    {
        $this->district = $district;

        return $this;
    }

    /**
     * Get district
     *
     * @return \App\FrontBundle\Entity\District
     */
    public function getDistrict()
    {
        return $this->district;
    }
}

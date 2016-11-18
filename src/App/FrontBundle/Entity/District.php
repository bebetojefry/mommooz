<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * State
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class District
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
     * @var State
     *
     * @ORM\ManyToOne(targetEntity="State", inversedBy="districts"))
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var ArrayCollection|Region[]
     *
     * @ORM\OneToMany(targetEntity="Region", mappedBy="district")
     */
    private $regions;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->regions = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return District
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set state
     *
     * @param \App\FrontBundle\Entity\State $state
     *
     * @return District
     */
    public function setState(\App\FrontBundle\Entity\State $state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return \App\FrontBundle\Entity\State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Add region
     *
     * @param \App\FrontBundle\Entity\Region $region
     *
     * @return District
     */
    public function addRegion(\App\FrontBundle\Entity\Region $region)
    {
        $this->regions[] = $region;

        return $this;
    }

    /**
     * Remove region
     *
     * @param \App\FrontBundle\Entity\Region $region
     */
    public function removeRegion(\App\FrontBundle\Entity\Region $region)
    {
        $this->regions->removeElement($region);
    }

    /**
     * Get regions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegions()
    {
        return $this->regions;
    }
}

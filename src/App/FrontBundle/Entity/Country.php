<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use App\FrontBundle\Entity\State;
use Doctrine\Common\Collections\Collection;

/**
 * Country
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Country
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
     * @ORM\Column(name="country_code", type="string", length=255)
     */
    private $countryCode;
    
    /**
     * @var string
     *
     * @ORM\Column(name="country_name", type="string", length=255)
     */
    private $countryName;

    /**
     * @var ArrayCollection|State[]
     *
     * @ORM\OneToMany(targetEntity="State", mappedBy="country")
     */
    private $states;
    
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
     * Set countryName
     *
     * @param string $countryName
     *
     * @return Country
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;

        return $this;
    }

    /**
     * Get countryName
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->states = new ArrayCollection();
    }

    /**
     * Add state
     *
     * @param State $state
     *
     * @return Country
     */
    public function addState(State $state)
    {
        $this->states[] = $state;

        return $this;
    }

    /**
     * Remove state
     *
     * @param State $state
     */
    public function removeState(State $state)
    {
        $this->states->removeElement($state);
    }

    /**
     * Get states
     *
     * @return Collection
     */
    public function getStates()
    {
        return $this->states;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return Country
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }
}

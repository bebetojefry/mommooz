<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stock
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Stock
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var Vendor
     * 
     * @ORM\ManyToOne(targetEntity="Vendor", inversedBy="stocks")
     */
    private $vendor;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;
    
    /**
     * @var ArrayCollection|Offer[]
     *
     * @ORM\ManyToMany(targetEntity="Offer")
     */
    private $offers;
    
    /**
     * @var ArrayCollection|StockEntry[]
     *
     * @ORM\OneToMany(targetEntity="StockEntry", mappedBy="stock")
     */
    private $items;
    
    

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
     * @return Stock
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Stock
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Stock
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
     * Set vendor
     *
     * @param \App\FrontBundle\Entity\Vendor $vendor
     *
     * @return Stock
     */
    public function setVendor(\App\FrontBundle\Entity\Vendor $vendor = null)
    {
        $this->vendor = $vendor;
    
        return $this;
    }

    /**
     * Get vendor
     *
     * @return \App\FrontBundle\Entity\Vendor
     */
    public function getVendor()
    {
        return $this->vendor;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add item
     *
     * @param \App\FrontBundle\Entity\StockEntry $item
     *
     * @return Stock
     */
    public function addItem(\App\FrontBundle\Entity\StockEntry $item)
    {
        $this->items[] = $item;
    
        return $this;
    }

    /**
     * Remove item
     *
     * @param \App\FrontBundle\Entity\StockEntry $item
     */
    public function removeItem(\App\FrontBundle\Entity\StockEntry $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Add offer
     *
     * @param \App\FrontBundle\Entity\Offer $offer
     *
     * @return Stock
     */
    public function addOffer(\App\FrontBundle\Entity\Offer $offer)
    {
        $this->offers[] = $offer;
    
        return $this;
    }

    /**
     * Remove offer
     *
     * @param \App\FrontBundle\Entity\Offer $offer
     */
    public function removeOffer(\App\FrontBundle\Entity\Offer $offer)
    {
        $this->offers->removeElement($offer);
    }

    /**
     * Get offers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOffers()
    {
        return $this->offers;
    }
}

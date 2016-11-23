<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StockEntry
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class StockEntry
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
     * @var Item
     * 
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="stock_entries")
     */
    private $item;
    
    /**
     * @var Stock
     * 
     * @ORM\ManyToOne(targetEntity="Stock", inversedBy="items")
     */
    private $stock;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="actual_price", type="float")
     */
    private $actualPrice;

    /**
     * @var ArrayCollection|Offer[]
     *
     * @ORM\ManyToMany(targetEntity="Offer")
     */
    private $offers;

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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return StockEntry
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    
        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return StockEntry
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set actualPrice
     *
     * @param float $actualPrice
     *
     * @return StockEntry
     */
    public function setActualPrice($actualPrice)
    {
        $this->actualPrice = $actualPrice;
    
        return $this;
    }

    /**
     * Get actualPrice
     *
     * @return float
     */
    public function getActualPrice()
    {
        return $this->actualPrice;
    }

    /**
     * Set offers
     *
     * @param string $offers
     *
     * @return StockEntry
     */
    public function setOffers($offers)
    {
        $this->offers = $offers;
    
        return $this;
    }

    /**
     * Get offers
     *
     * @return string
     */
    public function getOffers()
    {
        return $this->offers;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return StockEntry
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
     * Set item
     *
     * @param \App\FrontBundle\Entity\Item $item
     *
     * @return StockEntry
     */
    public function setItem(\App\FrontBundle\Entity\Item $item = null)
    {
        $this->item = $item;
    
        return $this;
    }

    /**
     * Get item
     *
     * @return \App\FrontBundle\Entity\Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set stock
     *
     * @param \App\FrontBundle\Entity\Stock $stock
     *
     * @return StockEntry
     */
    public function setStock(\App\FrontBundle\Entity\Stock $stock = null)
    {
        $this->stock = $stock;
    
        return $this;
    }

    /**
     * Get stock
     *
     * @return \App\FrontBundle\Entity\Stock
     */
    public function getStock()
    {
        return $this->stock;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->offers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add offer
     *
     * @param \App\FrontBundle\Entity\Offer $offer
     *
     * @return StockEntry
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
}

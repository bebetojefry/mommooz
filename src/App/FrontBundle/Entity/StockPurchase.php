<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StockPurchase
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class StockPurchase
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="purchases")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Purchase")
     * @ORM\JoinColumn(name="purchase", referencedColumnName="id", nullable=true)
     */
    private $purchase;
    
    /**
     * @var StockEntry
     * 
     * @ORM\ManyToOne(targetEntity="StockEntry", inversedBy="purchases")
     */
    private $stockItem;

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
     * @var boolean
     *
     * @ORM\Column(name="reverse", type="boolean")
     */
    private $reverse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="purchsed_on", type="datetime")
     */
    private $purchsedOn;


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
     * @return StockPurchase
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
     * @return StockPurchase
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
     * Set reverse
     *
     * @param boolean $reverse
     *
     * @return StockPurchase
     */
    public function setReverse($reverse)
    {
        $this->reverse = $reverse;
    
        return $this;
    }

    /**
     * Get reverse
     *
     * @return boolean
     */
    public function getReverse()
    {
        return $this->reverse;
    }

    /**
     * Set purchsedOn
     *
     * @param \DateTime $purchsedOn
     *
     * @return StockPurchase
     */
    public function setPurchsedOn($purchsedOn)
    {
        $this->purchsedOn = $purchsedOn;
    
        return $this;
    }

    /**
     * Get purchsedOn
     *
     * @return \DateTime
     */
    public function getPurchsedOn()
    {
        return $this->purchsedOn;
    }

    /**
     * Set user
     *
     * @param \App\FrontBundle\Entity\User $user
     *
     * @return StockPurchase
     */
    public function setUser(\App\FrontBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \App\FrontBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set stockItem
     *
     * @param \App\FrontBundle\Entity\StockEntry $stockItem
     *
     * @return StockPurchase
     */
    public function setStockItem(\App\FrontBundle\Entity\StockEntry $stockItem = null)
    {
        $this->stockItem = $stockItem;
    
        return $this;
    }

    /**
     * Get stockItem
     *
     * @return \App\FrontBundle\Entity\StockEntry
     */
    public function getStockItem()
    {
        return $this->stockItem;
    }

    /**
     * Set purchase
     *
     * @param \App\FrontBundle\Entity\Purchase $purchase
     *
     * @return StockPurchase
     */
    public function setPurchase(\App\FrontBundle\Entity\Purchase $purchase = null)
    {
        $this->purchase = $purchase;
    
        return $this;
    }

    /**
     * Get purchase
     *
     * @return \App\FrontBundle\Entity\Purchase
     */
    public function getPurchase()
    {
        return $this->purchase;
    }
}

<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CartItem
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class CartItem
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
     * @var Cart
     * 
     * @ORM\ManyToOne(targetEntity="Cart", inversedBy="items")
     */
    private $cart;

    /**
     * @var StockEntry
     * 
     * @ORM\ManyToOne(targetEntity="StockEntry")
     */
    private $entry;

    
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
     * @var \DateTime
     *
     * @ORM\Column(name="addedOn", type="datetime")
     */
    private $addedOn;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /*
     * Constructor
     */
    public function __construct() {
        $this->addedOn = new \DateTime('now');
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
     * Set price
     *
     * @param float $price
     *
     * @return CartItem
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
     * Set addedOn
     *
     * @param \DateTime $addedOn
     *
     * @return CartItem
     */
    public function setAddedOn($addedOn)
    {
        $this->addedOn = $addedOn;

        return $this;
    }

    /**
     * Get addedOn
     *
     * @return \DateTime
     */
    public function getAddedOn()
    {
        return $this->addedOn;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return CartItem
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
     * Set cart
     *
     * @param \App\FrontBundle\Entity\Cart $cart
     *
     * @return CartItem
     */
    public function setCart(\App\FrontBundle\Entity\Cart $cart = null)
    {
        $this->cart = $cart;

        return $this;
    }

    /**
     * Get cart
     *
     * @return \App\FrontBundle\Entity\Cart
     */
    public function getCart()
    {
        return $this->cart;
    }

    /**
     * Set entry
     *
     * @param \App\FrontBundle\Entity\StockEntry $entry
     *
     * @return CartItem
     */
    public function setEntry(\App\FrontBundle\Entity\StockEntry $entry = null)
    {
        $this->entry = $entry;

        return $this;
    }

    /**
     * Get entry
     *
     * @return \App\FrontBundle\Entity\StockEntry
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return CartItem
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
}

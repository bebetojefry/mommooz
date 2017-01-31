<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Cart
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
     * @ORM\OneToOne(targetEntity="User", inversedBy="Cart")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="session_id", type="string", length=255)
     */
    private $sessionId;

    /**
     * @var ArrayCollection|CartItem[]
     *
     * @ORM\OneToMany(targetEntity="CartItem", mappedBy="cart")
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
     * Set sessionId
     *
     * @param string $sessionId
     *
     * @return Cart
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    /**
     * Get sessionId
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
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
     * @param \App\FrontBundle\Entity\CartItem $item
     *
     * @return Cart
     */
    public function addItem(\App\FrontBundle\Entity\CartItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \App\FrontBundle\Entity\CartItem $item
     */
    public function removeItem(\App\FrontBundle\Entity\CartItem $item)
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
     * Clear items
     * 
     * @return Cart
     */
    public function clear()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
        
        return $this;
    }

    /**
     * Set user
     *
     * @param \App\FrontBundle\Entity\User $user
     *
     * @return Cart
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
    
    public function inCart($entry){
        $val = null;
        foreach($this->getItems() as $item){
            if($item->getEntry()->getId() == $entry->getId()){
                $val = $item;
            }
        }
        
        return $val;
    }
    
    public function getPrice(){
        $price = 0;
        foreach($this->getItems() as $item){
            $price += $item->getQuantity()*$item->getPrice();
        }
        
        return $price;
    }
}

<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WishList
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class WishList
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
     * @ORM\OneToOne(targetEntity="User", inversedBy="Wishlist")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="sessionId", type="string", length=255)
     */
    private $sessionId;
    
    /**
     * @var ArrayCollection|WishListItem[]
     *
     * @ORM\OneToMany(targetEntity="WishListItem", mappedBy="wishlist")
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
     * @return WishList
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
     * Set user
     *
     * @param \App\FrontBundle\Entity\User $user
     *
     * @return WishList
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
     * Add item
     *
     * @param \App\FrontBundle\Entity\WishListItem $item
     *
     * @return WishList
     */
    public function addItem(\App\FrontBundle\Entity\WishListItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \App\FrontBundle\Entity\WishListItem $item
     */
    public function removeItem(\App\FrontBundle\Entity\WishListItem $item)
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
    
    public function inWishList($entry){
        $val = null;
        foreach($this->getItems() as $item){
            if($item->getEntry()->getId() == $entry->getId()){
                $val = $item;
            }
        }
        
        return $val;
    }
}

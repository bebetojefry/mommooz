<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WishListItem
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class WishListItem
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
     * @var WishList
     * 
     * @ORM\ManyToOne(targetEntity="WishList", inversedBy="items")
     */
    private $wishlist;

    /**
     * @var StockEntry
     * 
     * @ORM\ManyToOne(targetEntity="StockEntry")
     */
    private $entry;


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
     * Set wishlist
     *
     * @param \App\FrontBundle\Entity\WishList $wishlist
     *
     * @return WishListItem
     */
    public function setWishlist(\App\FrontBundle\Entity\WishList $wishlist = null)
    {
        $this->wishlist = $wishlist;

        return $this;
    }

    /**
     * Get wishlist
     *
     * @return \App\FrontBundle\Entity\WishList
     */
    public function getWishlist()
    {
        return $this->wishlist;
    }

    /**
     * Set entry
     *
     * @param \App\FrontBundle\Entity\StockEntry $entry
     *
     * @return WishListItem
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
}

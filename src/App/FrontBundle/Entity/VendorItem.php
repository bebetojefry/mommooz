<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VendorItem
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class VendorItem
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
     * @var Vendor
     * 
     * @ORM\ManyToOne(targetEntity="Vendor", inversedBy="items")
     * @ORM\JoinColumn(name="vendor")
     */
    private $vendor;

    /**
     * @ORM\ManyToOne(targetEntity="Item")
     * @ORM\JoinColumn(name="item", referencedColumnName="id")
     */
    private $item;

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
     * Set status
     *
     * @param boolean $status
     *
     * @return VendorItem
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
     * @return VendorItem
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
     * Set item
     *
     * @param \App\FrontBundle\Entity\Item $item
     *
     * @return VendorItem
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
}

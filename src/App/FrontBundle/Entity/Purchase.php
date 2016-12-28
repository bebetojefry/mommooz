<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Purchase
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Purchase
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
     * @var Consumer
     * 
     * @ORM\ManyToOne(targetEntity="Consumer", inversedBy="orders")
     */
    private $consumer;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="purchased_on", type="datetime")
     */
    private $purchasedOn;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity="Address")
     * @ORM\JoinColumn(name="deliver_to", referencedColumnName="id")
     */
    private $deliverTo;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delivered_on", type="datetime", nullable=true)
     */
    private $deliveredOn;

    /**
     * @var string
     *
     * @ORM\Column(name="delivered_by", type="string", length=255, nullable=true)
     */
    private $deliveredBy;
    
    /**
     * @var ArrayCollection|PurchaseItem[]
     *
     * @ORM\OneToMany(targetEntity="PurchaseItem", mappedBy="purchase")
     */
    private $items;

    /*
     * constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set purchasedOn
     *
     * @param \DateTime $purchasedOn
     *
     * @return Purchase
     */
    public function setPurchasedOn($purchasedOn)
    {
        $this->purchasedOn = $purchasedOn;

        return $this;
    }

    /**
     * Get purchasedOn
     *
     * @return \DateTime
     */
    public function getPurchasedOn()
    {
        return $this->purchasedOn;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Purchase
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
     * Set deliveredOn
     *
     * @param \DateTime $deliveredOn
     *
     * @return Purchase
     */
    public function setDeliveredOn($deliveredOn)
    {
        $this->deliveredOn = $deliveredOn;

        return $this;
    }

    /**
     * Get deliveredOn
     *
     * @return \DateTime
     */
    public function getDeliveredOn()
    {
        return $this->deliveredOn;
    }

    /**
     * Set deliveredBy
     *
     * @param string $deliveredBy
     *
     * @return Purchase
     */
    public function setDeliveredBy($deliveredBy)
    {
        $this->deliveredBy = $deliveredBy;

        return $this;
    }

    /**
     * Get deliveredBy
     *
     * @return string
     */
    public function getDeliveredBy()
    {
        return $this->deliveredBy;
    }

    /**
     * Set consumer
     *
     * @param \App\FrontBundle\Entity\Consumer $consumer
     *
     * @return Purchase
     */
    public function setConsumer(\App\FrontBundle\Entity\Consumer $consumer = null)
    {
        $this->consumer = $consumer;

        return $this;
    }

    /**
     * Get consumer
     *
     * @return \App\FrontBundle\Entity\Consumer
     */
    public function getConsumer()
    {
        return $this->consumer;
    }

    /**
     * Add item
     *
     * @param \App\FrontBundle\Entity\PurchaseItem $item
     *
     * @return Purchase
     */
    public function addItem(\App\FrontBundle\Entity\PurchaseItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \App\FrontBundle\Entity\PurchaseItem $item
     */
    public function removeItem(\App\FrontBundle\Entity\PurchaseItem $item)
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
     * Set deliverTo
     *
     * @param \App\FrontBundle\Entity\Address $deliverTo
     *
     * @return Purchase
     */
    public function setDeliverTo(\App\FrontBundle\Entity\Address $deliverTo = null)
    {
        $this->deliverTo = $deliverTo;

        return $this;
    }

    /**
     * Get deliverTo
     *
     * @return \App\FrontBundle\Entity\Address
     */
    public function getDeliverTo()
    {
        return $this->deliverTo;
    }
}

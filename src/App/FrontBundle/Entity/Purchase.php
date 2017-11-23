<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Purchase
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\Column(name="purchased_on", type="datetime", nullable=true)
     */
    private $purchasedOn;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expected_on", type="datetime", nullable=true)
     */
    private $expectedOn;
    
    /**
     * @ORM\ManyToOne(targetEntity="Address")
     * @ORM\JoinColumn(name="deliver_to", referencedColumnName="id")
     */
    private $deliverTo;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delivered_on", type="datetime", nullable=true, nullable=true)
     */
    private $deliveredOn;

    /**
     * @var string
     *
     * @ORM\Column(name="delivered_by", type="string", length=255, nullable=true)
     */
    private $deliveredBy;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cancelled_on", type="datetime", nullable=true)
     */
    private $cancelledOn;
    
    /**
     * @var ArrayCollection|PurchaseItem[]
     *
     * @ORM\OneToMany(targetEntity="PurchaseItem", mappedBy="purchase")
     */
    private $items;
    
    /**
     * @var RewardUse
     *
     * @ORM\OneToOne(targetEntity="RewardUse", mappedBy="purchase")
     */
    private $reward_use;
    
    /**
     * @var string
     *
     * @ORM\Column(name="method", type="string", length=255, nullable=true)
     */
    private $method;

    /**
     * @var float
     *
     * @ORM\Column(name="delivery_charge", type="float", nullable=true, options={"default"=0})
     */
    private $deliveryCharge;

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
        if($this->deliveredOn == null) {
            return new \DateTime('now');
        }
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

    /**
     * Set expectedOn
     *
     * @param \DateTime $expectedOn
     *
     * @return Purchase
     */
    public function setExpectedOn($expectedOn)
    {
        $this->expectedOn = $expectedOn;
    
        return $this;
    }

    /**
     * Get expectedOn
     *
     * @return \DateTime
     */
    public function getExpectedOn()
    {
        if($this->expectedOn == null) {
            return new \DateTime('now');
        }
        return $this->expectedOn;
    }

    /**
     * Set cancelledOn
     *
     * @param \DateTime $cancelledOn
     *
     * @return Purchase
     */
    public function setCancelledOn($cancelledOn)
    {
        $this->cancelledOn = $cancelledOn;
    
        return $this;
    }

    /**
     * Get cancelledOn
     *
     * @return \DateTime
     */
    public function getCancelledOn()
    {
        if($this->cancelledOn == null) {
            return new \DateTime('now');
        }
        return $this->cancelledOn;
    }
    
    public function getTotalPrice(){
        $total = 0;
        foreach($this->items as $item){
            $total += $item->getPrice();
        }
        
        return $total;
    }

    /**
     * Set rewardUse
     *
     * @param \App\FrontBundle\Entity\RewardUse $rewardUse
     *
     * @return Purchase
     */
    public function setRewardUse(\App\FrontBundle\Entity\RewardUse $rewardUse = null)
    {
        $this->reward_use = $rewardUse;
    
        return $this;
    }

    /**
     * Get rewardUse
     *
     * @return \App\FrontBundle\Entity\RewardUse
     */
    public function getRewardUse()
    {
        return $this->reward_use;
    }

    /**
     * Set method
     *
     * @param string $method
     *
     * @return Purchase
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * Get method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }


    /**
     * @ORM\PreUpdate
     */
    public function setDates()
    {
        if($this->status == 3){
            $this->deliveredOn = null;
            $this->cancelledOn = null;
        } elseif($this->status == 4){
            $this->cancelledOn = null;
        } elseif($this->status == 5){
            $this->deliveredOn = null;
            $this->expectedOn = null;
            $this->deliveredBy = null;
        } else {
            $this->deliveredOn = null;
            $this->cancelledOn = null;
            $this->expectedOn = null;
            $this->deliveredBy = null;
        }
    }

    /**
     * Set deliveryCharge
     *
     * @param float $deliveryCharge
     *
     * @return Purchase
     */
    public function setDeliveryCharge($deliveryCharge)
    {
        $this->deliveryCharge = $deliveryCharge;

        return $this;
    }

    /**
     * Get deliveryCharge
     *
     * @return float
     */
    public function getDeliveryCharge()
    {
        return $this->deliveryCharge;
    }
}

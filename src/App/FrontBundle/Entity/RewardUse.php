<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RewardUse
 *
 * @ORM\Table(name="reward_use")
 * @ORM\Entity
 */
class RewardUse
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
     * @var Purchase
     * 
     * @ORM\OneToOne(targetEntity="Purchase", inversedBy="reward_use")
     */
    private $purchase;

     /**
     * @ORM\ManyToOne(targetEntity="Consumer")
     * @ORM\JoinColumn(name="consumer", referencedColumnName="id")
     */
    private $consumer;

    /**
     * @var float
     *
     * @ORM\Column(name="points", type="float")
     */
    private $points;

    /**
     * @var float
     *
     * @ORM\Column(name="money", type="float")
     */
    private $money;


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
     * Set purchase
     *
     * @param integer $purchase
     *
     * @return RewardUse
     */
    public function setPurchase($purchase)
    {
        $this->purchase = $purchase;
    
        return $this;
    }

    /**
     * Get purchase
     *
     * @return integer
     */
    public function getPurchase()
    {
        return $this->purchase;
    }

    /**
     * Set consumer
     *
     * @param integer $consumer
     *
     * @return RewardUse
     */
    public function setConsumer($consumer)
    {
        $this->consumer = $consumer;
    
        return $this;
    }

    /**
     * Get consumer
     *
     * @return integer
     */
    public function getConsumer()
    {
        return $this->consumer;
    }

    /**
     * Set points
     *
     * @param float $points
     *
     * @return RewardUse
     */
    public function setPoints($points)
    {
        $this->points = $points;
    
        return $this;
    }

    /**
     * Get points
     *
     * @return float
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set money
     *
     * @param float $money
     *
     * @return RewardUse
     */
    public function setMoney($money)
    {
        $this->money = $money;
    
        return $this;
    }

    /**
     * Get money
     *
     * @return float
     */
    public function getMoney()
    {
        return $this->money;
    }
}


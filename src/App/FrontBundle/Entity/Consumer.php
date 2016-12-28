<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Consumer
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\FrontBundle\Entity\UserRepository")
 */
class Consumer extends User
{  
    /**
     * @var ArrayCollection|Purchase[]
     *
     * @ORM\OneToMany(targetEntity="Purchase", mappedBy="consumer")
     */
    private $orders;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return array('ROLE_CONSUMER', 'ROLE_USER');
    }

    /**
     * Add order
     *
     * @param \App\FrontBundle\Entity\Purchase $order
     *
     * @return Consumer
     */
    public function addOrder(\App\FrontBundle\Entity\Purchase $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \App\FrontBundle\Entity\Purchase $order
     */
    public function removeOrder(\App\FrontBundle\Entity\Purchase $order)
    {
        $this->orders->removeElement($order);
    }

    /**
     * Get orders
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrders()
    {
        return $this->orders;
    }
}

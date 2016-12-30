<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vendor
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\FrontBundle\Entity\UserRepository")
 */
class Vendor extends User
{
    
    /**
     * @var ArrayCollection|Stock[]
     *
     * @ORM\OneToMany(targetEntity="Stock", mappedBy="vendor")
     */
    private $stocks;
    
    /**
     * Get roles
     *
     * @return array
     */
    public function getRoles()
    {
        return array('ROLE_VENDOR', 'ROLE_USER', 'ROLE_'.strtoupper($this->getUsername()));
    }

    /**
     * Add stock
     *
     * @param \App\FrontBundle\Entity\Stock $stock
     *
     * @return Vendor
     */
    public function addStock(\App\FrontBundle\Entity\Stock $stock)
    {
        $this->stocks[] = $stock;
    
        return $this;
    }

    /**
     * Remove stock
     *
     * @param \App\FrontBundle\Entity\Stock $stock
     */
    public function removeStock(\App\FrontBundle\Entity\Stock $stock)
    {
        $this->stocks->removeElement($stock);
    }

    /**
     * Get stocks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStocks()
    {
        return $this->stocks;
    }
    
    public function getInStockEntries(){
        $items = array();
        foreach ($this->getStocks() as $stock){
            $items = array_merge($items, $stock->getInStockEntries());
        }
        
        return $items;
    }
}

<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\FrontBundle\Entity\Category;
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
     * @ORM\OneToMany(targetEntity="Stock", mappedBy="vendor", cascade={"persist"})
     */
    private $stocks;
    
    /**
     * @var ArrayCollection|VendorItem[]
     *
     * @ORM\OneToMany(targetEntity="VendorItem", mappedBy="vendor")
     */
    private $items;
    
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

    /**
     * Add item
     *
     * @param \App\FrontBundle\Entity\VendorItem $item
     *
     * @return Vendor
     */
    public function addItem(\App\FrontBundle\Entity\VendorItem $item)
    {
        $this->items[] = $item;
    
        return $this;
    }

    /**
     * Remove item
     *
     * @param \App\FrontBundle\Entity\VendorItem $item
     */
    public function removeItem(\App\FrontBundle\Entity\VendorItem $item)
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
    
    public function hasItem($item) {
        foreach($this->items as $it){
            if($it->getItem()->getId() == $item->getId()){
                return true;
            }
        }
        
        return false;
    }
    
    public function getRealItems(Category $category = null)
    {
        $realItems = array();
        foreach($this->items as $item){
            if($category == null){
                $realItems[] = $item->getItem();
            } else if($item->getItem()->inCategory($category)){
                $realItems[] = $item->getItem();
            }
        }
        
        return $realItems;
    }
}

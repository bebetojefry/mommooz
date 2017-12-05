<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StockEntry
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class StockEntry
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
     * @var Item
     * 
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="stock_entries")
     */
    private $item;
    
    /**
     * @var Variant
     * 
     * @ORM\ManyToOne(targetEntity="ItemVariant", inversedBy="stock_entries")
     */
    private $variant;
    
    /**
     * @var Stock
     * 
     * @ORM\ManyToOne(targetEntity="Stock", inversedBy="items")
     */
    private $stock;

    /**
     * @var integer
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @var float
     *
     * @ORM\Column(name="mrp", type="float")
     */
    private $mrp;
    
    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var float
     *
     * @ORM\Column(name="actual_price", type="float")
     */
    private $actualPrice;

    /**
     * @var ArrayCollection|Offer[]
     *
     * @ORM\ManyToMany(targetEntity="Offer")
     */
    private $offers;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;
    
    /**
     * @var ArrayCollection|Keyword[]
     *
     * @ORM\ManyToMany(targetEntity="Keyword")
     */
    private $keywords;
    
    /**
     * @var ArrayCollection|StockPurchase[]
     *
     * @ORM\OneToMany(targetEntity="StockPurchase", mappedBy="stockItem")
     */
    private $purchases;
    
    /**
     * @var ArrayCollection|PurchaseItem[]
     *
     * @ORM\OneToMany(targetEntity="PurchaseItem", mappedBy="entry")
     */
    private $orders;
    
    /**
     * @var ArrayCollection|ItemView[]
     *
     * @ORM\OneToMany(targetEntity="ItemView", mappedBy="entry")
     */
    private $ItemViews;
    
    private $state;
    
    private $commtype;
    
    private $commvalue;

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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return StockEntry
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    
        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return StockEntry
     */
    public function setPrice($price)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set actualPrice
     *
     * @param float $actualPrice
     *
     * @return StockEntry
     */
    public function setActualPrice($actualPrice)
    {
        $this->actualPrice = $actualPrice;
    
        return $this;
    }

    /**
     * Get actualPrice
     *
     * @return float
     */
    public function getActualPrice()
    {
        return $this->actualPrice;
    }

    /**
     * Set offers
     *
     * @param string $offers
     *
     * @return StockEntry
     */
    public function setOffers($offers)
    {
        $this->offers = $offers;
    
        return $this;
    }

    /**
     * Get offers
     *
     * @return string
     */
    public function getOffers()
    {
        return $this->offers;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return StockEntry
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
     * Set item
     *
     * @param \App\FrontBundle\Entity\Item $item
     *
     * @return StockEntry
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

    /**
     * Set stock
     *
     * @param \App\FrontBundle\Entity\Stock $stock
     *
     * @return StockEntry
     */
    public function setStock(\App\FrontBundle\Entity\Stock $stock = null)
    {
        $this->stock = $stock;
    
        return $this;
    }

    /**
     * Get stock
     *
     * @return \App\FrontBundle\Entity\Stock
     */
    public function getStock()
    {
        return $this->stock;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->offers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->keywords = new \Doctrine\Common\Collections\ArrayCollection();
        $this->purchases = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ItemViews = new \Doctrine\Common\Collections\ArrayCollection();
        $this->orders = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add offer
     *
     * @param \App\FrontBundle\Entity\Offer $offer
     *
     * @return StockEntry
     */
    public function addOffer(\App\FrontBundle\Entity\Offer $offer)
    {
        $this->offers[] = $offer;
    
        return $this;
    }

    /**
     * Remove offer
     *
     * @param \App\FrontBundle\Entity\Offer $offer
     */
    public function removeOffer(\App\FrontBundle\Entity\Offer $offer)
    {
        $this->offers->removeElement($offer);
    }

    /**
     * Set variant
     *
     * @param \App\FrontBundle\Entity\ItemVariant $variant
     *
     * @return StockEntry
     */
    public function setVariant(\App\FrontBundle\Entity\ItemVariant $variant = null)
    {
        $this->variant = $variant;

        return $this;
    }

    /**
     * Get variant
     *
     * @return \App\FrontBundle\Entity\ItemVariant
     */
    public function getVariant()
    {
        return $this->variant;
    }
    
    public function setState($state){
        $this->state = $state;
        
        return $this;
    }
    
    public function getState(){
        return $this->state;
    }
    
    public function setCommtype($commtype){
       $this->commtype = $commtype;
        
        return $this;
    }
    
    public function getCommtype(){
        return $this->commtype;
    }
    
    public function setCommvalue($commvalue){
       $this->commvalue = $commvalue;
        
        return $this;
    }
    
    public function getCommvalue(){
        return $this->commvalue;
    }

    /**
     * Add keyword
     *
     * @param \App\FrontBundle\Entity\Keyword $keyword
     *
     * @return StockEntry
     */
    public function addKeyword(\App\FrontBundle\Entity\Keyword $keyword)
    {
        $this->keywords[] = $keyword;

        return $this;
    }

    /**
     * Remove keyword
     *
     * @param \App\FrontBundle\Entity\Keyword $keyword
     */
    public function removeKeyword(\App\FrontBundle\Entity\Keyword $keyword)
    {
        $this->keywords->removeElement($keyword);
    }

    /**
     * Get keywords
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Add purchase
     *
     * @param \App\FrontBundle\Entity\StockPurchase $purchase
     *
     * @return StockEntry
     */
    public function addPurchase(\App\FrontBundle\Entity\StockPurchase $purchase)
    {
        $this->purchases[] = $purchase;
    
        return $this;
    }

    /**
     * Remove purchase
     *
     * @param \App\FrontBundle\Entity\StockPurchase $purchase
     */
    public function removePurchase(\App\FrontBundle\Entity\StockPurchase $purchase)
    {
        $this->purchases->removeElement($purchase);
    }

    /**
     * Get purchases
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPurchases()
    {
        return $this->purchases;
    }
    
    public function getInStock()
    {
        $in_stock = $this->quantity;
        foreach($this->purchases as $purchase) {
            $in_stock -= $purchase->getQuantity();
        }
        
        return $in_stock;
    }

    /**
     * Add itemView
     *
     * @param \App\FrontBundle\Entity\ItemView $itemView
     *
     * @return StockEntry
     */
    public function addItemView(\App\FrontBundle\Entity\ItemView $itemView)
    {
        $this->ItemViews[] = $itemView;
    
        return $this;
    }

    /**
     * Remove itemView
     *
     * @param \App\FrontBundle\Entity\ItemView $itemView
     */
    public function removeItemView(\App\FrontBundle\Entity\ItemView $itemView)
    {
        $this->ItemViews->removeElement($itemView);
    }

    /**
     * Get itemViews
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getItemViews()
    {
        return $this->ItemViews;
    }

    /**
     * Set mrp
     *
     * @param float $mrp
     *
     * @return StockEntry
     */
    public function setMrp($mrp)
    {
        $this->mrp = $mrp;

        return $this;
    }

    /**
     * Get mrp
     *
     * @return float
     */
    public function getMrp()
    {
        return $this->mrp;
    }

    /**
     * Add order
     *
     * @param \App\FrontBundle\Entity\PurchaseItem $order
     *
     * @return StockEntry
     */
    public function addOrder(\App\FrontBundle\Entity\PurchaseItem $order)
    {
        $this->orders[] = $order;

        return $this;
    }

    /**
     * Remove order
     *
     * @param \App\FrontBundle\Entity\PurchaseItem $order
     */
    public function removeOrder(\App\FrontBundle\Entity\PurchaseItem $order)
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
    
    public function onOffer(){
        foreach($this->offers as $offer){
            $dt = new \DateTime('now'); $dt->setTime(0, 0, 0);
            $interval = $offer->getExpiry()->diff($dt);
            if($offer->getStatus() && $interval->format('%d') >= 0){
                return true;
            }
        }
        
        return false;
    }
    
    public function isNew(){
        return false;
    }
    
    public function getFilterData(){
        $data = array();
        $category = $this->getItem()->getProduct()->getCategory();
        $data['item_id'] = $this->getItem()->getId();
        $data['cat'] = array('id' => $category->getId(), 'name' => $category->getCategoryName());
        
        $data['tagged_cats'] = array();
        foreach($this->getItem()->getCategories() as $cat) {
            if(!in_array($cat->getId(), array($category->getId(), 446, 447))) {
                $data['tagged_cats'][] = array('id' => $cat->getId(), 'name' => $cat->getCategoryName());
            }
        }
        
        $brand = $this->getItem()->getBrand();
        if($brand->isVisible()){
            $brandName = $brand->getName() == '' ? 'Brandless' : $brand->getName();
            $data['brand'] = array('id' => $brand->getId(), 'name' => $brandName);
        }
        
        $data['offer'] = $this->onOffer();
        $data['new'] = $this->isNew();
        
        return $data;
    }

    public function isEnabled(){
        $parent_cat_enabled = true;
        $cat = $this->getItem()->getProduct()->getCategory();
        while($cat->getParent()){
            $cat = $cat->getParent();
            if(!$cat->getStatus()){
                $parent_cat_enabled = false;
                break;
            }
        }

        return ($this->getStatus() && $this->getItem()->getStatus() && $this->getItem()->getProduct()->getStatus() && $this->getItem()->getProduct()->getCategory()->getStatus() && $parent_cat_enabled);
    }
}

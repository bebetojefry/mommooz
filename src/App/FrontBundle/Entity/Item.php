<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\FrontBundle\Entity\Keyword;

/**
 * Item
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Item
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
    /**
     * @var Product
     * 
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="items")
     */
    private $product;

    /**
     * @var Brand
     * 
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="items")
     */
    private $brand;

    /**
     * @var ArrayCollection|Keyword[]
     *
     * @ORM\ManyToMany(targetEntity="Keyword")
     */
    private $keywords;

    /**
     * @var ArrayCollection|Offer[]
     *
     * @ORM\ManyToMany(targetEntity="Offer")
     */
    private $offers;

    /**
     * @var ArrayCollection|Specification[]
     *
     * @ORM\ManyToMany(targetEntity="Specification", orphanRemoval=true, cascade={"persist"})
     */
    private $specifications;
    
    /**
     * @var ArrayCollection|Image[]
     *
     * @ORM\ManyToMany(targetEntity="Image", orphanRemoval=true)
     */
    private $images;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;
    
    /**
     * @var string
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var ArrayCollection|ItemVariant[]
     *
     * @ORM\OneToMany(targetEntity="ItemVariant", mappedBy="item", cascade={"persist"})
     */
    private $variants;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="comm_type", type="integer", nullable=true)
     */
    private $comm_type;

    /**
     * @var string
     *
     * @ORM\Column(name="comm_value", type="float", nullable=true)
     */
    private $comm_value;
    
    /**
     * @var ArrayCollection|StockEntry[]
     *
     * @ORM\OneToMany(targetEntity="StockEntry", mappedBy="item")
     */
    private $stock_entries;
    
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
     * Set product
     *
     * @param integer $product
     *
     * @return Item
     */
    public function setProduct($product)
    {
        $this->product = $product;
    
        return $this;
    }

    /**
     * Get product
     *
     * @return integer
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set brand
     *
     * @param integer $brand
     *
     * @return Item
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
    
        return $this;
    }

    /**
     * Get brand
     *
     * @return integer
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     *
     * @return Item
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    
        return $this;
    }

    /**
     * Get keywords
     *
     * @return string
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * Set offers
     *
     * @param string $offers
     *
     * @return Item
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
     * Set specifications
     *
     * @param string $specifications
     *
     * @return Item
     */
    public function setSpecifications($specifications)
    {
        $this->specifications = $specifications;
    
        return $this;
    }

    /**
     * Get specifications
     *
     * @return string
     */
    public function getSpecifications()
    {
        return $this->specifications;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Item
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
     * Constructor
     */
    public function __construct()
    {
        $this->keywords = new \Doctrine\Common\Collections\ArrayCollection();
        $this->offers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->specifications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->variants = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add keyword
     *
     * @param Keyword $keyword
     *
     * @return Item
     */
    public function addKeyword(Keyword $keyword)
    {
        $this->keywords[] = $keyword;
    
        return $this;
    }

    /**
     * Remove keyword
     *
     * @param Keyword $keyword
     */
    public function removeKeyword(Keyword $keyword)
    {
        $this->keywords->removeElement($keyword);
    }

    /**
     * Add offer
     *
     * @param \App\FrontBundle\Entity\Offer $offer
     *
     * @return Item
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
     * Add specification
     *
     * @param \App\FrontBundle\Entity\Specification $specification
     *
     * @return Item
     */
    public function addSpecification(\App\FrontBundle\Entity\Specification $specification)
    {
        $this->specifications[] = $specification;
    
        return $this;
    }

    /**
     * Remove specification
     *
     * @param \App\FrontBundle\Entity\Specification $specification
     */
    public function removeSpecification(\App\FrontBundle\Entity\Specification $specification)
    {
        $this->specifications->removeElement($specification);
    }
    
    /**
     * Add image
     *
     * @param \App\FrontBundle\Entity\Image $image
     *
     * @return Product
     */
    public function addImage(\App\FrontBundle\Entity\Image $image)
    {
        $this->images[] = $image;
    
        return $this;
    }

    /**
     * Remove image
     *
     * @param \App\FrontBundle\Entity\Image $image
     */
    public function removeImage(\App\FrontBundle\Entity\Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Item
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Item
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Item
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
     * Add variant
     *
     * @param \App\FrontBundle\Entity\ItemVariant $variant
     *
     * @return Item
     */
    public function addVariant(\App\FrontBundle\Entity\ItemVariant $variant)
    {
        $this->variants[] = $variant;

        return $this;
    }

    /**
     * Remove variant
     *
     * @param \App\FrontBundle\Entity\ItemVariant $variant
     */
    public function removeVariant(\App\FrontBundle\Entity\ItemVariant $variant)
    {
        $this->variants->removeElement($variant);
    }

    /**
     * Get variants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * Set commType
     *
     * @param integer $commType
     *
     * @return Item
     */
    public function setCommType($commType)
    {
        $this->comm_type = $commType;
    
        return $this;
    }

    /**
     * Get commType
     *
     * @return integer
     */
    public function getCommType()
    {
        return $this->comm_type;
    }

    /**
     * Set commValue
     *
     * @param float $commValue
     *
     * @return Item
     */
    public function setCommValue($commValue)
    {
        $this->comm_value = $commValue;
    
        return $this;
    }

    /**
     * Get commValue
     *
     * @return float
     */
    public function getCommValue()
    {
        return $this->comm_value;
    }

    /**
     * Add stockEntry
     *
     * @param \App\FrontBundle\Entity\StockEntry $stockEntry
     *
     * @return Item
     */
    public function addStockEntry(\App\FrontBundle\Entity\StockEntry $stockEntry)
    {
        $this->stock_entries[] = $stockEntry;
    
        return $this;
    }

    /**
     * Remove stockEntry
     *
     * @param \App\FrontBundle\Entity\StockEntry $stockEntry
     */
    public function removeStockEntry(\App\FrontBundle\Entity\StockEntry $stockEntry)
    {
        $this->stock_entries->removeElement($stockEntry);
    }

    /**
     * Get stockEntries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStockEntries()
    {
        return $this->stock_entries;
    }
}

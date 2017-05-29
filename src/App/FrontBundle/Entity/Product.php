<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\FrontBundle\Entity\ProductRepository")
 */
class Product
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
     * @var Category
     * 
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products"))
     */
    private $category;

    /**
     * @var ArrayCollection|Keyword[]
     *
     * @ORM\ManyToMany(targetEntity="Keyword"))
     */
    private $keywords;

    /**
     * @var ArrayCollection|Item[]
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="product")
     */
    private $items;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="deliverable", type="integer", nullable=true)
     */
    private $deliverable;
    
    /**
     * @var ArrayCollection|Region[]
     *
     * @ORM\ManyToMany(targetEntity="Region")
     */
    private $regions;
    
    /**
     * @var ArrayCollection|Category[]
     *
     * @ORM\ManyToMany(targetEntity="Category")
     */
    private $categories;
    
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
     * Set name
     *
     * @param string $name
     *
     * @return Product
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
     * @return Product
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
     * Set category
     *
     * @param integer $category
     *
     * @return Product
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return integer
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     *
     * @return Product
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
     * Set status
     *
     * @param boolean $status
     *
     * @return Product
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
        $this->keywords = new ArrayCollection();
        $this->regions = new ArrayCollection();
    }

    /**
     * Add keyword
     *
     * @param \App\FrontBundle\Entity\Keyword $keyword
     *
     * @return Product
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
     * Add item
     *
     * @param \App\FrontBundle\Entity\Item $item
     *
     * @return Product
     */
    public function addItem(\App\FrontBundle\Entity\Item $item)
    {
        $this->items[] = $item;
    
        return $this;
    }

    /**
     * Remove item
     *
     * @param \App\FrontBundle\Entity\Item $item
     */
    public function removeItem(\App\FrontBundle\Entity\Item $item)
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
     * Set deliverable
     *
     * @param integer $deliverable
     *
     * @return Product
     */
    public function setDeliverable($deliverable)
    {
        $this->deliverable = $deliverable;

        return $this;
    }

    /**
     * Get deliverable
     *
     * @return integer
     */
    public function getDeliverable()
    {
        return $this->deliverable;
    }

    /**
     * Add region
     *
     * @param \App\FrontBundle\Entity\Region $region
     *
     * @return Product
     */
    public function addRegion(\App\FrontBundle\Entity\Region $region)
    {
        $this->regions[] = $region;

        return $this;
    }

    /**
     * Remove region
     *
     * @param \App\FrontBundle\Entity\Region $region
     */
    public function removeRegion(\App\FrontBundle\Entity\Region $region)
    {
        $this->regions->removeElement($region);
    }

    /**
     * Get regions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegions()
    {
        return $this->regions;
    }
    
    /**
     * Set regions
     *
     * @param \Doctrine\Common\Collections\Collection $regions
     * 
     * @return Product
     */
    public function setRegions($regions)
    {
        $this->regions = $regions;
        
        return $this;
    }

    /**
     * Add category
     *
     * @param \App\FrontBundle\Entity\Category $category
     *
     * @return Product
     */
    public function addCategory(\App\FrontBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \App\FrontBundle\Entity\Category $category
     */
    public function removeCategory(\App\FrontBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }
    
    /**
     * Set categories
     *
     * @param \Doctrine\Common\Collections\Collection $categories
     * 
     * @return Product
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;
        
        return $this;
    }
}

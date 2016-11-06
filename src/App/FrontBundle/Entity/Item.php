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
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="items"))
     */
    private $product;

    /**
     * @var Brand
     * 
     * @ORM\ManyToOne(targetEntity="Brand", inversedBy="items"))
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
     * @ORM\OneToMany(targetEntity="Specification", mappedBy="item")
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
}

<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\FrontBundle\Entity\Keyword;
use App\FrontBundle\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Category
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="App\FrontBundle\Entity\CategoryRepository")
 */
class Category
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
     * @ORM\Column(name="category_name", type="string", length=255)
     */
    private $categoryName;

    /**
     * @var State
     * 
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="childs"))
     */
    private $parent;

    /**
     * @var ArrayCollection|Keyword[]
     *
     * @ORM\ManyToMany(targetEntity="Keyword"))
     */
    private $keywords;

    /**
     * @var ArrayCollection|Category[]
     *
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     */
    private $childs;
    
    /**
     * @var ArrayCollection|Product[]
     *
     * @ORM\OneToMany(targetEntity="Product", mappedBy="category")
     */
    private $products;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

    /**
     * @var ArrayCollection|Image[]
     *
     * @ORM\ManyToMany(targetEntity="Image", orphanRemoval=true)
     */
    private $images;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="popular", type="boolean")
     */
    private $popular;
    
    /**
     * @var ArrayCollection|Item[]
     * 
     * @ORM\ManyToMany(targetEntity="Item", mappedBy="categories")
     */
    private $tagged_items;
    
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
     * Set categoryName
     *
     * @param string $categoryName
     *
     * @return Category
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * Get categoryName
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * Get categoryName
     *
     * @return string
     */
    public function getNameForRoute()
    {
        return str_replace(' ', '-', $this->categoryName);
    }

    /**
     * Set parent
     *
     * @param string $parent
     *
     * @return Category
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return string
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set keywords
     *
     * @param string $keywords
     *
     * @return Category
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
     * @return Category
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
        $this->childs = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->products = new ArrayCollection();
    }

    /**
     * Add keyword
     *
     * @param Keyword $keyword
     *
     * @return Category
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
     * Add child
     *
     * @param Category $child
     *
     * @return Category
     */
    public function addChild(Category $child)
    {
        $this->childs[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param Category $child
     */
    public function removeChild(Category $child)
    {
        $this->childs->removeElement($child);
    }

    /**
     * Get childs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * Add product
     *
     * @param \App\FrontBundle\Entity\Product $product
     *
     * @return Category
     */
    public function addProduct(\App\FrontBundle\Entity\Product $product)
    {
        $this->products[] = $product;
    
        return $this;
    }

    /**
     * Remove product
     *
     * @param \App\FrontBundle\Entity\Product $product
     */
    public function removeProduct(\App\FrontBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Add image
     *
     * @param \App\FrontBundle\Entity\Image $image
     *
     * @return Category
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
     * Set popular
     *
     * @param boolean $popular
     *
     * @return Category
     */
    public function setPopular($popular)
    {
        $this->popular = $popular;
    
        return $this;
    }

    /**
     * Get popular
     *
     * @return boolean
     */
    public function getPopular()
    {
        return $this->popular;
    }
    
    public function getAllProducts(\SplObjectStorage &$refs = null){
        $products = array();
        
        if($refs === null){
            $refs = new \SplObjectStorage();
        }
        
        foreach($this->products as $product){
            if(!$refs->contains($product)){
                $products[] = $product;
                $refs->attach($product);
            }
        }
        
        foreach ($this->getChilds() as $cat){
            $products = array_merge($products, $cat->getAllProducts($refs));
        }
        
        return $products;
    }
    
    public function getInStockEntries(\SplObjectStorage &$items = null){
        $instock = array();
        
        if($items === null){
            $items = new \SplObjectStorage();
        }
        
        foreach($this->products->toArray() as $product){
            foreach($product->getItems() as $item){
                if(!$items->contains($item)){
                    $instock = array_merge($instock, $item->getLowCostEntries());
                    $items->attach($item);
                }
            }
        }
        
        foreach($this->getTaggedItems() as $item){            
            if(!$items->contains($item)){
                $instock = array_merge($instock, $item->getLowCostEntries());
                $items->attach($item);
            }
        }
        
        foreach($this->childs as $cat){
            $instock = array_merge($instock, $cat->getInStockEntries($items));
        }
        
        return $instock;
    }
    
    public function getAllBrands(){
        $brands = array();
        
        foreach($this->getAllProducts() as $product){
            foreach($product->getItems() as $item){
                if(!isset($brands[$item->getBrand()->getId()])){
                    $brands[$item->getBrand()->getId()] = $item->getBrand();
                }
            }
        }
        
        return $brands;
    }
    
    public function getPicture(){
        $images = $this->getImages();
        $picture = 'data:image/jpeg;base64,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QFBAUEFAQAAUBBQEFQAAAVABQEFAQFBBUAFAQFBBUAAAFAQVAAUEFAQUBBQEFAQUBBQEFAQUBAUEFAQUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABQEFAQUARQEFAEUBBQEUAQUBBQEFAQUBBQEFAQUBBQEFAQUARQEFAQUBBQEFAQUARQEUAQUARQEUAQUBBQEFAQUBBQEFAQUARQEFABUAFQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAVAAAAAAAAAAAAABUAAAAAAAAAFQAAAAAAAAAAAAAAAAAAFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFAQUBBQEFAQUBBQEFAQUBBQEBQQUBBQEFAQVABQEFAQUBBQEFAQUBBQEFAQUBBQEFAQUBBQEFAQUBBQEFAQUBBUAFAQUBBQEFAQUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB//9k=';
        if($images->count() > 0){
            $picture = $images->first()->getImage();
        }
        
        return $picture;
    }
    
    public function getRealPicturePath($index = 0){
        $images = $this->getImages();
        $picture = 'data:image/jpeg;base64,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QFBAUEFAQAAUBBQEFQAAAVABQEFAQFBBUAFAQFBBUAAAFAQVAAUEFAQUBBQEFAQUBBQEFAQUBAUEFAQUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABQEFAQUARQEFAEUBBQEUAQUBBQEFAQUBBQEFAQUBBQEFAQUARQEFAQUBBQEFAQUARQEUAQUARQEUAQUBBQEFAQUBBQEFAQUARQEFABUAFQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAVAAAAAAAAAAAAABUAAAAAAAAAFQAAAAAAAAAAAAAAAAAAFAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAFAQUBBQEFAQUBBQEFAQUBBQEBQQUBBQEFAQVABQEFAQUBBQEFAQUBBQEFAQUBBQEFAQUBBQEFAQUBBQEFAQUBBUAFAQUBBQEFAQUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAB//9k=';
        if($image = $images->get($index)){
            $picture = $image->getImage();
            $index = $image->getId();
        }

        $decoded = explode(',', $picture);
        $filename = sprintf('uploads/category/%s_%s.jpg', $this->getId(), $index);
        if(!file_exists($filename)){
            file_put_contents($filename, base64_decode($decoded[1]));
        }

        return $filename;
    }
    
    public function hasChild(Category $child) {
        foreach($this->childs as $ch){
            if($ch->getId() == $child->getId()){
                return true;
                break;
            } else {
                if($ch->hasChild($child)){
                    return true;
                    break;
                }
            }
        }
        
        return false;
    }
    
    public function getRoot(){
        if($this->parent->getId() == 1){
            return $this;
        } else {
            if($this->parent){
                return $this->parent->getRoot();
            }
        }
       
        return null;
    }

    /**
     * Add taggedProduct
     *
     * @param \App\FrontBundle\Entity\Product $taggedProduct
     *
     * @return Category
     */
    public function addTaggedProduct(\App\FrontBundle\Entity\Product $taggedProduct)
    {
        $this->tagged_products[] = $taggedProduct;

        return $this;
    }

    /**
     * Remove taggedProduct
     *
     * @param \App\FrontBundle\Entity\Product $taggedProduct
     */
    public function removeTaggedProduct(\App\FrontBundle\Entity\Product $taggedProduct)
    {
        $this->tagged_products->removeElement($taggedProduct);
    }

    /**
     * Get taggedProducts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTaggedItems()
    {
        return $this->tagged_items;
    }
}

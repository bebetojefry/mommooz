<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Banner
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="banner_type", type="string")
 * @ORM\DiscriminatorMap({"offer" = "OfferBanner", "item" = "ItemBanner", "category" = "CategoryBanner"})
 * 
 */
abstract class Banner
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
     * @ORM\Column(name="banner_name", type="string", length=255)
     */
    private $banner_name;

    /**
     * @var ArrayCollection|Image[]
     *
     * @ORM\ManyToMany(targetEntity="Image", orphanRemoval=true)
     */
    private $images;    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set bannerName
     *
     * @param string $bannerName
     *
     * @return Banner
     */
    public function setBannerName($bannerName)
    {
        $this->banner_name = $bannerName;
    
        return $this;
    }

    /**
     * Get bannerName
     *
     * @return string
     */
    public function getBannerName()
    {
        return $this->banner_name;
    }

    /**
     * Add image
     *
     * @param \App\FrontBundle\Entity\Image $image
     *
     * @return Banner
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
    
    public function getDescription(){
        $value = null;
        switch(get_class($this)){
            case 'App\FrontBundle\Entity\CategoryBanner':
                $value = 'Category <br/><b>'.$this->getEntity()->getCategoryName().'</b>';
                break;
            case 'App\FrontBundle\Entity\ItemBanner';
                $value = 'Item <br/><b>'.$this->getEntity()->getName().'</b>';
                break;
            case 'App\FrontBundle\Entity\OfferBanner':
                $value = 'Offer <br/><b>'.$this->getEntity()->getName().'</b>';
                break;
        }
        
        return $value;
    }
}

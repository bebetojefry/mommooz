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
     * @var string
     *
     * @ORM\Column(name="banner_image", type="text")
     */
    private $banner_image;    


    /**
     * Get bannerId
     *
     * @return integer
     */
    public function getBannerId()
    {
        return $this->banner_id;
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
     * Set bannerImage
     *
     * @param string $bannerImage
     *
     * @return Banner
     */
    public function setBannerImage($bannerImage)
    {
        $this->banner_image = $bannerImage;

        return $this;
    }

    /**
     * Get bannerImage
     *
     * @return string
     */
    public function getBannerImage()
    {
        return $this->banner_image;
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
}

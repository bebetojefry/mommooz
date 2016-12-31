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
    
    public function getClass(){
        $class = explode('\\', get_class($this));
        return end($class);
    }
    
    public function getRouteName(){
        $route = '';
        switch($this->getClass()){
            case 'CategoryBanner':
                $route = 'category_page';
                break;
            case 'ItemBanner':
                $route = 'item_page';
                break;
            case 'OfferBanner':
                $route = 'offer_page';
                break;
        }
        
        return $route;
    }
    
    public function getPicture(){
        $images = $this->getImages();
        $picture = 'data:image/jpeg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAAA8AAD/4QMraHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjMtYzAxMSA2Ni4xNDU2NjEsIDIwMTIvMDIvMDYtMTQ6NTY6MjcgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDUzYgKFdpbmRvd3MpIiB4bXBNTTpJbnN0YW5jZUlEPSJ4bXAuaWlkOjY2QTc0NTM0RTUyNjExRTVCODBFREYwMUJDRjk3NzM1IiB4bXBNTTpEb2N1bWVudElEPSJ4bXAuZGlkOjY2QTc0NTM1RTUyNjExRTVCODBFREYwMUJDRjk3NzM1Ij4gPHhtcE1NOkRlcml2ZWRGcm9tIHN0UmVmOmluc3RhbmNlSUQ9InhtcC5paWQ6NjZBNzQ1MzJFNTI2MTFFNUI4MEVERjAxQkNGOTc3MzUiIHN0UmVmOmRvY3VtZW50SUQ9InhtcC5kaWQ6NjZBNzQ1MzNFNTI2MTFFNUI4MEVERjAxQkNGOTc3MzUiLz4gPC9yZGY6RGVzY3JpcHRpb24+IDwvcmRmOlJERj4gPC94OnhtcG1ldGE+IDw/eHBhY2tldCBlbmQ9InIiPz7/7gAmQWRvYmUAZMAAAAABAwAVBAMGCg0AABDvAAAS9QAAH7oAACUl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J5wlUllUmHeTSySD06jcTTUfRE05TsKAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABgYEYvgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAHMcZ2HSAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA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kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkgEAkkkgEkkkkkkkkkkgkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkgAEkEkEAkkkEkEkkAkkEkkgEkggkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkAAAkggAEAkAkAkkEAgEEgkEgkEkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkgkgkgkkkEEEEgEEkAgAAAAgAEkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkgkkkkEgkAkAgAkkkAgAEEAkAgEkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkAAgkkkEkgAgAkkgEkAEgEEgkAkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkEkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkAkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk//aAAgBAQMBPxD+K5IgQJxIW/CAFnMBgHM0ltbITcOg3+vAutygGH14bBuvn2ohAubKdY1oIQxDCP8A5Q8gaVZVNE24QFcAiUIAdV3+0UACiEbiNTMxQAmdlQTxfbGp+K1MGJoGc0CMNACTkvwhrtiLyrrhBg+OKCQkjkaIAAWhapjcaTKm8TQcwF1wmYnvU6JOwIPy4dE1k+y0IBGRwlISZyAHq0HccJLHQaiLtiKXvQBBMJcpCTOQA9Wg7jhJY6DURdsRS96AIJhLnBQKsBla6XxM+08GhU3AlHrRiILKwT6VDL2x8OM2KEAFHrRCANLiIs1eTEwAn0q7Fzb+ykBCygHu1L+8IJ7lOA9gKXvQiCMjh4dcIMHzRUgGUvDixJYTmntNRMUsSDzQAgmEucPzLXtP2I41vBOqo+KN5vduF/XPCA+Tc+79FbtiLBO7eoDhUSQfJVvyNzZLJ7/RsnGpBCQjWi4yCEys3VqSb5hiWanBREyP7N6t7cQSzM4iPmkkPSIsJ60VqzOFho7VK4BJC5KjXSrkICEsl7KUCbAOkLJ3xRWrM4WGjtUrgEkLkqNdKuQgISyXspQJsA6QsnfHBWimA1ZgXq1Y5ImKy3t4qTBGVmDJ+qcuGSbl4tFBJIY2xK3oJoc2zypCddVK6mSvLBl4ZO1c02cB6iCyiFTWNqmoCYURGmtGkVzrklpjcqexBc2DcNqZLAXtIyUisCLNEsWJp3ucOQmwhoIORCyP4oY6EXNkYfkqEYITDoD1pDDvAkYbWpQkncUmBKXBlvPVu/LSeEwGhmehT9lkk2JnER80WYJu0CYOj9huLMT1MxMepWgXs6BH2nhAQ0qt9P7UZr5D0A/mhNSGegj+aVLTHZB/P0bCgtIYSk0fU+MCC9ORKEso4XrUx5bDu0tJe0X8T8q5fs4c03UBBokfFIo5xw5puoCDRI+KRRzilFyd6JolIlN8TbeGjXueREkTFGNli+/hsfhBg0ggT7/Rk7VzTZxgfI89WQaQe6rk3WiQTUL72nbgJTAFLllru9rUAoQCGbCubb18jz0wHQT2P5oQGJPcn81zTdWDtRaL3ZeYmgRGxs0oj/NB9iONraR2YnJ6PCAzpkHsGjJlhHokfijC3YO4oiLAHsfQbIGWDP6KDXOakIECfWhasDuFafBACh8tEAIoDJdu1y/Zw5purB2rl+zhzTdWDtXL9lIfdI9UVZUMKmSDHWkRUAXV/bQEJJ7AOV+rZxk7VzTZxgfI89c03cWNoth2jC+Sndwool1lh7VG9SwB2Cvkeema0D2oRlPoTZXKN1YO1AwYI7INCkgjl1rUCpKDv9g6770uRHWahdJEmx6lIzRFBQbTac0gDOGy7nqUlEMBE4LlK7GWwMNR9yQmUdz81CJlJkI9c9iiKggfvgQgm4Tu6RSBMyrviuaPxRPq2X0yoX7OXK6rSY4GjgYTrRMQdmKF85wURgICJzZDRqb2l1/ZRk+FR1XK2rB2oEmJRKJ6VN7S6/soyfCo6rlbVg7UCTEolE9K+UK1ImelLMTK6jm23ai/am7vbzSqsOCyAavWgV8iJBN49KldJEGx6FfKFakTPTioOGs6gnsiiT4EDRMJamtcsv4BM1HubSdtsUfcsgRJWjSeYwYRVy1G8CCzswQ3onhcGk2yFDKwS7Ibz70pONgKjuU+FQkKj3IoHcUESDE+tasDBiTT9U+EtEJrLvNS4w1g7Vs1ezJqGs0PHjsGGIxNNppQluzqPvAGRGCZZexTVkCYCukF6KAXKm0pfb70WBBg5RM3KMrqpl7RUimciRAGw/hXv//aAAgBAgMBPxD/AJ4L/9oACAEDAwE/EP8Angv/2Q==';
        if($images->count() > 0){
            $picture = $images->first()->getImage();
        }
        
        return $picture;
    }
}

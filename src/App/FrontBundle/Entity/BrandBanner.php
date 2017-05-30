<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\FrontBundle\Entity\Banner;

/**
 * BrandBanner
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class BrandBanner extends Banner
{
    /**
     * @var Category
     * 
     * @ORM\ManyToOne(targetEntity="Category"))
     */
    private $entity;

    /**
     * Set entity
     *
     * @param \App\FrontBundle\Entity\Category $entity
     *
     * @return CategoryBanner
     */
    public function setEntity(\App\FrontBundle\Entity\Category $entity = null)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return \App\FrontBundle\Entity\Category
     */
    public function getEntity()
    {
        return $this->entity;
    }
}

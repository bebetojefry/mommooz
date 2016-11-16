<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\FrontBundle\Entity\Banner;

/**
 * ItemBanner
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ItemBanner extends Banner
{
    /**
     * @var Item
     * 
     * @ORM\ManyToOne(targetEntity="Item"))
     */
    private $entity;

    /**
     * Set entity
     *
     * @param \App\FrontBundle\Entity\Item $entity
     *
     * @return ItemBanner
     */
    public function setEntity(\App\FrontBundle\Entity\Item $entity = null)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return \App\FrontBundle\Entity\Item
     */
    public function getEntity()
    {
        return $this->entity;
    }
}

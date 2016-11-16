<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\FrontBundle\Entity\Banner;

/**
 * OfferBanner
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class OfferBanner extends Banner
{
    /**
     * @var Offer
     * 
     * @ORM\ManyToOne(targetEntity="Offer"))
     */
    private $entity;

    /**
     * Set entity
     *
     * @param \App\FrontBundle\Entity\Offer $entity
     *
     * @return OfferBanner
     */
    public function setEntity(\App\FrontBundle\Entity\Offer $entity = null)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return \App\FrontBundle\Entity\Offer
     */
    public function getEntity()
    {
        return $this->entity;
    }
}

<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Brand
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Brand
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
     * @var ArrayCollection|Keyword[]
     *
     * @ORM\ManyToMany(targetEntity="Keyword"))
     */
    private $keywords;

    /**
     * @var ArrayCollection|Item[]
     *
     * @ORM\OneToMany(targetEntity="Item", mappedBy="brand")
     */
    private $items;
    
    /**
     * @var string
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;
    
    /**
     * @var string
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;


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
     * @return Brand
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
     * @return Brand
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
     * Set keywords
     *
     * @param string $keywords
     *
     * @return Brand
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
     * @param string $status
     *
     * @return Brand
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return string
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
    }

    /**
     * Add keyword
     *
     * @param \App\FrontBundle\Entity\Keyword $keyword
     *
     * @return Brand
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
     * @return Brand
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
     * Set visible
     *
     * @param boolean $visible
     *
     * @return Brand
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Is visible
     *
     * @return boolean
     */
    public function isVisible()
    {
        return $this->visible;
    }
}

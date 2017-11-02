<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ItemVariant
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ItemVariant
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
     * @var Item
     * 
     * @ORM\ManyToOne(targetEntity="Item", inversedBy="variants", cascade={"persist"}))
     */
    private $item;
    
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;

    /**
     * @var VariantType
     * 
     * @ORM\ManyToOne(targetEntity="VariantType"))
     */
    private $variantType;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=255)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var ArrayCollection|StockEntry[]
     *
     * @ORM\OneToMany(targetEntity="StockEntry", mappedBy="variant")
     */
    private $stock_entries;

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
     * Set value
     *
     * @param string $value
     *
     * @return ItemVariant
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return ItemVariant
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set item
     *
     * @param \App\FrontBundle\Entity\Item $item
     *
     * @return ItemVariant
     */
    public function setItem(\App\FrontBundle\Entity\Item $item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return \App\FrontBundle\Entity\Item
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set variantType
     *
     * @param \App\FrontBundle\Entity\VariantType $variantType
     *
     * @return ItemVariant
     */
    public function setVariantType(\App\FrontBundle\Entity\VariantType $variantType = null)
    {
        $this->variantType = $variantType;

        return $this;
    }

    /**
     * Get variantType
     *
     * @return \App\FrontBundle\Entity\VariantType
     */
    public function getVariantType()
    {
        return $this->variantType;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->stock_entries = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add stockEntry
     *
     * @param \App\FrontBundle\Entity\StockEntry $stockEntry
     *
     * @return ItemVariant
     */
    public function addStockEntry(\App\FrontBundle\Entity\StockEntry $stockEntry)
    {
        $this->stock_entries[] = $stockEntry;

        return $this;
    }

    /**
     * Remove stockEntry
     *
     * @param \App\FrontBundle\Entity\StockEntry $stockEntry
     */
    public function removeStockEntry(\App\FrontBundle\Entity\StockEntry $stockEntry)
    {
        $this->stock_entries->removeElement($stockEntry);
    }

    /**
     * Get stockEntries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStockEntries()
    {
        return $this->stock_entries;
    }
    
    public function getUniqueName()
    {
        return sprintf('%s - %s', $this->getVariantType()->getName(), $this->value);
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return ItemVariant
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}

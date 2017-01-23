<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reward
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Reward
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
     * @ORM\ManyToOne(targetEntity="Consumer")
     * @ORM\JoinColumn(name="consumer", referencedColumnName="id")
     */
    private $consumer;

    /**
     * @var integer
     *
     * @ORM\Column(name="point", type="integer")
     */
    private $point;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255)
     */
    private $source;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="credited_on", type="datetime")
     */
    private $creditedOn;


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
     * Set point
     *
     * @param integer $point
     *
     * @return Reward
     */
    public function setPoint($point)
    {
        $this->point = $point;

        return $this;
    }

    /**
     * Get point
     *
     * @return integer
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return Reward
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set creditedOn
     *
     * @param \DateTime $creditedOn
     *
     * @return Reward
     */
    public function setCreditedOn($creditedOn)
    {
        $this->creditedOn = $creditedOn;

        return $this;
    }

    /**
     * Get creditedOn
     *
     * @return \DateTime
     */
    public function getCreditedOn()
    {
        return $this->creditedOn;
    }

    /**
     * Set consumer
     *
     * @param \App\FrontBundle\Entity\Consumer $consumer
     *
     * @return Reward
     */
    public function setConsumer(\App\FrontBundle\Entity\Consumer $consumer = null)
    {
        $this->consumer = $consumer;

        return $this;
    }

    /**
     * Get consumer
     *
     * @return \App\FrontBundle\Entity\Consumer
     */
    public function getConsumer()
    {
        return $this->consumer;
    }
}

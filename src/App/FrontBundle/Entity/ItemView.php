<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ItemView
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class ItemView
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
     * @var User
     * 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="ItemViews")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="session_id", type="string", length=255)
     */
    private $sessionId;

    /**
     * @var StockEntry
     * 
     * @ORM\ManyToOne(targetEntity="StockEntry", inversedBy="ItemViews")
     */
    private $entry;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="viewed_on", type="datetime")
     */
    private $viewedOn;

    /*
     * Constructor
     */
    public function __construct() {
        $this->viewedOn = new \DateTime('now');
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
     * Set sessionId
     *
     * @param string $sessionId
     *
     * @return ItemView
     */
    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;
    
        return $this;
    }

    /**
     * Get sessionId
     *
     * @return string
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * Set user
     *
     * @param \App\FrontBundle\Entity\User $user
     *
     * @return ItemView
     */
    public function setUser(\App\FrontBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \App\FrontBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set entry
     *
     * @param \App\FrontBundle\Entity\StockEntry $entry
     *
     * @return ItemView
     */
    public function setEntry(\App\FrontBundle\Entity\StockEntry $entry = null)
    {
        $this->entry = $entry;
    
        return $this;
    }

    /**
     * Get entry
     *
     * @return \App\FrontBundle\Entity\StockEntry
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * Set viewedOn
     *
     * @param \DateTime $viewedOn
     *
     * @return ItemView
     */
    public function setViewedOn($viewedOn)
    {
        $this->viewedOn = $viewedOn;
    
        return $this;
    }

    /**
     * Get viewedOn
     *
     * @return \DateTime
     */
    public function getViewedOn()
    {
        return $this->viewedOn;
    }
}

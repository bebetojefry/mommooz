<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reference
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Reference
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
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;
    
    /**
     * @ORM\ManyToOne(targetEntity="Consumer")
     * @ORM\JoinColumn(name="Consumer", referencedColumnName="id")
     */
    private $refered_by;


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
     * @return Reference
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
     * Set email
     *
     * @param string $email
     *
     * @return Reference
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Reference
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    
        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set referedBy
     *
     * @param \App\FrontBundle\Entity\Consumer $referedBy
     *
     * @return Reference
     */
    public function setReferedBy(\App\FrontBundle\Entity\Consumer $referedBy = null)
    {
        $this->refered_by = $referedBy;
    
        return $this;
    }

    /**
     * Get referedBy
     *
     * @return \App\FrontBundle\Entity\Consumer
     */
    public function getReferedBy()
    {
        return $this->refered_by;
    }
}

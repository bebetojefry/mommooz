<?php

namespace App\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Session
 *
 * @ORM\Table(name="Session")
 * @ORM\Entity(repositoryClass="App\FrontBundle\Entity\SessionRepository")
 */
class Session
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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="sessions"))
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=255)
     */
    private $ip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="loggedInOn", type="datetime")
     */
    private $logged_in_On;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="loggedOutOn", type="datetime")
     */
    private $logged_out_On;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

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
     * Set user
     *
     * @param User $user
     *
     * @return Userlock
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return Userlock
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set loggedInOn
     *
     * @param \DateTime $loggedInOn
     *
     * @return Session
     */
    public function setLoggedInOn($loggedInOn)
    {
        $this->logged_in_On = $loggedInOn;

        return $this;
    }

    /**
     * Get loggedInOn
     *
     * @return \DateTime
     */
    public function getLoggedInOn()
    {
        return $this->logged_in_On;
    }
    
    /**
     * Set loggedOutOn
     *
     * @param \DateTime $loggedOutOn
     *
     * @return Session
     */
    public function setLoggedOutOn($loggedOutOn)
    {
        $this->logged_out_On = $loggedOutOn;

        return $this;
    }

    /**
     * Get loggedOutOn
     *
     * @return \DateTime
     */
    public function getLoggedOutOn()
    {
        return $this->logged_out_On;
    }
    
    /**
     * Set status
     *
     * @param Boolean $status
     *
     * @return Session
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return Boolean
     */
    public function getStatus()
    {
        return $this->status;
    }
}

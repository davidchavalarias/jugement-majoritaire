<?php

namespace Polytech\Bundle\JMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Code
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Polytech\Bundle\JMBundle\Entity\CodeRepository")
 */
class Code
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
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;
    
    /**
     * @ORM\ManyToOne(targetEntity="Election", inversedBy="codes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $election;


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
     * Set code
     *
     * @param string $code
     * @return Code
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

    /**
     * Set election
     *
     * @param \Polytech\JMBundle\Entity\Election $election
     * @return Code
     */
    public function setElection(\Polytech\JMBundle\Entity\Election $election)
    {
        $this->election = $election;

        return $this;
    }

    /**
     * Get election
     *
     * @return \Polytech\JMBundle\Entity\Election 
     */
    public function getElection()
    {
        return $this->election;
    }
}

<?php

namespace Polytech\Bundle\JMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Vote
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Polytech\Bundle\JMBundle\Entity\VoteRepository")
 */
class Vote
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
     * @ORM\ManyToOne(targetEntity="Candidat", inversedBy="votes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $candidat;
    
    /**
     * @ORM\ManyToOne(targetEntity="Mention", inversedBy="votes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mention;


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
     * Set candidat
     *
     * @param \Polytech\JMBundle\Entity\Candidat $candidat
     * @return Vote
     */
    public function setCandidat(\Polytech\JMBundle\Entity\Candidat $candidat)
    {
        $this->candidat = $candidat;

        return $this;
    }

    /**
     * Get candidat
     *
     * @return \Polytech\JMBundle\Entity\Candidat 
     */
    public function getCandidat()
    {
        return $this->candidat;
    }

    /**
     * Set mention
     *
     * @param \Polytech\JMBundle\Entity\Mention $mention
     * @return Vote
     */
    public function setMention(\Polytech\JMBundle\Entity\Mention $mention)
    {
        $this->mention = $mention;

        return $this;
    }

    /**
     * Get mention
     *
     * @return \Polytech\JMBundle\Entity\Mention 
     */
    public function getMention()
    {
        return $this->mention;
    }

    public function __toString() {
        return $this->getId();
    }
}
<?php

namespace Polytech\Bundle\JMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Mention
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Polytech\Bundle\JMBundle\Entity\MentionRepository")
 */
class Mention
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="Vote", mappedBy="mention")
     */
    private $votes; 

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nom
     *
     * @param string $nom
     * @return Mention
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Add votes
     *
     * @param \Polytech\JMBundle\Entity\Vote $vote
     * @return Mention
     */
    public function addVote(\Polytech\JMBundle\Entity\Vote $vote)
    {
        $this->votes[] = $vote;

        return $this;
    }

    /**
     * Remove votes
     *
     * @param \Polytech\JMBundle\Entity\Vote $vote
     */
    public function removeVote(\Polytech\JMBundle\Entity\Vote $vote)
    {
        $this->votes->removeElement($vote);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVotes()
    {
        return $this->votes;
    }
}
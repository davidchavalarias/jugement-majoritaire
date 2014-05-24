<?php

namespace Polytech\Bundle\JMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Election
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Polytech\Bundle\JMBundle\Entity\ElectionRepository")
 */
class Election
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="started", type="boolean")
     */
    private $started;

    /**
     * @var boolean
     *
     * @ORM\Column(name="finished", type="boolean")
     */
    private $finished;

    /**
     * @ORM\OneToMany(targetEntity="Candidat", mappedBy="election")
     */
    private $candidats; 

    /**
     * @ORM\OneToMany(targetEntity="Code", mappedBy="election")
     */
    private $codes; 


    public function __construct()
    {
        $this->started = false;
        $this->finished = false;
        $this->candidats = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \DateTime("now");
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
     * @return Election
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
     * Set date
     *
     * @param \DateTime $date
     * @return Election
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set started
     *
     * @param boolean $started
     * @return Election
     */
    public function setStarted($started)
    {
        $this->started = $started;

        return $this;
    }

    /**
     * Get started
     *
     * @return started
     */
    public function getStarted()
    {
        return $this->started;
    }

    /**
     * Set finished
     *
     * @param boolean $finished
     * @return Election
     */
    public function setFinished($finished)
    {
        $this->finished = $finished;

        return $this;
    }

    /**
     * Get finished
     *
     * @return finished
     */
    public function getFinished()
    {
        return $this->finished;
    }

    /**
     * Add candidats
     *
     * @param Candidat $candidat
     * @return Election
     */
    public function addCandidat(Candidat $candidat)
    {
        $this->candidats[] = $candidat;
        $candidat->setElection($this);

        return $this;
    }

    /**
     * Remove candidats
     *
     * @param \Polytech\JMBundle\Entity\Candidat $candidat
     */
    public function removeCandidat(Candidat $candidat)
    {
        $this->candidats->removeElement($candidat);
    }

    /**
     * Get candidats
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCandidats()
    {
        return $this->candidats;
    }

    /**
     * Add code
     *
     * @param Code $code
     * @return Code
     */
    public function addCode(Code $code)
    {
        $this->codes[] = $code;
        $code->setElection($this);

        return $this;
    }

    /**
     * Remove code
     *
     * @param Code $code
     */
    public function removeCode(Code $code)
    {
        $this->codes->removeElement($code);
    }

    /**
     * Get codes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCodes()
    {
        return $this->codes;
    }

    public function __toString() {
        return $this->getNom();
    }
}

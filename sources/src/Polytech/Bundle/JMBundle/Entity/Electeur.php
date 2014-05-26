<?php

namespace Polytech\Bundle\JMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Electeur
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Polytech\Bundle\JMBundle\Entity\ElecteurRepository")
 */
class Electeur
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
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="dejaVote", type="boolean")
     */
    private $dejaVote;
    
    /**
     * @ORM\ManyToOne(targetEntity="Election", inversedBy="electeurs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $election;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dejaVote  = false;
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
     * @return Electeur
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
     * Set prenom
     *
     * @param string $prenom
     * @return Electeur
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }
    

    /**
     * Set email
     *
     * @param string $email
     * @return Electeur
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
     * Set dejaVote
     *
     * @param boolean $dejaVote
     * @return Electeur
     */
    public function setDejaVote($dejaVote)
    {
        $this->dejaVote = $dejaVote;

        return $this;
    }

    /**
     * Get dejaVote
     *
     * @return boolean 
     */
    public function isDejaVote()
    {
        return $this->dejaVote;
    }

    public function getDejaVote()
    {
        return $this->dejaVote;
    }


    public function __toString() {
        return $this->getPrenom() . " " . $this->getNom();
    }

    /**
     * Set election
     *
     * @param Election $election
     * @return Electeur
     */
    public function setElection(Election $election)
    {
        $this->election = $election;

        return $this;
    }

    /**
     * Get election
     *
     * @return Election 
     */
    public function getElection()
    {
        return $this->election;
    }
}
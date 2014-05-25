<?php

namespace Polytech\Bundle\JMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Polytech\Bundle\JMBundle\Entity\Electeur;
use Polytech\Bundle\JMBundle\Form\ElecteurType;

/**
 * Electeur controller.
 *
 */
class ElecteurController extends Controller
{
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();
        $election = $em->getRepository('PolytechJMBundle:Election')->find($id);
        $entities = $em->getRepository('PolytechJMBundle:Electeur')->findByElection($election->getId());

        return $this->render('PolytechJMBundle:Electeur:index.html.twig', array(
            'entities' => $entities,
            'currentElections' => $currentElections, 
            'electionNom' => $election->getNom(),
        ));
    }
}

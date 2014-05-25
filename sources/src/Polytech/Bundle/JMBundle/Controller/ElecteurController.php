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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();
        $entities = $em->getRepository('PolytechJMBundle:Electeur')->findAll();

        return $this->render('PolytechJMBundle:Electeur:index.html.twig', array(
            'entities' => $entities,
            'currentElections' => $currentElections, 
        ));
    }
}

<?php

namespace Polytech\Bundle\JMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $elections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();
        $oldElections = $em->getRepository('PolytechJMBundle:Election')->findByStarted(false);

        return $this->render('PolytechJMBundle:Default:index.html.twig', array('elections' => $elections, 'oldElections' => $oldElections));
    }

    public function voteAction($idElection)
    {
        $em = $this->getDoctrine()->getManager();
        $election = $em->getRepository('PolytechJMBundle:Election')->find($idElection);

        return $this->render('PolytechJMBundle:Default:vote.html.twig', array('election' => $election));
    }

    public function checkvoteAction($idElection)
    {
        $em = $this->getDoctrine()->getManager();
        $election = $em->getRepository('PolytechJMBundle:Election')->find($idElection);

        return $this->render('PolytechJMBundle:Default:check_vote.html.twig', array('election' => $election));
    }

    public function statsAction($idElection)
    {
        $em = $this->getDoctrine()->getManager();
        $election = $em->getRepository('PolytechJMBundle:Election')->find($idElection);

        return $this->render('PolytechJMBundle:Default:stats.html.twig', array('election' => $election));
    }
}

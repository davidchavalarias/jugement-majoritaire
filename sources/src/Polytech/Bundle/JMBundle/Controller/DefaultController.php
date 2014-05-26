<?php

namespace Polytech\Bundle\JMBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Polytech\Bundle\JMBundle\Entity\Vote;
use Polytech\Bundle\JMBundle\Entity\Candidat;
use Doctrine\Common\Util\Debug;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();
        $oldElections = $em->getRepository('PolytechJMBundle:Election')->findByFinished(true);

        return $this->render('PolytechJMBundle:Default:index.html.twig', array('currentElections' => $currentElections, 'oldElections' => $oldElections));
    }

    public function voteAction(Request $request, $idElection)
    {
        $em = $this->getDoctrine()->getManager();
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();
        $election = $em->getRepository('PolytechJMBundle:Election')->find($idElection);

        if($election->getFinished() == true || $election->getStarted() == false)
        {
            return $this->redirect($this->generateUrl('polytech_jm_index'));
        }


        $candidats = $em->getRepository('PolytechJMBundle:Candidat')->findByElection($election->getId());
        $builder = $this->get('form.factory')->createNamedBuilder('form_vote','form');

        setlocale(LC_ALL,'fr_FR.UTF-8');
        foreach ($candidats as $candidat)
        {
            $builder->add('mention-'.htmlentities(iconv("UTF-8", "ASCII//TRANSLIT", str_replace(' ', '_',$candidat->getNom()) )), 'entity', array(
                'class' => 'PolytechJMBundle:Mention',
                'property' => 'nom',
                'expanded' => true,
                'multiple' => false,
                'label' => $candidat->getNom(),
                'label_attr' => array('class' => 'col-md-2'),
                'attr' => array('style' => 'display:inline', 'class' => 'col-md-10'),
                'query_builder' => function(\Polytech\Bundle\JMBundle\Entity\MentionRepository $repo) use ($election)
                    {
                        return $repo->createQueryBuilder('m')->where('m.election = ?1')->setParameter(1,$election)->orderBy('m.id','ASC');
                    }
            ));
        }

        $builder->add('revenir','submit',array(
            'attr' => array(
                'class' => 'btn btn-primary',
                'first' =>'data-first-button' ),
            'label' => 'Valider'
            ));

        $form = $builder->getForm();
        $form->handleRequest($request);

        /*if($form->isValid())
        {
            $data = $form->getData();

            if($form->getName() === 'form_mention')
            {
                return $this->redirect($this->generateUrl('polytech_jm_index'));
            }

            if($form->getName() === 'form_check')
            {
                Debug::dump($data);
                //return $this->redirect($this->generateUrl('polytech_jm_check_vote'));
            }

            /*Gérer l'ajout des vote dans la bdd ici*/
            /*$elec = (new Electeur())->setNom('Electeur');
            $em->persist($elec);
            foreach ($candidats as $candidat)
            {
                $vote = new Vote();
                $vote->setCandidat($candidat);
                $vote->setMention($form->get('mention-'.htmlentities(iconv('UTF-8','ASCII//TRANSLIT',str_replace(' ', '_',$candidat->getNom())))->getData());
                $vote->setElecteur($elec);
                $em->persist($vote);

            }
            $em->flush();

            return $this->redirect($this->generateUrl('polytech_jm_index'));
        }*/

        if('POST' === $request->getMethod()){
 
            if($request->request->has('form_vote'))
            {
                setlocale(LC_ALL,'fr_FR.UTF-8');
                foreach ($candidats as $candidat)
                {
                    $vote = new Vote();
                    $vote->setCandidat($candidat);
                    $vote->setMention($form->get('mention-'.htmlentities(iconv('UTF-8','ASCII//TRANSLIT',str_replace(' ', '_',$candidat->getNom()))))->getData());
                    $em->persist($vote);
                }
                $em->flush();
                return $this->redirect($this->generateUrl('polytech_jm_index'));
            }
            else if($request->request->has('form_check')){
                // verif bon Code et email
                $formCheck = $request->request->get('form_check');
                $code = $formCheck["code"];
                $email = $formCheck["email"];
            }
        }

        return $this->render('PolytechJMBundle:Default:vote.html.twig', array('currentElections' => $currentElections, 'election' => $election, 'candidats' => $candidats, 'form' => $form->createView()));
    }

    public function checkvoteAction($idElection)
    {
        $em = $this->getDoctrine()->getManager();
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();
        $election = $em->getRepository('PolytechJMBundle:Election')->find($idElection);

        $form = $this->createCheckForm($idElection);

        if($election->getFinished() == true)
        {
            return $this->redirect($this->generateUrl('polytech_jm_index'));
        }

        return $this->render('PolytechJMBundle:Default:check_vote.html.twig', array('currentElections' => $currentElections, 'election' => $election, 'form' => $form->createView()));
    }

    private function createCheckForm($id)
    {
        return $this->get('form.factory')->createNamedBuilder('form_check','form')
            ->setAction($this->generateUrl('polytech_jm_vote', array('idElection' => $id)))
            ->setMethod('POST')
            ->add('email', 'email', array('label' => 'Votre e-mail'))
            ->add('code', 'text', array('label' => 'Votre code personnel'))
            ->add('submit', 'submit', array('label' => 'Connexion', 'attr' => array('class' => 'btn btn-success' )))
            ->getForm()
        ;
    }

    public function statsAction($idElection)
    {
        $em = $this->getDoctrine()->getManager();
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();
        $election = $em->getRepository('PolytechJMBundle:Election')->find($idElection);

        if($election->getFinished() == false)
        {
            return $this->redirect($this->generateUrl('polytech_jm_index'));
        }

        $candidats = $em->getRepository('PolytechJMBundle:Candidat')->findByElection($election->getId());
        $mentions = $em->getRepository('PolytechJMBundle:Mention')->findByElection($election->getId());
        
        $nbrVotes = array();
        $indMentionMaj = array();
        $nbVMention = array();
        
        foreach($candidats as $cand)
        {
            $n = $em->getRepository('PolytechJMBundle:Vote')->countVotesByCandidat($cand->getId());
            $nbrVotes[$cand->getNom()] = $n[1];
            $nbrVotesMentionTmp = array();
            $pourcentageMention = array();
            foreach($mentions as $m)
            {
                $temp = $em->getRepository('PolytechJMBundle:Vote')->countVotesByCandidatAndMention($cand->getId(), $m->getId());
                $nbrVotesMentionTmp[$m->getNom()] = $temp[1];
                if($nbrVotes[$cand->getNom()] == 0)
                {
                    $pourcentageMention[$m->getNom()] = 0;
                }
                else
                {
                    $pourcentageMention[$m->getNom()] = $nbrVotesMentionTmp[$m->getNom()]*100/$nbrVotes[$cand->getNom()];
                }
            }

            $p_maj = 0;
            $i_maj = 0;
            $plus  = 0;
            foreach($mentions as  $m){
                $p_maj +=$pourcentageMention[$m->getNom()];
                $i_maj += 1;
                if($p_maj < 50)
                {
                    $plus += $pourcentageMention[$m->getNom()];
                }
                if($p_maj >= 50) break;
            }
            $moins = 100-($plus+$pourcentageMention[$m->getNom()]);
            $nbVMention[$cand->getNom()] = $nbrVotesMentionTmp;
            
            if(!isset($indMentionMaj[$i_maj]))
            {
                $indMentionMaj[$i_maj]=array();
            }
            if($plus >= $moins)
            {
                if(!isset($indMentionMaj[$i_maj][0]))
                {
                    $indMentionMaj[$i_maj][0] = array();
                }
                $indMentionMaj[$i_maj][0][$cand->getNom()] = $plus;
            }
            else
            {
                if(!isset($indMentionMaj[$i_maj][1]))
                {
                    $indMentionMaj[$i_maj][1] = array();
                }
                $indMentionMaj[$i_maj][1][$cand->getNom()] = $moins;
            }
            
        }

        //asort($indMentionMaj);
        ksort($indMentionMaj);
        foreach($indMentionMaj as &$indV)
        {
            if(isset($indV[0]))
            {
                arsort($indV[0]);
            }

            if(isset($indV[1]))
            {
                asort($indV[1]);
            }
        }

        return $this->render('PolytechJMBundle:Default:stats.html.twig', array('currentElections' => $currentElections, 'election' => $election, 'nbVMention' => $nbVMention, 'mentions' => $mentions, 'indMentionMaj' => $indMentionMaj, 'nbC' => count($candidats)));
    }



    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        return $this->render('PolytechJMBundle:Security:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }
}

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
            'election' => $election,
        ));
    }

    /**
     * Creates a new Electeur entity.
     *
     */
    public function createAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Electeur();
        $entity->setElection($em->getRepository('PolytechJMBundle:Election')->find($id));
        $form = $this->createCreateForm($entity, $id);
        $form->handleRequest($request);
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();
        
        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_electeur', array('id' => $entity->getElection()->getId())));
        }

        return $this->render('PolytechJMBundle:Electeur:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'currentElections' => $currentElections,
        ));
    }

    /**
    * Creates a form to create a Electeur entity.
    *
    * @param Electeur $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Electeur $entity, $id)
    {
        $form = $this->createForm(new ElecteurType(), $entity, array(
            'action' => $this->generateUrl('crud_electeur_create', array('id' => $id )),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Ajouter', 'attr' => array('class' => 'btn btn-success' )));

        return $form;
    }

    /**
     * Displays a form to create a new Electeur entity.
     *
     */
    public function newAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Electeur();
        $entity->setElection($em->getRepository('PolytechJMBundle:Election')->find($id));
        $form   = $this->createCreateForm($entity, $id);
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();

        return $this->render('PolytechJMBundle:Electeur:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'currentElections' => $currentElections, 
        ));
    }

    /**
     * Deletes a Electeur entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PolytechJMBundle:Electeur')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Electeur entity.');
            }
            $election = $entity->getElection();
            $em->remove($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('crud_electeur', array('id' => $entity->getElection()->getId() )));
        }
        
        return $this->redirect($this->generateUrl('crud_election'));
    }

    /**
     * Checks before deleting a Electeur entity.
     *
     */
    public function deleteCheckAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PolytechJMBundle:Electeur')->find($id);
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Electeur entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PolytechJMBundle:Electeur:deleteCheck.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'currentElections' => $currentElections,         ));
    }

    /**
     * Creates a form to delete a Electeur entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('crud_mention_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Supprimer'))
            ->getForm()
        ;
    }
}

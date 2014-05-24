<?php

namespace Polytech\Bundle\JMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Polytech\Bundle\JMBundle\Entity\Election;
use Polytech\Bundle\JMBundle\Form\ElectionType;

/**
 * Election controller.
 *
 */
class ElectionController extends Controller
{

    /**
     * Lists all Election entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PolytechJMBundle:Election')->findAll();
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();

        return $this->render('PolytechJMBundle:Election:index.html.twig', array(
            'entities' => $entities,
            'currentElections' => $currentElections, 
        ));
    }
    /**
     * Creates a new Election entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Election();
        $em = $this->getDoctrine()->getManager();
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_election_show', array('currentElections' => $currentElections, 'id' => $entity->getId())));
        }

        return $this->render('PolytechJMBundle:Election:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'currentElections' => $currentElections, 
        ));
    }

    /**
    * Creates a form to create a Election entity.
    *
    * @param Election $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Election $entity)
    {
        $form = $this->createForm(new ElectionType(), $entity, array(
            'action' => $this->generateUrl('crud_election_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Election entity.
     *
     */
    public function newAction()
    {
        $entity = new Election();
        $form   = $this->createCreateForm($entity);
        $em = $this->getDoctrine()->getManager();
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();

        return $this->render('PolytechJMBundle:Election:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'currentElections' => $currentElections, 
        ));
    }

    /**
     * Finds and displays a Election entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PolytechJMBundle:Election')->find($id);
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Election entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PolytechJMBundle:Election:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'currentElections' => $currentElections,         ));
    }

    /**
     * Displays a form to edit an existing Election entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PolytechJMBundle:Election')->find($id);
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Election entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PolytechJMBundle:Election:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'currentElections' => $currentElections, 
        ));
    }

    /**
    * Creates a form to edit a Election entity.
    *
    * @param Election $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Election $entity)
    {
        $form = $this->createForm(new ElectionType(), $entity, array(
            'action' => $this->generateUrl('crud_election_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Election entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PolytechJMBundle:Election')->find($id);
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Election entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('crud_election_edit', array('currentElections' => $currentElections, 'id' => $id)));
        }

        return $this->render('PolytechJMBundle:Election:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'currentElections' => $currentElections, 
        ));
    }
    /**
     * Deletes a Election entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PolytechJMBundle:Election')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Election entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('crud_election'));
    }

    /**
     * Creates a form to delete a Election entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('crud_election_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }


    public function setStartedAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PolytechJMBundle:Election')->find($id);
        $entity->setStarted(true);
        $em->flush();

        return $this->redirect($this->generateUrl('crud_election_show', array('id' => $id )));
    }

    public function setFinishedAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('PolytechJMBundle:Election')->find($id);
        $entity->setFinished(true);
        $em->flush();

        return $this->redirect($this->generateUrl('crud_election_show', array('id' => $id )));
    }
}

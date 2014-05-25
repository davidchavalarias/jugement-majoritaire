<?php

namespace Polytech\Bundle\JMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Polytech\Bundle\JMBundle\Entity\Mention;
use Polytech\Bundle\JMBundle\Form\MentionType;

/**
 * Mention controller.
 *
 */
class MentionController extends Controller
{

    /**
     * Lists all Mention entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();
        $entities = $em->getRepository('PolytechJMBundle:Mention')->findAll();

        return $this->render('PolytechJMBundle:Mention:index.html.twig', array(
            'entities' => $entities,
            'currentElections' => $currentElections, 
        ));
    }
    /**
     * Creates a new Mention entity.
     *
     */
    public function createAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Mention();
        $entity->setElection($em->getRepository('PolytechJMBundle:Election')->find($id));
        $form = $this->createCreateForm($entity, $id);
        $form->handleRequest($request);
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();
        
        if ($form->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_election_show', array('id' => $entity->getElection()->getId())));
        }

        return $this->render('PolytechJMBundle:Mention:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'currentElections' => $currentElections,
        ));
    }

    /**
    * Creates a form to create a Mention entity.
    *
    * @param Mention $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Mention $entity, $id)
    {
        $form = $this->createForm(new MentionType(), $entity, array(
            'action' => $this->generateUrl('crud_mention_create', array('id' => $id )),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Ajouter', 'attr' => array('class' => 'btn btn-success' )));

        return $form;
    }

    /**
     * Displays a form to create a new Mention entity.
     *
     */
    public function newAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Mention();
        $entity->setElection($em->getRepository('PolytechJMBundle:Election')->find($id));
        $form   = $this->createCreateForm($entity, $id);
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();

        return $this->render('PolytechJMBundle:Mention:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'currentElections' => $currentElections, 
        ));
    }

    /**
     * Finds and displays a Mention entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PolytechJMBundle:Mention')->find($id);
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mention entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PolytechJMBundle:Mention:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'currentElections' => $currentElections,        ));
    }

    /**
     * Displays a form to edit an existing Mention entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PolytechJMBundle:Mention')->find($id);
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mention entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PolytechJMBundle:Mention:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'currentElections' => $currentElections, 
        ));
    }

    /**
    * Creates a form to edit a Mention entity.
    *
    * @param Mention $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Mention $entity)
    {
        $form = $this->createForm(new MentionType(), $entity, array(
            'action' => $this->generateUrl('crud_mention_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Mention entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PolytechJMBundle:Mention')->find($id);
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mention entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('crud_mention_edit', array('currentElections' => $currentElections, 'id' => $id)));
        }

        return $this->render('PolytechJMBundle:Mention:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'currentElections' => $currentElections, 
        ));
    }
    /**
     * Deletes a Mention entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PolytechJMBundle:Mention')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Mention entity.');
            }
            $election = $entity->getElection();
            $em->remove($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('crud_election_show', array('id' => $election->getId() )));
        }

        return $this->redirect($this->generateUrl('crud_election'));
    }

    /**
     * Checks before deleting a Mention entity.
     *
     */
    public function deleteCheckAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PolytechJMBundle:Mention')->find($id);
        $currentElections = $em->getRepository('PolytechJMBundle:Election')->findCurrentElections();

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Mention entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PolytechJMBundle:Mention:deleteCheck.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'currentElections' => $currentElections,         ));
    }

    /**
     * Creates a form to delete a Mention entity by id.
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

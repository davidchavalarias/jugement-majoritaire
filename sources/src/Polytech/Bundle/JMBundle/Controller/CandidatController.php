<?php

namespace Polytech\Bundle\JMBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Polytech\Bundle\JMBundle\Entity\Candidat;
use Polytech\Bundle\JMBundle\Form\CandidatType;

/**
 * Candidat controller.
 *
 */
class CandidatController extends Controller
{

    /**
     * Lists all Candidat entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('PolytechJMBundle:Candidat')->findAll();

        return $this->render('PolytechJMBundle:Candidat:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Candidat entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Candidat();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('crud_candidat_show', array('id' => $entity->getId())));
        }

        return $this->render('PolytechJMBundle:Candidat:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
    * Creates a form to create a Candidat entity.
    *
    * @param Candidat $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Candidat $entity)
    {
        $form = $this->createForm(new CandidatType(), $entity, array(
            'action' => $this->generateUrl('crud_candidat_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Candidat entity.
     *
     */
    public function newAction()
    {
        $entity = new Candidat();
        $form   = $this->createCreateForm($entity);

        return $this->render('PolytechJMBundle:Candidat:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Candidat entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PolytechJMBundle:Candidat')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Candidat entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PolytechJMBundle:Candidat:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to edit an existing Candidat entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PolytechJMBundle:Candidat')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Candidat entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('PolytechJMBundle:Candidat:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Candidat entity.
    *
    * @param Candidat $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Candidat $entity)
    {
        $form = $this->createForm(new CandidatType(), $entity, array(
            'action' => $this->generateUrl('crud_candidat_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Candidat entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('PolytechJMBundle:Candidat')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Candidat entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('crud_candidat_edit', array('id' => $id)));
        }

        return $this->render('PolytechJMBundle:Candidat:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Candidat entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('PolytechJMBundle:Candidat')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Candidat entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('crud_candidat'));
    }

    /**
     * Creates a form to delete a Candidat entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('crud_candidat_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

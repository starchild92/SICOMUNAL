<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SICBundle\Entity\SituacionComunidad;
use SICBundle\Form\SituacionComunidadType;

/**
 * SituacionComunidad controller.
 *
 */
class SituacionComunidadController extends Controller
{
    /**
     * Lists all SituacionComunidad entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $situacionComunidads = $em->getRepository('SICBundle:SituacionComunidad')->findAll();

        return $this->render('situacioncomunidad/index.html.twig', array(
            'situacionComunidads' => $situacionComunidads,
        ));
    }

    /**
     * Creates a new SituacionComunidad entity.
     *
     */
    public function newAction(Request $request, $id_planilla)
    {
        /*Redireccionar cuando se accede por GET y evitar que se cree una nueva para la misma planilla*/
        $em = $this->getDoctrine()->getManager();
        $planilla = $em->getRepository('SICBundle:Planillas')->findById($id_planilla);
        $p = $planilla[0];

        if($p->getSituacionComunidad() != NULL){
            $this->get('session')->getFlashBag()
            ->add('error', 'Seleccione la sección que desea modificar');
            return $this->redirectToRoute('planillas_show', array('id' => $id_planilla));
        }

        $situacionComunidad = new SituacionComunidad();
        $form = $this->createForm('SICBundle\Form\SituacionComunidadType', $situacionComunidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($situacionComunidad);
            $p->setSituacionComunidad($situacionComunidad);
            $p->setTerminada('100');
            $em->persist($p);
            $em->flush();

            $this->get('session')->getFlashBag()
            ->add('success', 'Se ha culminado con éxtio el registro de los datos en el sistema.');
            return $this->redirectToRoute('planillas_show', array('id' => $id_planilla));
        }

        return $this->render('situacioncomunidad/new.html.twig', array(
            'situacionComunidad' => $situacionComunidad,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a SituacionComunidad entity.
     *
     */
    public function showAction(SituacionComunidad $situacionComunidad)
    {
        $deleteForm = $this->createDeleteForm($situacionComunidad);

        return $this->render('situacioncomunidad/show.html.twig', array(
            'situacionComunidad' => $situacionComunidad,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing SituacionComunidad entity.
     *
     */
    public function editAction(Request $request, SituacionComunidad $situacionComunidad)
    {
        $deleteForm = $this->createDeleteForm($situacionComunidad);
        $editForm = $this->createForm('SICBundle\Form\SituacionComunidadType', $situacionComunidad);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($situacionComunidad);
            $em->flush();

            return $this->redirectToRoute('situacioncomunidad_edit', array('id' => $situacionComunidad->getId()));
        }

        return $this->render('situacioncomunidad/edit.html.twig', array(
            'situacionComunidad' => $situacionComunidad,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a SituacionComunidad entity.
     *
     */
    public function deleteAction(Request $request, SituacionComunidad $situacionComunidad)
    {
        $form = $this->createDeleteForm($situacionComunidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($situacionComunidad);
            $em->flush();
        }

        return $this->redirectToRoute('situacioncomunidad_index');
    }

    /**
     * Creates a form to delete a SituacionComunidad entity.
     *
     * @param SituacionComunidad $situacionComunidad The SituacionComunidad entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SituacionComunidad $situacionComunidad)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('situacioncomunidad_delete', array('id' => $situacionComunidad->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

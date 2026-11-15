<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminPreguntas;
use SICBundle\Form\AdminPreguntasType;

/**
 * AdminPreguntas controller.
 *
 */
class AdminPreguntasController extends BaseController
{
    /**
     * Lists all AdminPreguntas entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminPreguntas = $em->getRepository(\SICBundle\Entity\AdminPreguntas::class)->findAll();

        return $this->render('adminpreguntas/index.html.twig', array(
            'adminPreguntas' => $adminPreguntas,
        ));
    }

    /**
     * Creates a new AdminPreguntas entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminPregunta = new AdminPreguntas();
        $form = $this->createForm('SICBundle\Form\AdminPreguntasType', $adminPregunta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminPregunta);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Pregunta a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'particiapcioncomunitaria'));
            // return $this->redirectToRoute('configurables_preguntas_show', array('id' => $adminPregunta->getId()));
        }

        return $this->render('adminpreguntas/new.html.twig', array(
            'adminPregunta' => $adminPregunta,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminPreguntas entity.
     *
     */
    public function showAction(AdminPreguntas $adminPregunta)
    {
        $deleteForm = $this->createDeleteForm($adminPregunta);

        return $this->render('adminpreguntas/show.html.twig', array(
            'adminPregunta' => $adminPregunta,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminPreguntas entity.
     *
     */
    public function editAction(Request $request, AdminPreguntas $adminPregunta)
    {
        $deleteForm = $this->createDeleteForm($adminPregunta);
        $editForm = $this->createForm('SICBundle\Form\AdminPreguntasType', $adminPregunta);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminPregunta);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Pregunta');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'particiapcioncomunitaria'));
            // return $this->redirectToRoute('configurables_preguntas_edit', array('id' => $adminPregunta->getId()));
        }

        return $this->render('adminpreguntas/edit.html.twig', array(
            'adminPregunta' => $adminPregunta,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminPreguntas entity.
     *
     */
    public function deleteAction(Request $request, AdminPreguntas $adminPregunta)
    {
        $form = $this->createDeleteForm($adminPregunta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminPregunta);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminPregunta->getPregunta().' de los parámetros de Pregunta del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'particiapcioncomunitaria'));
        // return $this->redirectToRoute('configurables_preguntas_index');
    }

    /**
     * Creates a form to delete a AdminPreguntas entity.
     *
     * @param AdminPreguntas $adminPregunta The AdminPreguntas entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminPreguntas $adminPregunta)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurables_preguntas_delete', array('id' => $adminPregunta->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

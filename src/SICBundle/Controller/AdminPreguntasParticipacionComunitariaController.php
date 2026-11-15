<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminPreguntasParticipacionComunitaria;
use SICBundle\Form\AdminPreguntasParticipacionComunitariaType;

/**
 * AdminPreguntasParticipacionComunitaria controller.
 *
 */
class AdminPreguntasParticipacionComunitariaController extends BaseController
{
    /**
     * Lists all AdminPreguntasParticipacionComunitaria entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminPreguntasParticipacionComunitarias = $em->getRepository('SICBundle:AdminPreguntasParticipacionComunitaria')->findAll();

        return $this->render('adminpreguntasparticipacioncomunitaria/index.html.twig', array(
            'adminPreguntasParticipacionComunitarias' => $adminPreguntasParticipacionComunitarias,
        ));
    }

    /**
     * Creates a new AdminPreguntasParticipacionComunitaria entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminPreguntasParticipacionComunitarium = new AdminPreguntasParticipacionComunitaria();
        $form = $this->createForm('SICBundle\Form\AdminPreguntasParticipacionComunitariaType', $adminPreguntasParticipacionComunitarium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminPreguntasParticipacionComunitarium);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nueva Pregunta de Partición Comunitaria a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'particiapcioncomunitaria'));
            // return $this->redirectToRoute('configurables_preguntas_participacion_comunitaria_show', array('id' => $adminPreguntasParticipacionComunitarium->getId()));
        }

        return $this->render('adminpreguntasparticipacioncomunitaria/new.html.twig', array(
            'adminPreguntasParticipacionComunitarium' => $adminPreguntasParticipacionComunitarium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminPreguntasParticipacionComunitaria entity.
     *
     */
    public function showAction(AdminPreguntasParticipacionComunitaria $adminPreguntasParticipacionComunitarium)
    {
        $deleteForm = $this->createDeleteForm($adminPreguntasParticipacionComunitarium);

        return $this->render('adminpreguntasparticipacioncomunitaria/show.html.twig', array(
            'adminPreguntasParticipacionComunitarium' => $adminPreguntasParticipacionComunitarium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminPreguntasParticipacionComunitaria entity.
     *
     */
    public function editAction(Request $request, AdminPreguntasParticipacionComunitaria $adminPreguntasParticipacionComunitarium)
    {
        $deleteForm = $this->createDeleteForm($adminPreguntasParticipacionComunitarium);
        $editForm = $this->createForm('SICBundle\Form\AdminPreguntasParticipacionComunitariaType', $adminPreguntasParticipacionComunitarium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminPreguntasParticipacionComunitarium);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Pregunta de Partición Comunitaria');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'particiapcioncomunitaria'));
            // return $this->redirectToRoute('configurables_preguntas_participacion_comunitaria_edit', array('id' => $adminPreguntasParticipacionComunitarium->getId()));
        }

        return $this->render('adminpreguntasparticipacioncomunitaria/edit.html.twig', array(
            'adminPreguntasParticipacionComunitarium' => $adminPreguntasParticipacionComunitarium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminPreguntasParticipacionComunitaria entity.
     *
     */
    public function deleteAction(Request $request, AdminPreguntasParticipacionComunitaria $adminPreguntasParticipacionComunitarium)
    {
        $form = $this->createDeleteForm($adminPreguntasParticipacionComunitarium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminPreguntasParticipacionComunitarium);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminPreguntasParticipacionComunitarium->getInterrogante().' de los parámetros de Pregunta Partición Comunitaria del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'particiapcioncomunitaria'));
        // return $this->redirectToRoute('configurables_preguntas_participacion_comunitaria_index');
    }

    /**
     * Creates a form to delete a AdminPreguntasParticipacionComunitaria entity.
     *
     * @param AdminPreguntasParticipacionComunitaria $adminPreguntasParticipacionComunitarium The AdminPreguntasParticipacionComunitaria entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminPreguntasParticipacionComunitaria $adminPreguntasParticipacionComunitarium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurables_preguntas_participacion_comunitaria_delete', array('id' => $adminPreguntasParticipacionComunitarium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\AdminMecanismoInformacion;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\AdminMecanismoInformacionType;

/**
 * AdminMecanismoInformacion controller.
 *
 */
class AdminMecanismoInformacionController extends BaseController
{
    /**
     * Lists all AdminMecanismoInformacion entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminMecanismoInformacions = $em->getRepository(\SICBundle\Entity\AdminMecanismoInformacion::class)->findAll();

        return $this->render('adminmecanismoinformacion/index.html.twig', array(
            'adminMecanismoInformacions' => $adminMecanismoInformacions,
        ));
    }

    /**
     * Creates a new AdminMecanismoInformacion entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminMecanismoInformacion = new AdminMecanismoInformacion();
        $form = $this->createForm(AdminMecanismoInformacionType::class, $adminMecanismoInformacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminMecanismoInformacion);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Mecanismo de Información a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'mecanismoinformacion'));
            // return $this->redirectToRoute('configurables_tipo_mecanismo_informacion_show', array('id' => $adminMecanismoInformacion->getId()));
        }

        return $this->render('adminmecanismoinformacion/new.html.twig', array(
            'adminMecanismoInformacion' => $adminMecanismoInformacion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminMecanismoInformacion entity.
     *
     */
    public function showAction(AdminMecanismoInformacion $adminMecanismoInformacion)
    {
        $deleteForm = $this->createDeleteForm($adminMecanismoInformacion);

        return $this->render('adminmecanismoinformacion/show.html.twig', array(
            'adminMecanismoInformacion' => $adminMecanismoInformacion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminMecanismoInformacion entity.
     *
     */
    public function editAction(Request $request, AdminMecanismoInformacion $adminMecanismoInformacion)
    {
        $deleteForm = $this->createDeleteForm($adminMecanismoInformacion);
        $editForm = $this->createForm(AdminMecanismoInformacionType::class, $adminMecanismoInformacion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Mecanismo de Información');
            $em->persist($bitacora);
            $em->persist($adminMecanismoInformacion);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'mecanismoinformacion'));
            // return $this->redirectToRoute('configurables_tipo_mecanismo_informacion_edit', array('id' => $adminMecanismoInformacion->getId()));
        }

        return $this->render('adminmecanismoinformacion/edit.html.twig', array(
            'adminMecanismoInformacion' => $adminMecanismoInformacion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminMecanismoInformacion entity.
     *
     */
    public function deleteAction(Request $request, AdminMecanismoInformacion $adminMecanismoInformacion)
    {
        $form = $this->createDeleteForm($adminMecanismoInformacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminMecanismoInformacion->getNombre().' de los parámetros de Aguas Blancas del sistema');
            $em->persist($bitacora);
            $em->remove($adminMecanismoInformacion);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'mecanismoinformacion'));
        // return $this->redirectToRoute('configurables_tipo_mecanismo_informacion_index');
    }

    /**
     * Creates a form to delete a AdminMecanismoInformacion entity.
     *
     * @param AdminMecanismoInformacion $adminMecanismoInformacion The AdminMecanismoInformacion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminMecanismoInformacion $adminMecanismoInformacion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurables_tipo_mecanismo_informacion_delete', array('id' => $adminMecanismoInformacion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

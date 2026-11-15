<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminUbicacionTrabajo;
use SICBundle\Form\AdminUbicacionTrabajoType;

/**
 * AdminUbicacionTrabajo controller.
 *
 */
class AdminUbicacionTrabajoController extends BaseController
{
    /**
     * Lists all AdminUbicacionTrabajo entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminUbicacionTrabajos = $em->getRepository(\SICBundle\Entity\AdminUbicacionTrabajo::class)->findAll();

        return $this->render('adminubicaciontrabajo/index.html.twig', array(
            'adminUbicacionTrabajos' => $adminUbicacionTrabajos,
        ));
    }

    /**
     * Creates a new AdminUbicacionTrabajo entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminUbicacionTrabajo = new AdminUbicacionTrabajo();
        $form = $this->createForm(AdminUbicacionTrabajoType::class, $adminUbicacionTrabajo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminUbicacionTrabajo);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Ubicación de Trabajo a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'ubicaciontrabajo'));
            // return $this->redirectToRoute('configurable_ubicacion-trabajo_show', array('id' => $adminUbicacionTrabajo->getId()));
        }

        return $this->render('adminubicaciontrabajo/new.html.twig', array(
            'adminUbicacionTrabajo' => $adminUbicacionTrabajo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminUbicacionTrabajo entity.
     *
     */
    public function showAction(AdminUbicacionTrabajo $adminUbicacionTrabajo)
    {
        $deleteForm = $this->createDeleteForm($adminUbicacionTrabajo);

        return $this->render('adminubicaciontrabajo/show.html.twig', array(
            'adminUbicacionTrabajo' => $adminUbicacionTrabajo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminUbicacionTrabajo entity.
     *
     */
    public function editAction(Request $request, AdminUbicacionTrabajo $adminUbicacionTrabajo)
    {
        $deleteForm = $this->createDeleteForm($adminUbicacionTrabajo);
        $editForm = $this->createForm(AdminUbicacionTrabajoType::class, $adminUbicacionTrabajo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminUbicacionTrabajo);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Ubicación de Trabajo');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'ubicaciontrabajo'));
            // return $this->redirectToRoute('configurable_ubicacion-trabajo_edit', array('id' => $adminUbicacionTrabajo->getId()));
        }

        return $this->render('adminubicaciontrabajo/edit.html.twig', array(
            'adminUbicacionTrabajo' => $adminUbicacionTrabajo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminUbicacionTrabajo entity.
     *
     */
    public function deleteAction(Request $request, AdminUbicacionTrabajo $adminUbicacionTrabajo)
    {
        $form = $this->createDeleteForm($adminUbicacionTrabajo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminUbicacionTrabajo);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminUbicacionTrabajo->getNombre().' de los parámetros de Ubicación Trabajo del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'ubicaciontrabajo'));
        // return $this->redirectToRoute('configurable_ubicacion-trabajo_index');
    }

    /**
     * Creates a form to delete a AdminUbicacionTrabajo entity.
     *
     * @param AdminUbicacionTrabajo $adminUbicacionTrabajo The AdminUbicacionTrabajo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminUbicacionTrabajo $adminUbicacionTrabajo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurable_ubicacion-trabajo_delete', array('id' => $adminUbicacionTrabajo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

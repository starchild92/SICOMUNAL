<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminSalubridadVivienda;
use SICBundle\Form\AdminSalubridadViviendaType;

/**
 * AdminSalubridadVivienda controller.
 *
 */
class AdminSalubridadViviendaController extends BaseController
{
    /**
     * Lists all AdminSalubridadVivienda entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminSalubridadViviendas = $em->getRepository('SICBundle:AdminSalubridadVivienda')->findAll();

        return $this->render('adminsalubridadvivienda/index.html.twig', array(
            'adminSalubridadViviendas' => $adminSalubridadViviendas,
        ));
    }

    /**
     * Creates a new AdminSalubridadVivienda entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminSalubridadVivienda = new AdminSalubridadVivienda();
        $form = $this->createForm('SICBundle\Form\AdminSalubridadViviendaType', $adminSalubridadVivienda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminSalubridadVivienda);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo de Salubridad Vivienda a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'salubridadvivienda'));
            // return $this->redirectToRoute('configurable_tipo_salubridad_show', array('id' => $adminSalubridadVivienda->getId()));
        }

        return $this->render('adminsalubridadvivienda/new.html.twig', array(
            'adminSalubridadVivienda' => $adminSalubridadVivienda,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminSalubridadVivienda entity.
     *
     */
    public function showAction(AdminSalubridadVivienda $adminSalubridadVivienda)
    {
        $deleteForm = $this->createDeleteForm($adminSalubridadVivienda);

        return $this->render('adminsalubridadvivienda/show.html.twig', array(
            'adminSalubridadVivienda' => $adminSalubridadVivienda,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminSalubridadVivienda entity.
     *
     */
    public function editAction(Request $request, AdminSalubridadVivienda $adminSalubridadVivienda)
    {
        $deleteForm = $this->createDeleteForm($adminSalubridadVivienda);
        $editForm = $this->createForm('SICBundle\Form\AdminSalubridadViviendaType', $adminSalubridadVivienda);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminSalubridadVivienda);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Salubridad Vivienda');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'salubridadvivienda'));
            // return $this->redirectToRoute('configurable_tipo_salubridad_edit', array('id' => $adminSalubridadVivienda->getId()));
        }

        return $this->render('adminsalubridadvivienda/edit.html.twig', array(
            'adminSalubridadVivienda' => $adminSalubridadVivienda,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminSalubridadVivienda entity.
     *
     */
    public function deleteAction(Request $request, AdminSalubridadVivienda $adminSalubridadVivienda)
    {
        $form = $this->createDeleteForm($adminSalubridadVivienda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminSalubridadVivienda);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminSalubridadVivienda->getNombre().' de los parámetros de Tipo de Salubridad Vivienda del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'salubridadvivienda'));
        // return $this->redirectToRoute('configurable_tipo_salubridad_index');
    }

    /**
     * Creates a form to delete a AdminSalubridadVivienda entity.
     *
     * @param AdminSalubridadVivienda $adminSalubridadVivienda The AdminSalubridadVivienda entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminSalubridadVivienda $adminSalubridadVivienda)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurable_tipo_salubridad_delete', array('id' => $adminSalubridadVivienda->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

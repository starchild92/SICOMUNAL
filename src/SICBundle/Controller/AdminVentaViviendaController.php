<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminVentaVivienda;
use SICBundle\Form\AdminVentaViviendaType;

/**
 * AdminVentaVivienda controller.
 *
 */
class AdminVentaViviendaController extends BaseController
{
    /**
     * Lists all AdminVentaVivienda entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminVentaViviendas = $em->getRepository(\SICBundle\Entity\AdminVentaVivienda::class)->findAll();

        return $this->render('adminventavivienda/index.html.twig', array(
            'adminVentaViviendas' => $adminVentaViviendas,
        ));
    }

    /**
     * Creates a new AdminVentaVivienda entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminVentaVivienda = new AdminVentaVivienda();
        $form = $this->createForm('SICBundle\Form\AdminVentaViviendaType', $adminVentaVivienda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminVentaVivienda);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Venta Vivienda a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'ventavivienda'));
            // return $this->redirectToRoute('configurable_venta_vivenda_show', array('id' => $adminVentaVivienda->getId()));
        }

        return $this->render('adminventavivienda/new.html.twig', array(
            'adminVentaVivienda' => $adminVentaVivienda,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminVentaVivienda entity.
     *
     */
    public function showAction(AdminVentaVivienda $adminVentaVivienda)
    {
        $deleteForm = $this->createDeleteForm($adminVentaVivienda);

        return $this->render('adminventavivienda/show.html.twig', array(
            'adminVentaVivienda' => $adminVentaVivienda,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminVentaVivienda entity.
     *
     */
    public function editAction(Request $request, AdminVentaVivienda $adminVentaVivienda)
    {
        $deleteForm = $this->createDeleteForm($adminVentaVivienda);
        $editForm = $this->createForm('SICBundle\Form\AdminVentaViviendaType', $adminVentaVivienda);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminVentaVivienda);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Venta Vivienda');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'ventavivienda'));
            // return $this->redirectToRoute('configurable_venta_vivenda_edit', array('id' => $adminVentaVivienda->getId()));
        }

        return $this->render('adminventavivienda/edit.html.twig', array(
            'adminVentaVivienda' => $adminVentaVivienda,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminVentaVivienda entity.
     *
     */
    public function deleteAction(Request $request, AdminVentaVivienda $adminVentaVivienda)
    {
        $form = $this->createDeleteForm($adminVentaVivienda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminVentaVivienda);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminVentaVivienda->getNombre().' de los parámetros de Venta Vivienda del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'ventavivienda'));
        // return $this->redirectToRoute('configurable_venta_vivenda_index');
    }

    /**
     * Creates a form to delete a AdminVentaVivienda entity.
     *
     * @param AdminVentaVivienda $adminVentaVivienda The AdminVentaVivienda entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminVentaVivienda $adminVentaVivienda)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurable_venta_vivenda_delete', array('id' => $adminVentaVivienda->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

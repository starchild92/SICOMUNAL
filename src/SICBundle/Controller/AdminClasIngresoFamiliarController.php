<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\AdminClasIngresoFamiliar;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\AdminClasIngresoFamiliarType;

/**
 * AdminClasIngresoFamiliar controller.
 *
 */
class AdminClasIngresoFamiliarController extends BaseController
{
    /**
     * Lists all AdminClasIngresoFamiliar entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminClasIngresoFamiliars = $em->getRepository('SICBundle:AdminClasIngresoFamiliar')->findAll();

        return $this->render('adminclasingresofamiliar/index.html.twig', array(
            'adminClasIngresoFamiliars' => $adminClasIngresoFamiliars,
        ));
    }

    /**
     * Creates a new AdminClasIngresoFamiliar entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminClasIngresoFamiliar = new AdminClasIngresoFamiliar();
        $form = $this->createForm('SICBundle\Form\AdminClasIngresoFamiliarType', $adminClasIngresoFamiliar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de la Clasificación del Ingreso Familiar a los parámetros del sistema');
            $em->persist($bitacora);
            $em->persist($adminClasIngresoFamiliar);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'ingresofamiliar'));
            // return $this->redirectToRoute('configurable_clasificacion_ingreso_familiar_show', array('id' => $adminClasIngresoFamiliar->getId()));
        }

        return $this->render('adminclasingresofamiliar/new.html.twig', array(
            'adminClasIngresoFamiliar' => $adminClasIngresoFamiliar,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminClasIngresoFamiliar entity.
     *
     */
    public function showAction(AdminClasIngresoFamiliar $adminClasIngresoFamiliar)
    {
        $deleteForm = $this->createDeleteForm($adminClasIngresoFamiliar);

        return $this->render('adminclasingresofamiliar/show.html.twig', array(
            'adminClasIngresoFamiliar' => $adminClasIngresoFamiliar,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminClasIngresoFamiliar entity.
     *
     */
    public function editAction(Request $request, AdminClasIngresoFamiliar $adminClasIngresoFamiliar)
    {
        $deleteForm = $this->createDeleteForm($adminClasIngresoFamiliar);
        $editForm = $this->createForm('SICBundle\Form\AdminClasIngresoFamiliarType', $adminClasIngresoFamiliar);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de la Clasificación del Ingreso Familiar');
            $em->persist($bitacora);
            $em->persist($adminClasIngresoFamiliar);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'ingresofamiliar'));
            // return $this->redirectToRoute('configurable_clasificacion_ingreso_familiar_edit', array('id' => $adminClasIngresoFamiliar->getId()));
        }

        return $this->render('adminclasingresofamiliar/edit.html.twig', array(
            'adminClasIngresoFamiliar' => $adminClasIngresoFamiliar,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminClasIngresoFamiliar entity.
     *
     */
    public function deleteAction(Request $request, AdminClasIngresoFamiliar $adminClasIngresoFamiliar)
    {
        $form = $this->createDeleteForm($adminClasIngresoFamiliar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminClasIngresoFamiliar->getNombre().' de los parámetros de la Clasificación Ingreso Familiar del sistema');
            $em->persist($bitacora);
            $em->remove($adminClasIngresoFamiliar);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'ingresofamiliar'));
        // return $this->redirectToRoute('configurable_clasificacion_ingreso_familiar_index');
    }

    /**
     * Creates a form to delete a AdminClasIngresoFamiliar entity.
     *
     * @param AdminClasIngresoFamiliar $adminClasIngresoFamiliar The AdminClasIngresoFamiliar entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminClasIngresoFamiliar $adminClasIngresoFamiliar)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurable_clasificacion_ingreso_familiar_delete', array('id' => $adminClasIngresoFamiliar->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

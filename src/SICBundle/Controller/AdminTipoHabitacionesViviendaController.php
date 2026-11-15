<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminTipoHabitacionesVivienda;
use SICBundle\Form\AdminTipoHabitacionesViviendaType;

/**
 * AdminTipoHabitacionesVivienda controller.
 *
 */
class AdminTipoHabitacionesViviendaController extends BaseController
{
    /**
     * Lists all AdminTipoHabitacionesVivienda entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminTipoHabitacionesViviendas = $em->getRepository(\SICBundle\Entity\AdminTipoHabitacionesVivienda::class)->findAll();

        return $this->render('admintipohabitacionesvivienda/index.html.twig', array(
            'adminTipoHabitacionesViviendas' => $adminTipoHabitacionesViviendas,
        ));
    }

    /**
     * Creates a new AdminTipoHabitacionesVivienda entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminTipoHabitacionesVivienda = new AdminTipoHabitacionesVivienda();
        $form = $this->createForm('SICBundle\Form\AdminTipoHabitacionesViviendaType', $adminTipoHabitacionesVivienda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoHabitacionesVivienda);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo Habitación a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipohabitacionesvivienda'));
            // return $this->redirectToRoute('configurable_tipo_habitaciones_show', array('id' => $adminTipoHabitacionesVivienda->getId()));
        }

        return $this->render('admintipohabitacionesvivienda/new.html.twig', array(
            'adminTipoHabitacionesVivienda' => $adminTipoHabitacionesVivienda,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminTipoHabitacionesVivienda entity.
     *
     */
    public function showAction(AdminTipoHabitacionesVivienda $adminTipoHabitacionesVivienda)
    {
        $deleteForm = $this->createDeleteForm($adminTipoHabitacionesVivienda);

        return $this->render('admintipohabitacionesvivienda/show.html.twig', array(
            'adminTipoHabitacionesVivienda' => $adminTipoHabitacionesVivienda,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminTipoHabitacionesVivienda entity.
     *
     */
    public function editAction(Request $request, AdminTipoHabitacionesVivienda $adminTipoHabitacionesVivienda)
    {
        $deleteForm = $this->createDeleteForm($adminTipoHabitacionesVivienda);
        $editForm = $this->createForm('SICBundle\Form\AdminTipoHabitacionesViviendaType', $adminTipoHabitacionesVivienda);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoHabitacionesVivienda);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Habitación');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipohabitacionesvivienda'));
            // return $this->redirectToRoute('configurable_tipo_habitaciones_edit', array('id' => $adminTipoHabitacionesVivienda->getId()));
        }

        return $this->render('admintipohabitacionesvivienda/edit.html.twig', array(
            'adminTipoHabitacionesVivienda' => $adminTipoHabitacionesVivienda,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminTipoHabitacionesVivienda entity.
     *
     */
    public function deleteAction(Request $request, AdminTipoHabitacionesVivienda $adminTipoHabitacionesVivienda)
    {
        $form = $this->createDeleteForm($adminTipoHabitacionesVivienda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminTipoHabitacionesVivienda);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminTipoHabitacionesVivienda->getNombre().' de los parámetros de Tipo de Habitación del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipohabitacionesvivienda'));
        // return $this->redirectToRoute('configurable_tipo_habitaciones_index');
    }

    /**
     * Creates a form to delete a AdminTipoHabitacionesVivienda entity.
     *
     * @param AdminTipoHabitacionesVivienda $adminTipoHabitacionesVivienda The AdminTipoHabitacionesVivienda entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminTipoHabitacionesVivienda $adminTipoHabitacionesVivienda)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurable_tipo_habitaciones_delete', array('id' => $adminTipoHabitacionesVivienda->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

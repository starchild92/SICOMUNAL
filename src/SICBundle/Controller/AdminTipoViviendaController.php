<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminTipoVivienda;
use SICBundle\Form\AdminTipoViviendaType;

/**
 * AdminTipoVivienda controller.
 *
 */
class AdminTipoViviendaController extends BaseController
{
    /**
     * Lists all AdminTipoVivienda entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminTipoViviendas = $em->getRepository(\SICBundle\Entity\AdminTipoVivienda::class)->findAll();

        return $this->render('admintipovivienda/index.html.twig', array(
            'adminTipoViviendas' => $adminTipoViviendas,
        ));
    }

    /**
     * Creates a new AdminTipoVivienda entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminTipoVivienda = new AdminTipoVivienda();
        $form = $this->createForm('SICBundle\Form\AdminTipoViviendaType', $adminTipoVivienda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoVivienda);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo Vivienda a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipovivienda'));
            // return $this->redirectToRoute('configurable_tipo_vivienda_show', array('id' => $adminTipoVivienda->getId()));
        }

        return $this->render('admintipovivienda/new.html.twig', array(
            'adminTipoVivienda' => $adminTipoVivienda,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminTipoVivienda entity.
     *
     */
    public function showAction(AdminTipoVivienda $adminTipoVivienda)
    {
        $deleteForm = $this->createDeleteForm($adminTipoVivienda);

        return $this->render('admintipovivienda/show.html.twig', array(
            'adminTipoVivienda' => $adminTipoVivienda,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminTipoVivienda entity.
     *
     */
    public function editAction(Request $request, AdminTipoVivienda $adminTipoVivienda)
    {
        $deleteForm = $this->createDeleteForm($adminTipoVivienda);
        $editForm = $this->createForm('SICBundle\Form\AdminTipoViviendaType', $adminTipoVivienda);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoVivienda);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Vivienda');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipovivienda'));
            // return $this->redirectToRoute('configurable_tipo_vivienda_edit', array('id' => $adminTipoVivienda->getId()));
        }

        return $this->render('admintipovivienda/edit.html.twig', array(
            'adminTipoVivienda' => $adminTipoVivienda,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminTipoVivienda entity.
     *
     */
    public function deleteAction(Request $request, AdminTipoVivienda $adminTipoVivienda)
    {
        $form = $this->createDeleteForm($adminTipoVivienda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminTipoVivienda);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminTipoVivienda->getNombre().' de los parámetros de Tipo de Vivienda del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipovivienda'));
        // return $this->redirectToRoute('configurable_tipo_vivienda_index');
    }

    /**
     * Creates a form to delete a AdminTipoVivienda entity.
     *
     * @param AdminTipoVivienda $adminTipoVivienda The AdminTipoVivienda entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminTipoVivienda $adminTipoVivienda)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurable_tipo_vivienda_delete', array('id' => $adminTipoVivienda->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

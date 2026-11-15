<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminTipoIngresos;
use SICBundle\Form\AdminTipoIngresosType;

/**
 * AdminTipoIngresos controller.
 *
 */
class AdminTipoIngresosController extends BaseController
{
    /**
     * Lists all AdminTipoIngresos entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminTipoIngresos = $em->getRepository(\SICBundle\Entity\AdminTipoIngresos::class)->findAll();

        return $this->render('admintipoingresos/index.html.twig', array(
            'adminTipoIngresos' => $adminTipoIngresos,
        ));
    }

    /**
     * Creates a new AdminTipoIngresos entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminTipoIngreso = new AdminTipoIngresos();
        $form = $this->createForm('SICBundle\Form\AdminTipoIngresosType', $adminTipoIngreso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoIngreso);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo Ingreso a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipoingresos'));
            // return $this->redirectToRoute('configurable_tipos_ingresos_show', array('id' => $adminTipoIngreso->getId()));
        }

        return $this->render('admintipoingresos/new.html.twig', array(
            'adminTipoIngreso' => $adminTipoIngreso,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminTipoIngresos entity.
     *
     */
    public function showAction(AdminTipoIngresos $adminTipoIngreso)
    {
        $deleteForm = $this->createDeleteForm($adminTipoIngreso);

        return $this->render('admintipoingresos/show.html.twig', array(
            'adminTipoIngreso' => $adminTipoIngreso,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminTipoIngresos entity.
     *
     */
    public function editAction(Request $request, AdminTipoIngresos $adminTipoIngreso)
    {
        $deleteForm = $this->createDeleteForm($adminTipoIngreso);
        $editForm = $this->createForm('SICBundle\Form\AdminTipoIngresosType', $adminTipoIngreso);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoIngreso);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Ingreso');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipoingresos'));
            // return $this->redirectToRoute('configurable_tipos_ingresos_edit', array('id' => $adminTipoIngreso->getId()));
        }

        return $this->render('admintipoingresos/edit.html.twig', array(
            'adminTipoIngreso' => $adminTipoIngreso,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminTipoIngresos entity.
     *
     */
    public function deleteAction(Request $request, AdminTipoIngresos $adminTipoIngreso)
    {
        $form = $this->createDeleteForm($adminTipoIngreso);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminTipoIngreso);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminTipoIngreso->getNombre().' de los parámetros de Tipo de Ingreso del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipoingresos'));
        // return $this->redirectToRoute('configurable_tipos_ingresos_index');
    }

    /**
     * Creates a form to delete a AdminTipoIngresos entity.
     *
     * @param AdminTipoIngresos $adminTipoIngreso The AdminTipoIngresos entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminTipoIngresos $adminTipoIngreso)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurable_tipos_ingresos_delete', array('id' => $adminTipoIngreso->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

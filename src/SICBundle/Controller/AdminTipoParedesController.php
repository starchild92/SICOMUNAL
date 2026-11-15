<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminTipoParedes;
use SICBundle\Form\AdminTipoParedesType;

/**
 * AdminTipoParedes controller.
 *
 */
class AdminTipoParedesController extends BaseController
{
    /**
     * Lists all AdminTipoParedes entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminTipoParedes = $em->getRepository(\SICBundle\Entity\AdminTipoParedes::class)->findAll();

        return $this->render('admintipoparedes/index.html.twig', array(
            'adminTipoParedes' => $adminTipoParedes,
        ));
    }

    /**
     * Creates a new AdminTipoParedes entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminTipoParede = new AdminTipoParedes();
        $form = $this->createForm('SICBundle\Form\AdminTipoParedesType', $adminTipoParede);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoParede);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo de Pared a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipoparedes'));
            // return $this->redirectToRoute('configurable_tipo_paredes_show', array('id' => $adminTipoParede->getId()));
        }

        return $this->render('admintipoparedes/new.html.twig', array(
            'adminTipoParede' => $adminTipoParede,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminTipoParedes entity.
     *
     */
    public function showAction(AdminTipoParedes $adminTipoParede)
    {
        $deleteForm = $this->createDeleteForm($adminTipoParede);

        return $this->render('admintipoparedes/show.html.twig', array(
            'adminTipoParede' => $adminTipoParede,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminTipoParedes entity.
     *
     */
    public function editAction(Request $request, AdminTipoParedes $adminTipoParede)
    {
        $deleteForm = $this->createDeleteForm($adminTipoParede);
        $editForm = $this->createForm('SICBundle\Form\AdminTipoParedesType', $adminTipoParede);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoParede);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Pared');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipoparedes'));
            // return $this->redirectToRoute('configurable_tipo_paredes_edit', array('id' => $adminTipoParede->getId()));
        }

        return $this->render('admintipoparedes/edit.html.twig', array(
            'adminTipoParede' => $adminTipoParede,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminTipoParedes entity.
     *
     */
    public function deleteAction(Request $request, AdminTipoParedes $adminTipoParede)
    {
        $form = $this->createDeleteForm($adminTipoParede);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminTipoParede);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminTipoParede->getNombre().' de los parámetros de Tipo de Paredes del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipoparedes'));
        // return $this->redirectToRoute('configurable_tipo_paredes_index');
    }

    /**
     * Creates a form to delete a AdminTipoParedes entity.
     *
     * @param AdminTipoParedes $adminTipoParede The AdminTipoParedes entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminTipoParedes $adminTipoParede)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurable_tipo_paredes_delete', array('id' => $adminTipoParede->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

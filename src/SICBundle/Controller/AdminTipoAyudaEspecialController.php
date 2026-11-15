<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminTipoAyudaEspecial;
use SICBundle\Form\AdminTipoAyudaEspecialType;

/**
 * AdminTipoAyudaEspecial controller.
 *
 */
class AdminTipoAyudaEspecialController extends BaseController
{
    /**
     * Lists all AdminTipoAyudaEspecial entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminTipoAyudaEspecials = $em->getRepository('SICBundle:AdminTipoAyudaEspecial')->findAll();

        return $this->render('admintipoayudaespecial/index.html.twig', array(
            'adminTipoAyudaEspecials' => $adminTipoAyudaEspecials,
        ));
    }

    /**
     * Creates a new AdminTipoAyudaEspecial entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminTipoAyudaEspecial = new AdminTipoAyudaEspecial();
        $form = $this->createForm('SICBundle\Form\AdminTipoAyudaEspecialType', $adminTipoAyudaEspecial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoAyudaEspecial);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo de Ayuda Especial a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'ayudaespecial'));
            // return $this->redirectToRoute('configurable_tipo_ayuda_especial_show', array('id' => $adminTipoAyudaEspecial->getId()));
        }

        return $this->render('admintipoayudaespecial/new.html.twig', array(
            'adminTipoAyudaEspecial' => $adminTipoAyudaEspecial,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminTipoAyudaEspecial entity.
     *
     */
    public function showAction(AdminTipoAyudaEspecial $adminTipoAyudaEspecial)
    {
        $deleteForm = $this->createDeleteForm($adminTipoAyudaEspecial);

        return $this->render('admintipoayudaespecial/show.html.twig', array(
            'adminTipoAyudaEspecial' => $adminTipoAyudaEspecial,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminTipoAyudaEspecial entity.
     *
     */
    public function editAction(Request $request, AdminTipoAyudaEspecial $adminTipoAyudaEspecial)
    {
        $deleteForm = $this->createDeleteForm($adminTipoAyudaEspecial);
        $editForm = $this->createForm('SICBundle\Form\AdminTipoAyudaEspecialType', $adminTipoAyudaEspecial);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoAyudaEspecial);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Ayuda Especial');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'ayudaespecial'));
            // return $this->redirectToRoute('configurable_tipo_ayuda_especial_edit', array('id' => $adminTipoAyudaEspecial->getId()));
        }

        return $this->render('admintipoayudaespecial/edit.html.twig', array(
            'adminTipoAyudaEspecial' => $adminTipoAyudaEspecial,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminTipoAyudaEspecial entity.
     *
     */
    public function deleteAction(Request $request, AdminTipoAyudaEspecial $adminTipoAyudaEspecial)
    {
        $form = $this->createDeleteForm($adminTipoAyudaEspecial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminTipoAyudaEspecial);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminTipoAyudaEspecial->getNombre().' de los parámetros de Tipo de Ayuda Especial del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'ayudaespecial'));
        // return $this->redirectToRoute('configurable_tipo_ayuda_especial_index');
    }

    /**
     * Creates a form to delete a AdminTipoAyudaEspecial entity.
     *
     * @param AdminTipoAyudaEspecial $adminTipoAyudaEspecial The AdminTipoAyudaEspecial entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminTipoAyudaEspecial $adminTipoAyudaEspecial)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurable_tipo_ayuda_especial_delete', array('id' => $adminTipoAyudaEspecial->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

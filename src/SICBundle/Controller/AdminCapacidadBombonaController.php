<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\AdminCapacidadBombona;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\AdminCapacidadBombonaType;

/**
 * AdminCapacidadBombona controller.
 *
 */
class AdminCapacidadBombonaController extends BaseController
{
    /**
     * Lists all AdminCapacidadBombona entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminCapacidadBombonas = $em->getRepository('SICBundle:AdminCapacidadBombona')->findAll();

        return $this->render('admincapacidadbombona/index.html.twig', array(
            'adminCapacidadBombonas' => $adminCapacidadBombonas,
        ));
    }

    /**
     * Creates a new AdminCapacidadBombona entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminCapacidadBombona = new AdminCapacidadBombona();
        $form = $this->createForm('SICBundle\Form\AdminCapacidadBombonaType', $adminCapacidadBombona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminCapacidadBombona);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nueva Capacidad de Bombona a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'capacidadbombona'));
        }

        return $this->render('admincapacidadbombona/new.html.twig', array(
            'adminCapacidadBombona' => $adminCapacidadBombona,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminCapacidadBombona entity.
     *
     */
    public function showAction(AdminCapacidadBombona $adminCapacidadBombona)
    {
        $deleteForm = $this->createDeleteForm($adminCapacidadBombona);

        return $this->render('admincapacidadbombona/show.html.twig', array(
            'adminCapacidadBombona' => $adminCapacidadBombona,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminCapacidadBombona entity.
     *
     */
    public function editAction(Request $request, AdminCapacidadBombona $adminCapacidadBombona)
    {
        $deleteForm = $this->createDeleteForm($adminCapacidadBombona);
        $editForm = $this->createForm('SICBundle\Form\AdminCapacidadBombonaType', $adminCapacidadBombona);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminCapacidadBombona);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Capacidad de Bombona');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'capacidadbombona'));
            // return $this->redirectToRoute('admincapacidadbombona_edit', array('id' => $adminCapacidadBombona->getId()));
        }

        return $this->render('admincapacidadbombona/edit.html.twig', array(
            'adminCapacidadBombona' => $adminCapacidadBombona,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminCapacidadBombona entity.
     *
     */
    public function deleteAction(Request $request, AdminCapacidadBombona $adminCapacidadBombona)
    {
        $form = $this->createDeleteForm($adminCapacidadBombona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminCapacidadBombona);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminEmpresaGa->getNombre().' de los parámetros de Capacidad de Bombona del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'capacidadbombona'));
        // return $this->redirectToRoute('admincapacidadbombona_index');
    }

    /**
     * Creates a form to delete a AdminCapacidadBombona entity.
     *
     * @param AdminCapacidadBombona $adminCapacidadBombona The AdminCapacidadBombona entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminCapacidadBombona $adminCapacidadBombona)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admincapacidadbombona_delete', array('id' => $adminCapacidadBombona->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

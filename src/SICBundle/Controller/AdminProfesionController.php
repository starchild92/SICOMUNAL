<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminProfesion;
use SICBundle\Form\AdminProfesionType;

/**
 * AdminProfesion controller.
 *
 */
class AdminProfesionController extends BaseController
{
    /**
     * Lists all AdminProfesion entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminProfesions = $em->getRepository('SICBundle:AdminProfesion')->findAll();

        return $this->render('adminprofesion/index.html.twig', array(
            'adminProfesions' => $adminProfesions,
        ));
    }

    /**
     * Creates a new AdminProfesion entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminProfesion = new AdminProfesion();
        $form = $this->createForm('SICBundle\Form\AdminProfesionType', $adminProfesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminProfesion);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo de Profesión a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'profesiones'));
            // return $this->redirectToRoute('configurable_profesiones_show', array('id' => $adminProfesion->getId()));
        }

        return $this->render('adminprofesion/new.html.twig', array(
            'adminProfesion' => $adminProfesion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminProfesion entity.
     *
     */
    public function showAction(AdminProfesion $adminProfesion)
    {
        $deleteForm = $this->createDeleteForm($adminProfesion);

        return $this->render('adminprofesion/show.html.twig', array(
            'adminProfesion' => $adminProfesion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminProfesion entity.
     *
     */
    public function editAction(Request $request, AdminProfesion $adminProfesion)
    {
        $deleteForm = $this->createDeleteForm($adminProfesion);
        $editForm = $this->createForm('SICBundle\Form\AdminProfesionType', $adminProfesion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminProfesion);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Profesión');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'profesiones'));
            // return $this->redirectToRoute('configurable_profesiones_edit', array('id' => $adminProfesion->getId()));
        }

        return $this->render('adminprofesion/edit.html.twig', array(
            'adminProfesion' => $adminProfesion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminProfesion entity.
     *
     */
    public function deleteAction(Request $request, AdminProfesion $adminProfesion)
    {
        $form = $this->createDeleteForm($adminProfesion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminProfesion);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminProfesion->getNombre().' de los parámetros de Tipo de Profesión del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'profesiones'));
        // return $this->redirectToRoute('configurable_profesiones_index');
    }

    /**
     * Creates a form to delete a AdminProfesion entity.
     *
     * @param AdminProfesion $adminProfesion The AdminProfesion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminProfesion $adminProfesion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurable_profesiones_delete', array('id' => $adminProfesion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

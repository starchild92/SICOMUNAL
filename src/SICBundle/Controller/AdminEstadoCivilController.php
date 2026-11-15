<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\AdminEstadoCivil;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\AdminEstadoCivilType;

/**
 * AdminEstadoCivil controller.
 *
 */
class AdminEstadoCivilController extends BaseController
{
    /**
     * Lists all AdminEstadoCivil entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminEstadoCivils = $em->getRepository('SICBundle:AdminEstadoCivil')->findAll();

        return $this->render('adminestadocivil/index.html.twig', array(
            'adminEstadoCivils' => $adminEstadoCivils,
        ));
    }

    /**
     * Creates a new AdminEstadoCivil entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminEstadoCivil = new AdminEstadoCivil();
        $form = $this->createForm('SICBundle\Form\AdminEstadoCivilType', $adminEstadoCivil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Estado Civil a los parámetros del sistema');
            $em->persist($bitacora);
            $em->persist($adminEstadoCivil);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'estadocivil'));
            // return $this->redirectToRoute('configurable_estado_civil_show', array('id' => $adminEstadoCivil->getId()));
        }

        return $this->render('adminestadocivil/new.html.twig', array(
            'adminEstadoCivil' => $adminEstadoCivil,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminEstadoCivil entity.
     *
     */
    public function showAction(AdminEstadoCivil $adminEstadoCivil)
    {
        $deleteForm = $this->createDeleteForm($adminEstadoCivil);

        return $this->render('adminestadocivil/show.html.twig', array(
            'adminEstadoCivil' => $adminEstadoCivil,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminEstadoCivil entity.
     *
     */
    public function editAction(Request $request, AdminEstadoCivil $adminEstadoCivil)
    {
        $deleteForm = $this->createDeleteForm($adminEstadoCivil);
        $editForm = $this->createForm('SICBundle\Form\AdminEstadoCivilType', $adminEstadoCivil);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Estado Civil');
            $em->persist($bitacora);
            $em->persist($adminEstadoCivil);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'estadocivil'));
            // return $this->redirectToRoute('configurable_estado_civil_edit', array('id' => $adminEstadoCivil->getId()));
        }

        return $this->render('adminestadocivil/edit.html.twig', array(
            'adminEstadoCivil' => $adminEstadoCivil,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminEstadoCivil entity.
     *
     */
    public function deleteAction(Request $request, AdminEstadoCivil $adminEstadoCivil)
    {
        $form = $this->createDeleteForm($adminEstadoCivil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminEstadoCivil->getNombre().' de los parámetros de Estado Civil del sistema');
            $em->persist($bitacora);
            $em->remove($adminEstadoCivil);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'estadocivil'));
        // return $this->redirectToRoute('configurable_estado_civil_index');
    }

    /**
     * Creates a form to delete a AdminEstadoCivil entity.
     *
     * @param AdminEstadoCivil $adminEstadoCivil The AdminEstadoCivil entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminEstadoCivil $adminEstadoCivil)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurable_estado_civil_delete', array('id' => $adminEstadoCivil->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

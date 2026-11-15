<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminPensionadoInstitucion;
use SICBundle\Form\AdminPensionadoInstitucionType;

/**
 * AdminPensionadoInstitucion controller.
 *
 */
class AdminPensionadoInstitucionController extends BaseController
{
    /**
     * Lists all AdminPensionadoInstitucion entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminPensionadoInstitucions = $em->getRepository(\SICBundle\Entity\AdminPensionadoInstitucion::class)->findAll();

        return $this->render('adminpensionadoinstitucion/index.html.twig', array(
            'adminPensionadoInstitucions' => $adminPensionadoInstitucions,
        ));
    }

    /**
     * Creates a new AdminPensionadoInstitucion entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminPensionadoInstitucion = new AdminPensionadoInstitucion();
        $form = $this->createForm('SICBundle\Form\AdminPensionadoInstitucionType', $adminPensionadoInstitucion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminPensionadoInstitucion);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Institución Pensionados a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'pensionadoinstitucion'));
            // return $this->redirectToRoute('configurable_pensionados_instituciones_show', array('id' => $adminPensionadoInstitucion->getId()));
        }

        return $this->render('adminpensionadoinstitucion/new.html.twig', array(
            'adminPensionadoInstitucion' => $adminPensionadoInstitucion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminPensionadoInstitucion entity.
     *
     */
    public function showAction(AdminPensionadoInstitucion $adminPensionadoInstitucion)
    {
        $deleteForm = $this->createDeleteForm($adminPensionadoInstitucion);

        return $this->render('adminpensionadoinstitucion/show.html.twig', array(
            'adminPensionadoInstitucion' => $adminPensionadoInstitucion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminPensionadoInstitucion entity.
     *
     */
    public function editAction(Request $request, AdminPensionadoInstitucion $adminPensionadoInstitucion)
    {
        $deleteForm = $this->createDeleteForm($adminPensionadoInstitucion);
        $editForm = $this->createForm('SICBundle\Form\AdminPensionadoInstitucionType', $adminPensionadoInstitucion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminPensionadoInstitucion);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo Institución Pensionados');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'pensionadoinstitucion'));
            // return $this->redirectToRoute('configurable_pensionados_instituciones_edit', array('id' => $adminPensionadoInstitucion->getId()));
        }

        return $this->render('adminpensionadoinstitucion/edit.html.twig', array(
            'adminPensionadoInstitucion' => $adminPensionadoInstitucion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminPensionadoInstitucion entity.
     *
     */
    public function deleteAction(Request $request, AdminPensionadoInstitucion $adminPensionadoInstitucion)
    {
        $form = $this->createDeleteForm($adminPensionadoInstitucion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminPensionadoInstitucion);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó','la Institución'.$adminPensionadoInstitucion->getNombre().' de los parámetros de Institución Pensionados del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'pensionadoinstitucion'));
        // return $this->redirectToRoute('configurable_pensionados_instituciones_index');
    }

    /**
     * Creates a form to delete a AdminPensionadoInstitucion entity.
     *
     * @param AdminPensionadoInstitucion $adminPensionadoInstitucion The AdminPensionadoInstitucion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminPensionadoInstitucion $adminPensionadoInstitucion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurable_pensionados_instituciones_delete', array('id' => $adminPensionadoInstitucion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

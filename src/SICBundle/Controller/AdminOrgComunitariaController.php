<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminOrgComunitaria;
use SICBundle\Form\AdminOrgComunitariaType;

/**
 * AdminOrgComunitaria controller.
 *
 */
class AdminOrgComunitariaController extends BaseController
{
    /**
     * Lists all AdminOrgComunitaria entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminOrgComunitarias = $em->getRepository(\SICBundle\Entity\AdminOrgComunitaria::class)->findAll();

        return $this->render('adminorgcomunitaria/index.html.twig', array(
            'adminOrgComunitarias' => $adminOrgComunitarias,
        ));
    }

    /**
     * Creates a new AdminOrgComunitaria entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminOrgComunitarium = new AdminOrgComunitaria();
        $form = $this->createForm('SICBundle\Form\AdminOrgComunitariaType', $adminOrgComunitarium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminOrgComunitarium);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Organizaciones Comunitarias a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'organizacionescomunitarias'));
            // return $this->redirectToRoute('configurables_tipos_organizaciones_comunitarias_show', array('id' => $adminOrgComunitarium->getId()));
        }

        return $this->render('adminorgcomunitaria/new.html.twig', array(
            'adminOrgComunitarium' => $adminOrgComunitarium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminOrgComunitaria entity.
     *
     */
    public function showAction(AdminOrgComunitaria $adminOrgComunitarium)
    {
        $deleteForm = $this->createDeleteForm($adminOrgComunitarium);

        return $this->render('adminorgcomunitaria/show.html.twig', array(
            'adminOrgComunitarium' => $adminOrgComunitarium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminOrgComunitaria entity.
     *
     */
    public function editAction(Request $request, AdminOrgComunitaria $adminOrgComunitarium)
    {
        $deleteForm = $this->createDeleteForm($adminOrgComunitarium);
        $editForm = $this->createForm('SICBundle\Form\AdminOrgComunitariaType', $adminOrgComunitarium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminOrgComunitarium);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro Organizaciones Comunitarias');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'organizacionescomunitarias'));
            // return $this->redirectToRoute('configurables_tipos_organizaciones_comunitarias_edit', array('id' => $adminOrgComunitarium->getId()));
        }

        return $this->render('adminorgcomunitaria/edit.html.twig', array(
            'adminOrgComunitarium' => $adminOrgComunitarium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminOrgComunitaria entity.
     *
     */
    public function deleteAction(Request $request, AdminOrgComunitaria $adminOrgComunitarium)
    {
        $form = $this->createDeleteForm($adminOrgComunitarium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminOrgComunitarium);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminOrgComunitarium->getNombre().' de los parámetros de Organizaciones Comunitarias del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'organizacionescomunitarias'));
        // return $this->redirectToRoute('configurables_tipos_organizaciones_comunitarias_index');
    }

    /**
     * Creates a form to delete a AdminOrgComunitaria entity.
     *
     * @param AdminOrgComunitaria $adminOrgComunitarium The AdminOrgComunitaria entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminOrgComunitaria $adminOrgComunitarium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurables_tipos_organizaciones_comunitarias_delete', array('id' => $adminOrgComunitarium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

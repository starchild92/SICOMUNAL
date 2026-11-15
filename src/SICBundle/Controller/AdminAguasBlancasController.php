<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\AdminAguasBlancas;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\AdminAguasBlancasType;

/**
 * AdminAguasBlancas controller.
 *
 */
class AdminAguasBlancasController extends BaseController
{
    /**
     * Lists all AdminAguasBlancas entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminAguasBlancas = $em->getRepository('SICBundle:AdminAguasBlancas')->findAll();

        return $this->render('adminaguasblancas/index.html.twig', array(
            'adminAguasBlancas' => $adminAguasBlancas,
        ));
    }

    /**
     * Creates a new AdminAguasBlancas entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminAguasBlanca = new AdminAguasBlancas();
        $form = $this->createForm('SICBundle\Form\AdminAguasBlancasType', $adminAguasBlanca);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminAguasBlanca);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Agua Blanca a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'aguasblancas'));
            // return $this->redirectToRoute('configurables_tipos_aguas_blancas_show', array('id' => $adminAguasBlanca->getId()));
        }

        return $this->render('adminaguasblancas/new.html.twig', array(
            'adminAguasBlanca' => $adminAguasBlanca,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminAguasBlancas entity.
     *
     */
    public function showAction(AdminAguasBlancas $adminAguasBlanca)
    {
        $deleteForm = $this->createDeleteForm($adminAguasBlanca);

        return $this->render('adminaguasblancas/show.html.twig', array(
            'adminAguasBlanca' => $adminAguasBlanca,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminAguasBlancas entity.
     *
     */
    public function editAction(Request $request, AdminAguasBlancas $adminAguasBlanca)
    {
        $deleteForm = $this->createDeleteForm($adminAguasBlanca);
        $editForm = $this->createForm('SICBundle\Form\AdminAguasBlancasType', $adminAguasBlanca);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Aguas Blancas');
            $em->persist($bitacora);
            $em->persist($adminAguasBlanca);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'aguasblancas'));
            // return $this->redirectToRoute('configurables_tipos_aguas_blancas_edit', array('id' => $adminAguasBlanca->getId()));
        }

        return $this->render('adminaguasblancas/edit.html.twig', array(
            'adminAguasBlanca' => $adminAguasBlanca,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminAguasBlancas entity.
     *
     */
    public function deleteAction(Request $request, AdminAguasBlancas $adminAguasBlanca)
    {
        $form = $this->createDeleteForm($adminAguasBlanca);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminAguasBlanca);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminAguasBlanca->getNombre().' de los parámetros de Aguas Blancas del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'aguasblancas'));
        // return $this->redirectToRoute('configurables_tipos_aguas_blancas_index');
    }

    /**
     * Creates a form to delete a AdminAguasBlancas entity.
     *
     * @param AdminAguasBlancas $adminAguasBlanca The AdminAguasBlancas entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminAguasBlancas $adminAguasBlanca)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurables_tipos_aguas_blancas_delete', array('id' => $adminAguasBlanca->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

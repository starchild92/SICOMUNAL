<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\AdminAreaTrabajoCC;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\AdminAreaTrabajoCCType;

/**
 * AdminAreaTrabajoCC controller.
 *
 */
class AdminAreaTrabajoCCController extends BaseController
{
    /**
     * Lists all AdminAreaTrabajoCC entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminAreaTrabajoCCs = $em->getRepository(\SICBundle\Entity\AdminAreaTrabajoCC::class)->findAll();

        return $this->render('adminareatrabajocc/index.html.twig', array(
            'adminAreaTrabajoCCs' => $adminAreaTrabajoCCs,
        ));
    }

    /**
     * Creates a new AdminAreaTrabajoCC entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminAreaTrabajoCC = new AdminAreaTrabajoCC();
        $form = $this->createForm('SICBundle\Form\AdminAreaTrabajoCCType', $adminAreaTrabajoCC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nueva Area de Trabajo del Consejo Comunal a los parámetros del sistema');
            $em->persist($bitacora);
            $em->persist($adminAreaTrabajoCC);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'areatrabajo'));
            // return $this->redirectToRoute('configurables_tipos_areas_trabajo_show', array('id' => $adminAreaTrabajoCC->getId()));
        }

        return $this->render('adminareatrabajocc/new.html.twig', array(
            'adminAreaTrabajoCC' => $adminAreaTrabajoCC,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminAreaTrabajoCC entity.
     *
     */
    public function showAction(AdminAreaTrabajoCC $adminAreaTrabajoCC)
    {
        $deleteForm = $this->createDeleteForm($adminAreaTrabajoCC);

        return $this->render('adminareatrabajocc/show.html.twig', array(
            'adminAreaTrabajoCC' => $adminAreaTrabajoCC,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminAreaTrabajoCC entity.
     *
     */
    public function editAction(Request $request, AdminAreaTrabajoCC $adminAreaTrabajoCC)
    {
        $deleteForm = $this->createDeleteForm($adminAreaTrabajoCC);
        $editForm = $this->createForm('SICBundle\Form\AdminAreaTrabajoCCType', $adminAreaTrabajoCC);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Area de Trabajo del Consejo Comunal');
            $em->persist($bitacora);
            $em->persist($adminAreaTrabajoCC);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'areatrabajo'));
            // return $this->redirectToRoute('configurables_tipos_areas_trabajo_edit', array('id' => $adminAreaTrabajoCC->getId()));
        }

        return $this->render('adminareatrabajocc/edit.html.twig', array(
            'adminAreaTrabajoCC' => $adminAreaTrabajoCC,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminAreaTrabajoCC entity.
     *
     */
    public function deleteAction(Request $request, AdminAreaTrabajoCC $adminAreaTrabajoCC)
    {
        $form = $this->createDeleteForm($adminAreaTrabajoCC);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminAreaTrabajoCC->getNombre().' de los parámetros de Area de Trabajo del sistema');
            $em->persist($bitacora);
            $em->remove($adminAreaTrabajoCC);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'areatrabajo'));
        // return $this->redirectToRoute('configurables_tipos_areas_trabajo_index');
    }

    /**
     * Creates a form to delete a AdminAreaTrabajoCC entity.
     *
     * @param AdminAreaTrabajoCC $adminAreaTrabajoCC The AdminAreaTrabajoCC entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminAreaTrabajoCC $adminAreaTrabajoCC)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurables_tipos_areas_trabajo_delete', array('id' => $adminAreaTrabajoCC->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

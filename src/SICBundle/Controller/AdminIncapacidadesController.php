<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\AdminIncapacidades;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\AdminIncapacidadesType;

/**
 * AdminIncapacidades controller.
 *
 */
class AdminIncapacidadesController extends BaseController
{
    /**
     * Lists all AdminIncapacidades entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminIncapacidades = $em->getRepository('SICBundle:AdminIncapacidades')->findAll();

        return $this->render('adminincapacidades/index.html.twig', array(
            'adminIncapacidades' => $adminIncapacidades,
        ));
    }

    /**
     * Creates a new AdminIncapacidades entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminIncapacidade = new AdminIncapacidades();
        $form = $this->createForm('SICBundle\Form\AdminIncapacidadesType', $adminIncapacidade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Discapacidad a los parámetros del sistema');
            $em->persist($bitacora);
            $em->persist($adminIncapacidade);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'incapacidades'));
            // return $this->redirectToRoute('configurable_incapacidades_show', array('id' => $adminIncapacidade->getId()));
        }

        return $this->render('adminincapacidades/new.html.twig', array(
            'adminIncapacidade' => $adminIncapacidade,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminIncapacidades entity.
     *
     */
    public function showAction(AdminIncapacidades $adminIncapacidade)
    {
        $deleteForm = $this->createDeleteForm($adminIncapacidade);

        return $this->render('adminincapacidades/show.html.twig', array(
            'adminIncapacidade' => $adminIncapacidade,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminIncapacidades entity.
     *
     */
    public function editAction(Request $request, AdminIncapacidades $adminIncapacidade)
    {
        $deleteForm = $this->createDeleteForm($adminIncapacidade);
        $editForm = $this->createForm('SICBundle\Form\AdminIncapacidadesType', $adminIncapacidade);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Discapacidades');
            $em->persist($bitacora);
            $em->persist($adminIncapacidade);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'incapacidades'));
            // return $this->redirectToRoute('configurable_incapacidades_edit', array('id' => $adminIncapacidade->getId()));
        }

        return $this->render('adminincapacidades/edit.html.twig', array(
            'adminIncapacidade' => $adminIncapacidade,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminIncapacidades entity.
     *
     */
    public function deleteAction(Request $request, AdminIncapacidades $adminIncapacidade)
    {
        $form = $this->createDeleteForm($adminIncapacidade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminIncapacidade->getIncapacidad().' de los parámetros de Discapacidades del sistema');
            $em->persist($bitacora);
            $em->remove($adminIncapacidade);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'incapacidades'));
        // return $this->redirectToRoute('configurable_incapacidades_index');
    }

    /**
     * Creates a form to delete a AdminIncapacidades entity.
     *
     * @param AdminIncapacidades $adminIncapacidade The AdminIncapacidades entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminIncapacidades $adminIncapacidade)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurable_incapacidades_delete', array('id' => $adminIncapacidade->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

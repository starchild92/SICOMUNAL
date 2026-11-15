<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminTipoSituacion;
use SICBundle\Form\AdminTipoSituacionType;

/**
 * AdminTipoSituacion controller.
 *
 */
class AdminTipoSituacionController extends BaseController
{
    /**
     * Lists all AdminTipoSituacion entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminTipoSituacions = $em->getRepository(\SICBundle\Entity\AdminTipoSituacion::class)->findAll();

        return $this->render('admintiposituacion/index.html.twig', array(
            'adminTipoSituacions' => $adminTipoSituacions,
        ));
    }

    /**
     * Creates a new AdminTipoSituacion entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminTipoSituacion = new AdminTipoSituacion();
        $form = $this->createForm('SICBundle\Form\AdminTipoSituacionType', $adminTipoSituacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoSituacion);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Situación de Exclusión a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();
            
            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'situacion'));
            // return $this->redirectToRoute('admintiposituacion_show', array('id' => $adminTipoSituacion->getId()));
        }

        return $this->render('admintiposituacion/new.html.twig', array(
            'adminTipoSituacion' => $adminTipoSituacion,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminTipoSituacion entity.
     *
     */
    public function showAction(AdminTipoSituacion $adminTipoSituacion)
    {
        $deleteForm = $this->createDeleteForm($adminTipoSituacion);

        return $this->render('admintiposituacion/show.html.twig', array(
            'adminTipoSituacion' => $adminTipoSituacion,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminTipoSituacion entity.
     *
     */
    public function editAction(Request $request, AdminTipoSituacion $adminTipoSituacion)
    {
        $deleteForm = $this->createDeleteForm($adminTipoSituacion);
        $editForm = $this->createForm('SICBundle\Form\AdminTipoSituacionType', $adminTipoSituacion);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoSituacion);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un tipo de Situación de Exclusión a los parámetros del sistema');
            $em->persist($bitacora);

            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'situacion'));
            // return $this->redirectToRoute('admintiposituacion_edit', array('id' => $adminTipoSituacion->getId()));
        }

        return $this->render('admintiposituacion/edit.html.twig', array(
            'adminTipoSituacion' => $adminTipoSituacion,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminTipoSituacion entity.
     *
     */
    public function deleteAction(Request $request, AdminTipoSituacion $adminTipoSituacion)
    {
        $form = $this->createDeleteForm($adminTipoSituacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminTipoSituacion);
            
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'eliminó','un tipo de Situación de Exclusión a los parámetros del sistema');
            $em->persist($bitacora);

            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'situacion'));
        // return $this->redirectToRoute('admintiposituacion_index');
    }

    /**
     * Creates a form to delete a AdminTipoSituacion entity.
     *
     * @param AdminTipoSituacion $adminTipoSituacion The AdminTipoSituacion entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminTipoSituacion $adminTipoSituacion)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admintiposituacion_delete', array('id' => $adminTipoSituacion->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

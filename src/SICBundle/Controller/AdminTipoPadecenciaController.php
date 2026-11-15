<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminTipoPadecencia;
use SICBundle\Form\AdminTipoPadecenciaType;

/**
 * AdminTipoPadecencia controller.
 *
 */
class AdminTipoPadecenciaController extends BaseController
{
    /**
     * Lists all AdminTipoPadecencia entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminTipoPadecencias = $em->getRepository('SICBundle:AdminTipoPadecencia')->findAll();

        return $this->render('admintipopadecencia/index.html.twig', array(
            'adminTipoPadecencias' => $adminTipoPadecencias,
        ));
    }

    /**
     * Creates a new AdminTipoPadecencia entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminTipoPadecencium = new AdminTipoPadecencia();
        $form = $this->createForm('SICBundle\Form\AdminTipoPadecenciaType', $adminTipoPadecencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoPadecencium);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo de Padecencia a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipopadecencia'));
            // return $this->redirectToRoute('configurable_tipo_padecencia_show', array('id' => $adminTipoPadecencium->getId()));
        }

        return $this->render('admintipopadecencia/new.html.twig', array(
            'adminTipoPadecencium' => $adminTipoPadecencium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminTipoPadecencia entity.
     *
     */
    public function showAction(AdminTipoPadecencia $adminTipoPadecencium)
    {
        $deleteForm = $this->createDeleteForm($adminTipoPadecencium);

        return $this->render('admintipopadecencia/show.html.twig', array(
            'adminTipoPadecencium' => $adminTipoPadecencium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminTipoPadecencia entity.
     *
     */
    public function editAction(Request $request, AdminTipoPadecencia $adminTipoPadecencium)
    {
        $deleteForm = $this->createDeleteForm($adminTipoPadecencium);
        $editForm = $this->createForm('SICBundle\Form\AdminTipoPadecenciaType', $adminTipoPadecencium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoPadecencium);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Padecencia');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipopadecencia'));
            // return $this->redirectToRoute('configurable_tipo_padecencia_edit', array('id' => $adminTipoPadecencium->getId()));
        }

        return $this->render('admintipopadecencia/edit.html.twig', array(
            'adminTipoPadecencium' => $adminTipoPadecencium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminTipoPadecencia entity.
     *
     */
    public function deleteAction(Request $request, AdminTipoPadecencia $adminTipoPadecencium)
    {
        $form = $this->createDeleteForm($adminTipoPadecencium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminTipoPadecencium);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminTipoPadecencium->getNombre().' de los parámetros de Tipo de Padecencia del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipopadecencia'));
        // return $this->redirectToRoute('configurable_tipo_padecencia_index');
    }

    /**
     * Creates a form to delete a AdminTipoPadecencia entity.
     *
     * @param AdminTipoPadecencia $adminTipoPadecencium The AdminTipoPadecencia entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminTipoPadecencia $adminTipoPadecencium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurable_tipo_padecencia_delete', array('id' => $adminTipoPadecencium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

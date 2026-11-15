<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminTipoTelefonia;
use SICBundle\Form\AdminTipoTelefoniaType;

/**
 * AdminTipoTelefonia controller.
 *
 */
class AdminTipoTelefoniaController extends BaseController
{
    /**
     * Lists all AdminTipoTelefonia entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminTipoTelefonias = $em->getRepository('SICBundle:AdminTipoTelefonia')->findAll();

        return $this->render('admintipotelefonia/index.html.twig', array(
            'adminTipoTelefonias' => $adminTipoTelefonias,
        ));
    }

    /**
     * Creates a new AdminTipoTelefonia entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminTipoTelefonium = new AdminTipoTelefonia();
        $form = $this->createForm('SICBundle\Form\AdminTipoTelefoniaType', $adminTipoTelefonium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoTelefonium);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo de Telefonía a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipotelefonia'));
            // return $this->redirectToRoute('configurables_tipo_telefonia_show', array('id' => $adminTipoTelefonium->getId()));
        }

        return $this->render('admintipotelefonia/new.html.twig', array(
            'adminTipoTelefonium' => $adminTipoTelefonium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminTipoTelefonia entity.
     *
     */
    public function showAction(AdminTipoTelefonia $adminTipoTelefonium)
    {
        $deleteForm = $this->createDeleteForm($adminTipoTelefonium);

        return $this->render('admintipotelefonia/show.html.twig', array(
            'adminTipoTelefonium' => $adminTipoTelefonium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminTipoTelefonia entity.
     *
     */
    public function editAction(Request $request, AdminTipoTelefonia $adminTipoTelefonium)
    {
        $deleteForm = $this->createDeleteForm($adminTipoTelefonium);
        $editForm = $this->createForm('SICBundle\Form\AdminTipoTelefoniaType', $adminTipoTelefonium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoTelefonium);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Telefonía');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipotelefonia'));
            // return $this->redirectToRoute('configurables_tipo_telefonia_edit', array('id' => $adminTipoTelefonium->getId()));
        }

        return $this->render('admintipotelefonia/edit.html.twig', array(
            'adminTipoTelefonium' => $adminTipoTelefonium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminTipoTelefonia entity.
     *
     */
    public function deleteAction(Request $request, AdminTipoTelefonia $adminTipoTelefonium)
    {
        $form = $this->createDeleteForm($adminTipoTelefonium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminTipoTelefonium);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminTipoTelefonium->getNombre().' de los parámetros de Tipo de Telefonía del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipotelefonia'));
        // return $this->redirectToRoute('configurables_tipo_telefonia_index');
    }

    /**
     * Creates a form to delete a AdminTipoTelefonia entity.
     *
     * @param AdminTipoTelefonia $adminTipoTelefonium The AdminTipoTelefonia entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminTipoTelefonia $adminTipoTelefonium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurables_tipo_telefonia_delete', array('id' => $adminTipoTelefonium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminTipoTecho;
use SICBundle\Form\AdminTipoTechoType;

/**
 * AdminTipoTecho controller.
 *
 */
class AdminTipoTechoController extends BaseController
{
    /**
     * Lists all AdminTipoTecho entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminTipoTechos = $em->getRepository('SICBundle:AdminTipoTecho')->findAll();

        return $this->render('admintipotecho/index.html.twig', array(
            'adminTipoTechos' => $adminTipoTechos,
        ));
    }

    /**
     * Creates a new AdminTipoTecho entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminTipoTecho = new AdminTipoTecho();
        $form = $this->createForm('SICBundle\Form\AdminTipoTechoType', $adminTipoTecho);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoTecho);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo de Techo a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipotecho'));
            // return $this->redirectToRoute('configurable_tipo_techo_show', array('id' => $adminTipoTecho->getId()));
        }

        return $this->render('admintipotecho/new.html.twig', array(
            'adminTipoTecho' => $adminTipoTecho,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminTipoTecho entity.
     *
     */
    public function showAction(AdminTipoTecho $adminTipoTecho)
    {
        $deleteForm = $this->createDeleteForm($adminTipoTecho);

        return $this->render('admintipotecho/show.html.twig', array(
            'adminTipoTecho' => $adminTipoTecho,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminTipoTecho entity.
     *
     */
    public function editAction(Request $request, AdminTipoTecho $adminTipoTecho)
    {
        $deleteForm = $this->createDeleteForm($adminTipoTecho);
        $editForm = $this->createForm('SICBundle\Form\AdminTipoTechoType', $adminTipoTecho);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoTecho);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Techo');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipotecho'));
            // return $this->redirectToRoute('configurable_tipo_techo_edit', array('id' => $adminTipoTecho->getId()));
        }

        return $this->render('admintipotecho/edit.html.twig', array(
            'adminTipoTecho' => $adminTipoTecho,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminTipoTecho entity.
     *
     */
    public function deleteAction(Request $request, AdminTipoTecho $adminTipoTecho)
    {
        $form = $this->createDeleteForm($adminTipoTecho);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminTipoTecho);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminTipoTecho->getNombre().' de los parámetros de Tipo de Techo del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipotecho'));
        // return $this->redirectToRoute('configurable_tipo_techo_index');
    }

    /**
     * Creates a form to delete a AdminTipoTecho entity.
     *
     * @param AdminTipoTecho $adminTipoTecho The AdminTipoTecho entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminTipoTecho $adminTipoTecho)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurable_tipo_techo_delete', array('id' => $adminTipoTecho->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

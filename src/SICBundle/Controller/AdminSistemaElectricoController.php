<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminSistemaElectrico;
use SICBundle\Form\AdminSistemaElectricoType;

/**
 * AdminSistemaElectrico controller.
 *
 */
class AdminSistemaElectricoController extends BaseController
{
    /**
     * Lists all AdminSistemaElectrico entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminSistemaElectricos = $em->getRepository('SICBundle:AdminSistemaElectrico')->findAll();

        return $this->render('adminsistemaelectrico/index.html.twig', array(
            'adminSistemaElectricos' => $adminSistemaElectricos,
        ));
    }

    /**
     * Creates a new AdminSistemaElectrico entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminSistemaElectrico = new AdminSistemaElectrico();
        $form = $this->createForm('SICBundle\Form\AdminSistemaElectricoType', $adminSistemaElectrico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminSistemaElectrico);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo de Sistema Electrico a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'sistemaelectrico'));
            // return $this->redirectToRoute('configurables_tipo_sistema_electrico_show', array('id' => $adminSistemaElectrico->getId()));
        }

        return $this->render('adminsistemaelectrico/new.html.twig', array(
            'adminSistemaElectrico' => $adminSistemaElectrico,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminSistemaElectrico entity.
     *
     */
    public function showAction(AdminSistemaElectrico $adminSistemaElectrico)
    {
        $deleteForm = $this->createDeleteForm($adminSistemaElectrico);

        return $this->render('adminsistemaelectrico/show.html.twig', array(
            'adminSistemaElectrico' => $adminSistemaElectrico,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminSistemaElectrico entity.
     *
     */
    public function editAction(Request $request, AdminSistemaElectrico $adminSistemaElectrico)
    {
        $deleteForm = $this->createDeleteForm($adminSistemaElectrico);
        $editForm = $this->createForm('SICBundle\Form\AdminSistemaElectricoType', $adminSistemaElectrico);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminSistemaElectrico);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Sistema Electrico');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'sistemaelectrico'));
            // return $this->redirectToRoute('configurables_tipo_sistema_electrico_edit', array('id' => $adminSistemaElectrico->getId()));
        }

        return $this->render('adminsistemaelectrico/edit.html.twig', array(
            'adminSistemaElectrico' => $adminSistemaElectrico,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminSistemaElectrico entity.
     *
     */
    public function deleteAction(Request $request, AdminSistemaElectrico $adminSistemaElectrico)
    {
        $form = $this->createDeleteForm($adminSistemaElectrico);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminSistemaElectrico);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminSistemaElectrico->getNombre().' de los parámetros de Tipo de Sistema Electrico del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'sistemaelectrico'));
        // return $this->redirectToRoute('configurables_tipo_sistema_electrico_index');
    }

    /**
     * Creates a form to delete a AdminSistemaElectrico entity.
     *
     * @param AdminSistemaElectrico $adminSistemaElectrico The AdminSistemaElectrico entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminSistemaElectrico $adminSistemaElectrico)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurables_tipo_sistema_electrico_delete', array('id' => $adminSistemaElectrico->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

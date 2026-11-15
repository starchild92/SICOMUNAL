<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminTipoGas;
use SICBundle\Form\AdminTipoGasType;

/**
 * AdminTipoGas controller.
 *
 */
class AdminTipoGasController extends BaseController
{
    /**
     * Lists all AdminTipoGas entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminTipoGas = $em->getRepository('SICBundle:AdminTipoGas')->findAll();

        return $this->render('admintipogas/index.html.twig', array(
            'adminTipoGas' => $adminTipoGas,
        ));
    }

    /**
     * Creates a new AdminTipoGas entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminTipoGa = new AdminTipoGas();
        $form = $this->createForm('SICBundle\Form\AdminTipoGasType', $adminTipoGa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoGa);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo de Gas a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipogas'));
            // return $this->redirectToRoute('configurables_tipos_gas_show', array('id' => $adminTipoGa->getId()));
        }

        return $this->render('admintipogas/new.html.twig', array(
            'adminTipoGa' => $adminTipoGa,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminTipoGas entity.
     *
     */
    public function showAction(AdminTipoGas $adminTipoGa)
    {
        $deleteForm = $this->createDeleteForm($adminTipoGa);

        return $this->render('admintipogas/show.html.twig', array(
            'adminTipoGa' => $adminTipoGa,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminTipoGas entity.
     *
     */
    public function editAction(Request $request, AdminTipoGas $adminTipoGa)
    {
        $deleteForm = $this->createDeleteForm($adminTipoGa);
        $editForm = $this->createForm('SICBundle\Form\AdminTipoGasType', $adminTipoGa);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoGa);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Gas');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipogas'));
            // return $this->redirectToRoute('configurables_tipos_gas_edit', array('id' => $adminTipoGa->getId()));
        }

        return $this->render('admintipogas/edit.html.twig', array(
            'adminTipoGa' => $adminTipoGa,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminTipoGas entity.
     *
     */
    public function deleteAction(Request $request, AdminTipoGas $adminTipoGa)
    {
        $form = $this->createDeleteForm($adminTipoGa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminTipoGa);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminTipoGa->getNombre().' de los parámetros de Tipo de Gas del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipogas'));
        // return $this->redirectToRoute('configurables_tipos_gas_index');
    }

    /**
     * Creates a form to delete a AdminTipoGas entity.
     *
     * @param AdminTipoGas $adminTipoGa The AdminTipoGas entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminTipoGas $adminTipoGa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurables_tipos_gas_delete', array('id' => $adminTipoGa->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

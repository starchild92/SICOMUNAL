<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\AdminEmpresaGas;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\AdminEmpresaGasType;

/**
 * AdminEmpresaGas controller.
 *
 */
class AdminEmpresaGasController extends BaseController
{
    /**
     * Lists all AdminEmpresaGas entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminEmpresaGas = $em->getRepository(\SICBundle\Entity\AdminEmpresaGas::class)->findAll();

        return $this->render('adminempresagas/index.html.twig', array(
            'adminEmpresaGas' => $adminEmpresaGas,
        ));
    }

    /**
     * Creates a new AdminEmpresaGas entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminEmpresaGa = new AdminEmpresaGas();
        $form = $this->createForm('SICBundle\Form\AdminEmpresaGasType', $adminEmpresaGa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminEmpresaGa);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Proveedor de Gas a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'empresagas'));
            // return $this->redirectToRoute('adminempresagas_show', array('id' => $adminEmpresaGa->getId()));
        }

        return $this->render('adminempresagas/new.html.twig', array(
            'adminEmpresaGa' => $adminEmpresaGa,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminEmpresaGas entity.
     *
     */
    public function showAction(AdminEmpresaGas $adminEmpresaGa)
    {
        $deleteForm = $this->createDeleteForm($adminEmpresaGa);

        return $this->render('adminempresagas/show.html.twig', array(
            'adminEmpresaGa' => $adminEmpresaGa,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminEmpresaGas entity.
     *
     */
    public function editAction(Request $request, AdminEmpresaGas $adminEmpresaGa)
    {
        $deleteForm = $this->createDeleteForm($adminEmpresaGa);
        $editForm = $this->createForm('SICBundle\Form\AdminEmpresaGasType', $adminEmpresaGa);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminEmpresaGa);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Proveedor de Gas');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'empresagas'));
            // return $this->redirectToRoute('adminempresagas_edit', array('id' => $adminEmpresaGa->getId()));
        }

        return $this->render('adminempresagas/edit.html.twig', array(
            'adminEmpresaGa' => $adminEmpresaGa,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminEmpresaGas entity.
     *
     */
    public function deleteAction(Request $request, AdminEmpresaGas $adminEmpresaGa)
    {
        $form = $this->createDeleteForm($adminEmpresaGa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminEmpresaGa);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminEmpresaGa->getNombre().' de los parámetros de Proveedor de Gas del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'empresagas'));
        // return $this->redirectToRoute('adminempresagas_index');
    }

    /**
     * Creates a form to delete a AdminEmpresaGas entity.
     *
     * @param AdminEmpresaGas $adminEmpresaGa The AdminEmpresaGas entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminEmpresaGas $adminEmpresaGa)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('adminempresagas_delete', array('id' => $adminEmpresaGa->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

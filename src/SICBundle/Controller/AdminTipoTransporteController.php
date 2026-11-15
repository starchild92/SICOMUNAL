<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminTipoTransporte;
use SICBundle\Form\AdminTipoTransporteType;

/**
 * AdminTipoTransporte controller.
 *
 */
class AdminTipoTransporteController extends BaseController
{
    /**
     * Lists all AdminTipoTransporte entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminTipoTransportes = $em->getRepository(\SICBundle\Entity\AdminTipoTransporte::class)->findAll();

        return $this->render('admintipotransporte/index.html.twig', array(
            'adminTipoTransportes' => $adminTipoTransportes,
        ));
    }

    /**
     * Creates a new AdminTipoTransporte entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminTipoTransporte = new AdminTipoTransporte();
        $form = $this->createForm(AdminTipoTransporteType::class, $adminTipoTransporte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoTransporte);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo de Transporte a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipotransporte'));
            // return $this->redirectToRoute('configurables_tipo_transporte_show', array('id' => $adminTipoTransporte->getId()));
        }

        return $this->render('admintipotransporte/new.html.twig', array(
            'adminTipoTransporte' => $adminTipoTransporte,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminTipoTransporte entity.
     *
     */
    public function showAction(AdminTipoTransporte $adminTipoTransporte)
    {
        $deleteForm = $this->createDeleteForm($adminTipoTransporte);

        return $this->render('admintipotransporte/show.html.twig', array(
            'adminTipoTransporte' => $adminTipoTransporte,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminTipoTransporte entity.
     *
     */
    public function editAction(Request $request, AdminTipoTransporte $adminTipoTransporte)
    {
        $deleteForm = $this->createDeleteForm($adminTipoTransporte);
        $editForm = $this->createForm(AdminTipoTransporteType::class, $adminTipoTransporte);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoTransporte);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Transporte');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipotransporte'));
            // return $this->redirectToRoute('configurables_tipo_transporte_edit', array('id' => $adminTipoTransporte->getId()));
        }

        return $this->render('admintipotransporte/edit.html.twig', array(
            'adminTipoTransporte' => $adminTipoTransporte,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminTipoTransporte entity.
     *
     */
    public function deleteAction(Request $request, AdminTipoTransporte $adminTipoTransporte)
    {
        $form = $this->createDeleteForm($adminTipoTransporte);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminTipoTransporte);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminTipoTransporte->getNombre().' de los parámetros de Tipo de Transporte del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipotransporte'));
        // return $this->redirectToRoute('configurables_tipo_transporte_index');
    }

    /**
     * Creates a form to delete a AdminTipoTransporte entity.
     *
     * @param AdminTipoTransporte $adminTipoTransporte The AdminTipoTransporte entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminTipoTransporte $adminTipoTransporte)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurables_tipo_transporte_delete', array('id' => $adminTipoTransporte->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminTipoPlagas;
use SICBundle\Form\AdminTipoPlagasType;

/**
 * AdminTipoPlagas controller.
 *
 */
class AdminTipoPlagasController extends BaseController
{
    /**
     * Lists all AdminTipoPlagas entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminTipoPlagas = $em->getRepository('SICBundle:AdminTipoPlagas')->findAll();

        return $this->render('admintipoplagas/index.html.twig', array(
            'adminTipoPlagas' => $adminTipoPlagas,
        ));
    }

    /**
     * Creates a new AdminTipoPlagas entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminTipoPlaga = new AdminTipoPlagas();
        $form = $this->createForm('SICBundle\Form\AdminTipoPlagasType', $adminTipoPlaga);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoPlaga);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo de Plaga a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipoplagas'));
            // return $this->redirectToRoute('configurables_tipo_plagas_show', array('id' => $adminTipoPlaga->getId()));
        }

        return $this->render('admintipoplagas/new.html.twig', array(
            'adminTipoPlaga' => $adminTipoPlaga,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminTipoPlagas entity.
     *
     */
    public function showAction(AdminTipoPlagas $adminTipoPlaga)
    {
        $deleteForm = $this->createDeleteForm($adminTipoPlaga);

        return $this->render('admintipoplagas/show.html.twig', array(
            'adminTipoPlaga' => $adminTipoPlaga,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminTipoPlagas entity.
     *
     */
    public function editAction(Request $request, AdminTipoPlagas $adminTipoPlaga)
    {
        $deleteForm = $this->createDeleteForm($adminTipoPlaga);
        $editForm = $this->createForm('SICBundle\Form\AdminTipoPlagasType', $adminTipoPlaga);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoPlaga);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Plaga');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipoplagas'));
            // return $this->redirectToRoute('configurables_tipo_plagas_edit', array('id' => $adminTipoPlaga->getId()));
        }

        return $this->render('admintipoplagas/edit.html.twig', array(
            'adminTipoPlaga' => $adminTipoPlaga,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminTipoPlagas entity.
     *
     */
    public function deleteAction(Request $request, AdminTipoPlagas $adminTipoPlaga)
    {
        $form = $this->createDeleteForm($adminTipoPlaga);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminTipoPlaga);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminTipoPlaga->getNombre().' de los parámetros de Tipo de Plaga del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipoplagas'));
        // return $this->redirectToRoute('configurables_tipo_plagas_index');
    }

    /**
     * Creates a form to delete a AdminTipoPlagas entity.
     *
     * @param AdminTipoPlagas $adminTipoPlaga The AdminTipoPlagas entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminTipoPlagas $adminTipoPlaga)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurables_tipo_plagas_delete', array('id' => $adminTipoPlaga->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

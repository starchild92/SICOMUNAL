<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminNacionalidad;
use SICBundle\Form\AdminNacionalidadType;

/**
 * AdminNacionalidad controller.
 *
 */
class AdminNacionalidadController extends BaseController
{
    /**
     * Lists all AdminNacionalidad entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminNacionalidads = $em->getRepository(\SICBundle\Entity\AdminNacionalidad::class)->findAll();

        return $this->render('adminnacionalidad/index.html.twig', array(
            'adminNacionalidads' => $adminNacionalidads,
        ));
    }

    /**
     * Creates a new AdminNacionalidad entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminNacionalidad = new AdminNacionalidad();
        $form = $this->createForm(AdminNacionalidadType::class, $adminNacionalidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminNacionalidad);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo Nacionalidad a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'nacionalidad'));
            /*return $this->redirectToRoute('admin_nacionalidad_show', array('id' => $adminNacionalidad->getId()));*/
        }

        return $this->render('adminnacionalidad/new.html.twig', array(
            'adminNacionalidad' => $adminNacionalidad,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminNacionalidad entity.
     *
     */
    public function showAction(AdminNacionalidad $adminNacionalidad)
    {
        $deleteForm = $this->createDeleteForm($adminNacionalidad);

        return $this->render('adminnacionalidad/show.html.twig', array(
            'adminNacionalidad' => $adminNacionalidad,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminNacionalidad entity.
     *
     */
    public function editAction(Request $request, AdminNacionalidad $adminNacionalidad)
    {
        $deleteForm = $this->createDeleteForm($adminNacionalidad);
        $editForm = $this->createForm(AdminNacionalidadType::class, $adminNacionalidad);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminNacionalidad);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Nacionalidad');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'nacionalidad'));
            // return $this->redirectToRoute('admin_nacionalidad_edit', array('id' => $adminNacionalidad->getId()));
        }

        return $this->render('adminnacionalidad/edit.html.twig', array(
            'adminNacionalidad' => $adminNacionalidad,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminNacionalidad entity.
     *
     */
    public function deleteAction(Request $request, AdminNacionalidad $adminNacionalidad)
    {
        $form = $this->createDeleteForm($adminNacionalidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminNacionalidad);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminNacionalidad->getNacionalidad().' de los parámetros de Tipo de Nacionalidad del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'nacionalidad'));
        // return $this->redirectToRoute('admin_nacionalidad_index');
    }

    /**
     * Creates a form to delete a AdminNacionalidad entity.
     *
     * @param AdminNacionalidad $adminNacionalidad The AdminNacionalidad entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminNacionalidad $adminNacionalidad)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_nacionalidad_delete', array('id' => $adminNacionalidad->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

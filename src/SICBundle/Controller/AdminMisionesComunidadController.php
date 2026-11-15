<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminMisionesComunidad;
use SICBundle\Form\AdminMisionesComunidadType;

/**
 * AdminMisionesComunidad controller.
 *
 */
class AdminMisionesComunidadController extends BaseController
{
    /**
     * Lists all AdminMisionesComunidad entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminMisionesComunidads = $em->getRepository(\SICBundle\Entity\AdminMisionesComunidad::class)->findAll();

        return $this->render('adminmisionescomunidad/index.html.twig', array(
            'adminMisionesComunidads' => $adminMisionesComunidads,
        ));
    }

    /**
     * Creates a new AdminMisionesComunidad entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminMisionesComunidad = new AdminMisionesComunidad();
        $form = $this->createForm(AdminMisionesComunidadType::class, $adminMisionesComunidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminMisionesComunidad);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'misionescomunidad'));
            // return $this->redirectToRoute('configurables_tipos_misiones_comunidad_show', array('id' => $adminMisionesComunidad->getId()));
        }

        return $this->render('adminmisionescomunidad/new.html.twig', array(
            'adminMisionesComunidad' => $adminMisionesComunidad,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminMisionesComunidad entity.
     *
     */
    public function showAction(AdminMisionesComunidad $adminMisionesComunidad)
    {
        $deleteForm = $this->createDeleteForm($adminMisionesComunidad);

        return $this->render('adminmisionescomunidad/show.html.twig', array(
            'adminMisionesComunidad' => $adminMisionesComunidad,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminMisionesComunidad entity.
     *
     */
    public function editAction(Request $request, AdminMisionesComunidad $adminMisionesComunidad)
    {
        $deleteForm = $this->createDeleteForm($adminMisionesComunidad);
        $editForm = $this->createForm(AdminMisionesComunidadType::class, $adminMisionesComunidad);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminMisionesComunidad);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'misionescomunidad'));
            // return $this->redirectToRoute('configurables_tipos_misiones_comunidad_edit', array('id' => $adminMisionesComunidad->getId()));
        }

        return $this->render('adminmisionescomunidad/edit.html.twig', array(
            'adminMisionesComunidad' => $adminMisionesComunidad,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminMisionesComunidad entity.
     *
     */
    public function deleteAction(Request $request, AdminMisionesComunidad $adminMisionesComunidad)
    {
        $form = $this->createDeleteForm($adminMisionesComunidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminMisionesComunidad);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'misionescomunidad'));
        // return $this->redirectToRoute('configurables_tipos_misiones_comunidad_index');
    }

    /**
     * Creates a form to delete a AdminMisionesComunidad entity.
     *
     * @param AdminMisionesComunidad $adminMisionesComunidad The AdminMisionesComunidad entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminMisionesComunidad $adminMisionesComunidad)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurables_tipos_misiones_comunidad_delete', array('id' => $adminMisionesComunidad->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

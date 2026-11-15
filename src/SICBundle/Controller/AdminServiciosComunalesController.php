<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminServiciosComunales;
use SICBundle\Form\AdminServiciosComunalesType;

/**
 * AdminServiciosComunales controller.
 *
 */
class AdminServiciosComunalesController extends BaseController
{
    /**
     * Lists all AdminServiciosComunales entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminServiciosComunales = $em->getRepository(\SICBundle\Entity\AdminServiciosComunales::class)->findAll();

        return $this->render('adminservicioscomunales/index.html.twig', array(
            'adminServiciosComunales' => $adminServiciosComunales,
        ));
    }

    /**
     * Creates a new AdminServiciosComunales entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminServiciosComunale = new AdminServiciosComunales();
        $form = $this->createForm(AdminServiciosComunalesType::class, $adminServiciosComunale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminServiciosComunale);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo Servicio Comunal a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'servicioscomunales'));
            // return $this->redirectToRoute('configurables_tipos_servicios_comunales_show', array('id' => $adminServiciosComunale->getId()));
        }

        return $this->render('adminservicioscomunales/new.html.twig', array(
            'adminServiciosComunale' => $adminServiciosComunale,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminServiciosComunales entity.
     *
     */
    public function showAction(AdminServiciosComunales $adminServiciosComunale)
    {
        $deleteForm = $this->createDeleteForm($adminServiciosComunale);

        return $this->render('adminservicioscomunales/show.html.twig', array(
            'adminServiciosComunale' => $adminServiciosComunale,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminServiciosComunales entity.
     *
     */
    public function editAction(Request $request, AdminServiciosComunales $adminServiciosComunale)
    {
        $deleteForm = $this->createDeleteForm($adminServiciosComunale);
        $editForm = $this->createForm(AdminServiciosComunalesType::class, $adminServiciosComunale);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminServiciosComunale);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Servicio Comunal');
            $em->persist($bitacora);
            $em->flush();

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'servicioscomunales'));
            // return $this->redirectToRoute('configurables_tipos_servicios_comunales_edit', array('id' => $adminServiciosComunale->getId()));
        }

        return $this->render('adminservicioscomunales/edit.html.twig', array(
            'adminServiciosComunale' => $adminServiciosComunale,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminServiciosComunales entity.
     *
     */
    public function deleteAction(Request $request, AdminServiciosComunales $adminServiciosComunale)
    {
        $form = $this->createDeleteForm($adminServiciosComunale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminServiciosComunale);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminServiciosComunale->getNombre().' de los parámetros de Tipo de Servicio Comunal del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'servicioscomunales'));
        // return $this->redirectToRoute('configurables_tipos_servicios_comunales_index');
    }

    /**
     * Creates a form to delete a AdminServiciosComunales entity.
     *
     * @param AdminServiciosComunales $adminServiciosComunale The AdminServiciosComunales entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminServiciosComunales $adminServiciosComunale)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurables_tipos_servicios_comunales_delete', array('id' => $adminServiciosComunale->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

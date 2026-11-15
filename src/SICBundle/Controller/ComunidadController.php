<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Comunidad;
use SICBundle\Form\ComunidadType;
use SICBundle\Entity\Bitacora;

/**
 * Comunidad controller.
 *
 */
class ComunidadController extends BaseController
{
    /**
     * Lists all Comunidad entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $comunidads = $em->getRepository(\SICBundle\Entity\Comunidad::class)->findAll();

        return $this->render('comunidad/index.html.twig', array(
            'comunidads' => $comunidads,
        ));
    }

    /**
     * Creates a new Comunidad entity.
     *
     */
    public function newAction(Request $request)
    {
        $comunidad = new Comunidad();
        $form = $this->createForm(ComunidadType::class, $comunidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comunidad);

            $bitacora = new Bitacora($this->getUser(),'agregó','la información de la Comunidad.');
            $em->persist($bitacora);
            $em->flush();

            $this->addFlash('success', 'Se ha agregado la información de la Comunidad de forma exitosa.');

            return $this->redirectToRoute('sic_administacion_entidades');
        }

        return $this->render('comunidad/new.html.twig', array(
            'comunidad' => $comunidad,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Comunidad entity.
     *
     */
    public function showAction(Comunidad $comunidad)
    {
        $deleteForm = $this->createDeleteForm($comunidad);

        return $this->render('comunidad/show.html.twig', array(
            'comunidad' => $comunidad,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Comunidad entity.
     *
     */
    public function editAction(Request $request, Comunidad $comunidad)
    {
        $deleteForm = $this->createDeleteForm($comunidad);
        $editForm = $this->createForm(ComunidadType::class, $comunidad);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comunidad);

            $bitacora = new Bitacora($this->getUser(),'modificó','la información de la Comunidad.');
            $em->persist($bitacora);
            $em->flush();

            $this->addFlash('success', 'Se ha actualizado la información de la Comunidad de forma exitosa.');

            return $this->redirectToRoute('sic_administacion_entidades');
        }

        return $this->render('comunidad/edit.html.twig', array(
            'comunidad' => $comunidad,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Comunidad entity.
     *
     */
    public function deleteAction(Request $request, Comunidad $comunidad)
    {
        $form = $this->createDeleteForm($comunidad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comunidad);
            $em->flush();
        }

        return $this->redirectToRoute('comunidades_index');
    }

    /**
     * Creates a form to delete a Comunidad entity.
     *
     * @param Comunidad $comunidad The Comunidad entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Comunidad $comunidad)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comunidades_delete', array('id' => $comunidad->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

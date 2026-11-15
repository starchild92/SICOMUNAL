<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminPreguntasSitCom;
use SICBundle\Form\AdminPreguntasSitComType;

/**
 * AdminPreguntasSitCom controller.
 *
 */
class AdminPreguntasSitComController extends BaseController
{
    /**
     * Lists all AdminPreguntasSitCom entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminPreguntasSitComs = $em->getRepository('SICBundle:AdminPreguntasSitCom')->findAll();

        return $this->render('adminpreguntassitcom/index.html.twig', array(
            'adminPreguntasSitComs' => $adminPreguntasSitComs,
        ));
    }

    /**
     * Creates a new AdminPreguntasSitCom entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminPreguntasSitCom = new AdminPreguntasSitCom();
        $form = $this->createForm('SICBundle\Form\AdminPreguntasSitComType', $adminPreguntasSitCom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminPreguntasSitCom);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','una nueva Pregunta Situación Comunal a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'situacioncomunidad'));
            // return $this->redirectToRoute('adminpreguntassitcom_show', array('id' => $adminPreguntasSitCom->getId()));
        }

        return $this->render('adminpreguntassitcom/new.html.twig', array(
            'adminPreguntasSitCom' => $adminPreguntasSitCom,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminPreguntasSitCom entity.
     *
     */
    public function showAction(AdminPreguntasSitCom $adminPreguntasSitCom)
    {
        $deleteForm = $this->createDeleteForm($adminPreguntasSitCom);

        return $this->render('adminpreguntassitcom/show.html.twig', array(
            'adminPreguntasSitCom' => $adminPreguntasSitCom,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminPreguntasSitCom entity.
     *
     */
    public function editAction(Request $request, AdminPreguntasSitCom $adminPreguntasSitCom)
    {
        $deleteForm = $this->createDeleteForm($adminPreguntasSitCom);
        $editForm = $this->createForm('SICBundle\Form\AdminPreguntasSitComType', $adminPreguntasSitCom);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Preguntas Situación Comunidad');
            $em->persist($bitacora);
            $em->persist($adminPreguntasSitCom);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'situacioncomunidad'));
            // return $this->redirectToRoute('adminpreguntassitcom_edit', array('id' => $adminPreguntasSitCom->getId()));
        }

        return $this->render('adminpreguntassitcom/edit.html.twig', array(
            'adminPreguntasSitCom' => $adminPreguntasSitCom,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminPreguntasSitCom entity.
     *
     */
    public function deleteAction(Request $request, AdminPreguntasSitCom $adminPreguntasSitCom)
    {
        $form = $this->createDeleteForm($adminPreguntasSitCom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminPreguntasSitCom);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminPreguntasSitCom->getPregunta().' de los parámetros de Aguas Blancas del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'situacioncomunidad'));
        // return $this->redirectToRoute('adminpreguntassitcom_index');
    }

    /**
     * Creates a form to delete a AdminPreguntasSitCom entity.
     *
     * @param AdminPreguntasSitCom $adminPreguntasSitCom The AdminPreguntasSitCom entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminPreguntasSitCom $adminPreguntasSitCom)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('adminpreguntassitcom_delete', array('id' => $adminPreguntasSitCom->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

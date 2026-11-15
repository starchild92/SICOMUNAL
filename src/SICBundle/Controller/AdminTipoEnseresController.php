<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminTipoEnseres;
use SICBundle\Form\AdminTipoEnseresType;

/**
 * AdminTipoEnseres controller.
 *
 */
class AdminTipoEnseresController extends BaseController
{
    /**
     * Lists all AdminTipoEnseres entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminTipoEnseres = $em->getRepository(\SICBundle\Entity\AdminTipoEnseres::class)->findAll();

        return $this->render('admintipoenseres/index.html.twig', array(
            'adminTipoEnseres' => $adminTipoEnseres,
        ));
    }

    /**
     * Creates a new AdminTipoEnseres entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminTipoEnsere = new AdminTipoEnseres();
        $form = $this->createForm(AdminTipoEnseresType::class, $adminTipoEnsere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoEnsere);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de Tipo Enseres a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipoenseres'));
            // return $this->redirectToRoute('configurable_tipo_enseres_show', array('id' => $adminTipoEnsere->getId()));
        }

        return $this->render('admintipoenseres/new.html.twig', array(
            'adminTipoEnsere' => $adminTipoEnsere,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminTipoEnseres entity.
     *
     */
    public function showAction(AdminTipoEnseres $adminTipoEnsere)
    {
        $deleteForm = $this->createDeleteForm($adminTipoEnsere);

        return $this->render('admintipoenseres/show.html.twig', array(
            'adminTipoEnsere' => $adminTipoEnsere,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminTipoEnseres entity.
     *
     */
    public function editAction(Request $request, AdminTipoEnseres $adminTipoEnsere)
    {
        $deleteForm = $this->createDeleteForm($adminTipoEnsere);
        $editForm = $this->createForm(AdminTipoEnseresType::class, $adminTipoEnsere);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoEnsere);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Enseres');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipoenseres'));
            // return $this->redirectToRoute('configurable_tipo_enseres_edit', array('id' => $adminTipoEnsere->getId()));
        }

        return $this->render('admintipoenseres/edit.html.twig', array(
            'adminTipoEnsere' => $adminTipoEnsere,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminTipoEnseres entity.
     *
     */
    public function deleteAction(Request $request, AdminTipoEnseres $adminTipoEnsere)
    {
        $form = $this->createDeleteForm($adminTipoEnsere);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminTipoEnsere);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminTipoEnsere->getNombre().' de los parámetros de Tipo de Enseres del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipoenseres'));
        // return $this->redirectToRoute('configurable_tipo_enseres_index');
    }

    /**
     * Creates a form to delete a AdminTipoEnseres entity.
     *
     * @param AdminTipoEnseres $adminTipoEnsere The AdminTipoEnseres entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminTipoEnseres $adminTipoEnsere)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('configurable_tipo_enseres_delete', array('id' => $adminTipoEnsere->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

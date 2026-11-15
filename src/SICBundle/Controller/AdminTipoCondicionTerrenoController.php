<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminTipoCondicionTerreno;
use SICBundle\Form\AdminTipoCondicionTerrenoType;

/**
 * AdminTipoCondicionTerreno controller.
 *
 */
class AdminTipoCondicionTerrenoController extends BaseController
{
    /**
     * Lists all AdminTipoCondicionTerreno entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $adminTipoCondicionTerrenos = $em->getRepository(\SICBundle\Entity\AdminTipoCondicionTerreno::class)->findAll();

        return $this->render('admintipocondicionterreno/index.html.twig', array(
            'adminTipoCondicionTerrenos' => $adminTipoCondicionTerrenos,
        ));
    }

    /**
     * Creates a new AdminTipoCondicionTerreno entity.
     *
     */
    public function newAction(Request $request)
    {
        $adminTipoCondicionTerreno = new AdminTipoCondicionTerreno();
        $form = $this->createForm('SICBundle\Form\AdminTipoCondicionTerrenoType', $adminTipoCondicionTerreno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoCondicionTerreno);
            $this->addFlash('success', 'Se ha agregado un nuevo parámetro.');
            $bitacora = new Bitacora($this->getUser(),'agregó','un nuevo tipo de condición de terreno vivienda a los parámetros del sistema');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipocondicionterreno'));
        }

        return $this->render('admintipocondicionterreno/new.html.twig', array(
            'adminTipoCondicionTerreno' => $adminTipoCondicionTerreno,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AdminTipoCondicionTerreno entity.
     *
     */
    public function showAction(AdminTipoCondicionTerreno $adminTipoCondicionTerreno)
    {
        $deleteForm = $this->createDeleteForm($adminTipoCondicionTerreno);

        return $this->render('admintipocondicionterreno/show.html.twig', array(
            'adminTipoCondicionTerreno' => $adminTipoCondicionTerreno,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AdminTipoCondicionTerreno entity.
     *
     */
    public function editAction(Request $request, AdminTipoCondicionTerreno $adminTipoCondicionTerreno)
    {
        $deleteForm = $this->createDeleteForm($adminTipoCondicionTerreno);
        $editForm = $this->createForm('SICBundle\Form\AdminTipoCondicionTerrenoType', $adminTipoCondicionTerreno);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($adminTipoCondicionTerreno);
            $this->addFlash('success', 'Se ha modificado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'modificó','un parámetro de Tipo de Condición Terreno Vivienda');
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipocondicionterreno'));
            // return $this->redirectToRoute('admintipocondicionterreno_edit', array('id' => $adminTipoCondicionTerreno->getId()));
        }

        return $this->render('admintipocondicionterreno/edit.html.twig', array(
            'adminTipoCondicionTerreno' => $adminTipoCondicionTerreno,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AdminTipoCondicionTerreno entity.
     *
     */
    public function deleteAction(Request $request, AdminTipoCondicionTerreno $adminTipoCondicionTerreno)
    {
        $form = $this->createDeleteForm($adminTipoCondicionTerreno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($adminTipoCondicionTerreno);
            $this->addFlash('success', 'Se ha eliminado el parámetro de forma correcta.');
            $bitacora = new Bitacora($this->getUser(),'eliminó',$adminTipoCondicionTerreno->getNombre().' de los parámetros de Tipo de Condición Terreno Vivienda del sistema');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('sic_volver_parametros', array('index' => 'tipocondicionterreno'));
        // return $this->redirectToRoute('admintipocondicionterreno_index');
    }

    /**
     * Creates a form to delete a AdminTipoCondicionTerreno entity.
     *
     * @param AdminTipoCondicionTerreno $adminTipoCondicionTerreno The AdminTipoCondicionTerreno entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AdminTipoCondicionTerreno $adminTipoCondicionTerreno)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admintipocondicionterreno_delete', array('id' => $adminTipoCondicionTerreno->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

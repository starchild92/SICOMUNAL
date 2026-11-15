<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

use SICBundle\Entity\SituacionEconomica;
use SICBundle\Entity\Bitacora;
use SICBundle\Entity\AdminVentaVivienda;
use SICBundle\Form\SituacionEconomicaType;

/**
 * SituacionEconomica controller.
 *
 */
class SituacionEconomicaController extends BaseController
{
    private function obtenerStats()
    {
        $em = $this->getDoctrine()->getManager();

        $situacionEconomicas = $em->getRepository(\SICBundle\Entity\SituacionEconomica::class)->findAll();
        $total = sizeof($situacionEconomicas);

        $ubic_trabajo = $em->getRepository(\SICBundle\Entity\AdminUbicacionTrabajo::class)->findAll();
        $stat_ubic_trabajo = array();
        foreach ($ubic_trabajo as $elemento) {
            array_push(
                $stat_ubic_trabajo, 
                array(
                    'trabajo' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionEconomica::class)->findBy(
                                        array('dondeTrabaja' => $elemento->getId())
                                        )))
            );
        }

        $venta_vivienda = $em->getRepository(\SICBundle\Entity\AdminVentaVivienda::class)->findAll();
        $stat_venta_vivienda = array();
        foreach ($venta_vivienda as $elemento) {
            array_push(
                $stat_venta_vivienda, 
                array(
                    'venta_vivienda' => $elemento->getNombre(),
                    'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\SituacionEconomica::class)->findByActividadComercialVivienda($elemento)))
            );
        }

        $tipo_ingreso = $em->getRepository(\SICBundle\Entity\AdminTipoIngresos::class)->findAll();
        $stat_tipo_ingreso = array();
        foreach ($tipo_ingreso as $elemento) {
            array_push(
                $stat_tipo_ingreso, 
                array(
                    'tipo_ingreso' => $elemento->getNombre(),
                    'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\SituacionEconomica::class)->findBy(
                                  array('ingresoFamiliarEspecifico' => $elemento->getId())
                                  )))
            );
        }

        $stat_vehiculos = array();
        $si = 0; $no = 0;
        foreach ($situacionEconomicas as $sit) { if ($sit->getPlaca() != "") { $si++; }else{ $no++; } }
        array_push(
            $stat_vehiculos, 
            array('nombre' => 'Con Vehículo',
                'cantidad' => $si));
        array_push(
            $stat_vehiculos, 
            array('nombre' => 'Sin Vehículo',
                'cantidad' => $no));

        return array(
            'stat_ubic_trabajo'  => $stat_ubic_trabajo,
            'stat_venta_vivienda'  => $stat_venta_vivienda,
            'stat_tipo_ingreso'  => $stat_tipo_ingreso,
            'stat_vehiculos'  => $stat_vehiculos,
            'total' => $total,
        );
    }
    
    /**
     * Lists all SituacionEconomica entities.
     *
     */
    public function indexAction()
    {
        $stats = $this->obtenerStats();
        return $this->render('situacioneconomica/index.html.twig', $stats);
    }

    /**
     * Creates a new SituacionEconomica entity.
     *
     */
    public function newAction(Request $request, $id_planilla)
    {
        /*Redireccionar cuando se accede por GET y evitar que se cree una nueva para la misma planilla*/
        $em = $this->getDoctrine()->getManager();
        $planilla = $em->getRepository(\SICBundle\Entity\Planillas::class)->findById($id_planilla);
        $p = $planilla[0];

        if($p->getSituacionEconomica() != NULL){
            $this->addFlash('error', 'Seleccione la sección que desea modificar');
            return $this->redirectToRoute('planillas_show', array('id' => $id_planilla));
        }

        $situacionEconomica = new SituacionEconomica();
        $form = $this->createForm('SICBundle\Form\SituacionEconomicaType', $situacionEconomica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($situacionEconomica);
            $p->setSituacionEconomica($situacionEconomica);
            $p->setTerminada('30');
            $bitacora = new Bitacora($this->getUser(),'agregó','la Situación Económica a la planilla '.$id_planilla);
            $em->persist($bitacora);
            $em->persist($p);
            $em->flush();

            return $this->redirectToRoute('situacionvivienda_new', array('id_planilla' => $id_planilla));
        }

        return $this->render('situacioneconomica/new.html.twig', array(
            'situacionEconomica' => $situacionEconomica,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a SituacionEconomica entity.
     *
     */
    public function showAction(SituacionEconomica $situacionEconomica)
    {
        $deleteForm = $this->createDeleteForm($situacionEconomica);

        return $this->render('situacioneconomica/show.html.twig', array(
            'situacionEconomica' => $situacionEconomica,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing SituacionEconomica entity.
     *
     */
    public function editAction(Request $request, SituacionEconomica $situacionEconomica)
    {
        $deleteForm = $this->createDeleteForm($situacionEconomica);
        $editForm = $this->createForm('SICBundle\Form\SituacionEconomicaType', $situacionEconomica);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($situacionEconomica);
            $bitacora = new Bitacora($this->getUser(),'modificó','la informacion Situación Económica de la planilla '.$situacionEconomica->getPlanilla()->getId());
            $em->persist($bitacora);
            $em->flush();

            $this->addFlash('success', 'Se ha modificado la información de la Situación Económica de forma exitosa.');
            return $this->redirectToRoute('planillas_show', array('id' => $situacionEconomica->getPlanilla()->getId()));
        }

        return $this->render('situacioneconomica/edit.html.twig', array(
            'situacionEconomica' => $situacionEconomica,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a SituacionEconomica entity.
     *
     */
    public function deleteAction(Request $request, SituacionEconomica $situacionEconomica)
    {
        $form = $this->createDeleteForm($situacionEconomica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($situacionEconomica);
            $em->flush();
        }

        return $this->redirectToRoute('situacioneconomica_index');
    }

    /**
     * Creates a form to delete a SituacionEconomica entity.
     *
     * @param SituacionEconomica $situacionEconomica The SituacionEconomica entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SituacionEconomica $situacionEconomica)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('situacioneconomica_delete', array('id' => $situacionEconomica->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

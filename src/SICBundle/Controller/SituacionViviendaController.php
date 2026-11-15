<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\SituacionVivienda;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\SituacionViviendaType;

/**
 * SituacionVivienda controller.
 *
 */
class SituacionViviendaController extends BaseController
{
    private function obtenerStat()
    {
        $em = $this->getDoctrine()->getManager();
        $situacionViviendas = $em->getRepository(\SICBundle\Entity\SituacionVivienda::class)->findAll();
        $total = sizeof($situacionViviendas);

        $tipo_vivienda = $em->getRepository(\SICBundle\Entity\AdminTipoVivienda::class)->findAll();
        $stat_tipo_vivienda = array();
        foreach ($tipo_vivienda as $elemento) {
            array_push(
                $stat_tipo_vivienda, 
                array(
                    'tipo_vivienda' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionVivienda::class)->findBy(
                                        array('tipo' => $elemento->getId())
                                        ))
                    )
            );
        }

        $tipo_tenencia = $em->getRepository(\SICBundle\Entity\AdminTipoTenencia::class)->findAll();
        $stat_tipo_tenencia = array();
        foreach ($tipo_tenencia as $elemento) {
            array_push(
                $stat_tipo_tenencia, 
                array(
                    'tipo_tenencia' => $elemento->getForma(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionVivienda::class)->findBy(
                                        array('tenencia' => $elemento->getId())
                                        ))
                    )
            );
        }

        $terreno = $em->getRepository(\SICBundle\Entity\AdminRespCerrada::class)->findAll();
        $stat_terreno = array();
        foreach ($terreno as $elemento) {
            array_push(
                $stat_terreno, 
                array(
                    'resp' => $elemento->getRespuesta(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionVivienda::class)->findBy(
                                        array('terrenoPropio' => $elemento->getId())
                                        ))
                    )
            );
        }

        $ovc = $em->getRepository(\SICBundle\Entity\AdminRespCerrada::class)->findAll();
        $stat_ovc = array();
        foreach ($ovc as $elemento) {
            array_push(
                $stat_ovc, 
                array(
                    'resp' => $elemento->getRespuesta(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionVivienda::class)->findBy(
                                        array('ovc' => $elemento->getId())
                                        ))
                    )
            );
        }

        $habitaciones = $em->getRepository(\SICBundle\Entity\AdminTipoHabitacionesVivienda::class)->findAll();
        $stat_habitaciones = array();
        foreach ($habitaciones as $elemento) {
            array_push(
                $stat_habitaciones, 
                array(
                    'habitaciones' => $elemento->getNombre(),
                    'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\SituacionVivienda::class)->findByHabitaciones($elemento)))
            );
        }

        $stat_cant_habitaciones = array();
        foreach ($situacionViviendas as $elemento) {
            if (array_key_exists($elemento->getCantidadHabitaciones(), $stat_cant_habitaciones)) {
                $stat_cant_habitaciones[$elemento->getCantidadHabitaciones()] = $stat_cant_habitaciones[$elemento->getCantidadHabitaciones()] + 1;
            }else{
                $stat_cant_habitaciones[$elemento->getCantidadHabitaciones()] = 1;
            }
        }

        $paredes = $em->getRepository(\SICBundle\Entity\AdminTipoParedes::class)->findAll();
        $stat_paredes = array();
        foreach ($paredes as $elemento) {
            array_push(
                $stat_paredes, 
                array(
                    'paredes' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionVivienda::class)->findBy(
                                        array('paredes' => $elemento->getId())
                                        ))
                    )
            );
        }

        $techo = $em->getRepository(\SICBundle\Entity\AdminTipoTecho::class)->findAll();
        $stat_techo = array();
        foreach ($techo as $elemento) {
            array_push(
                $stat_techo, 
                array(
                    'techo' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionVivienda::class)->findBy(
                                        array('techo' => $elemento->getId())
                                        ))
                    )
            );
        }

        $enseres = $em->getRepository(\SICBundle\Entity\AdminTipoEnseres::class)->findAll();
        $stat_enseres = array();
        foreach ($enseres as $elemento) {
            array_push(
                $stat_enseres, 
                array(
                    'enseres' => $elemento->getNombre(),
                    'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\SituacionVivienda::class)->findByEnseres($elemento)))
            );
        }

        $salubridad = $em->getRepository(\SICBundle\Entity\AdminSalubridadVivienda::class)->findAll();
        $stat_salubridad = array();
        foreach ($salubridad as $elemento) {
            array_push(
                $stat_salubridad, 
                array(
                    'salubridad' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionVivienda::class)->findBy(
                                        array('salubridad' => $elemento->getId())
                                        ))
                    )
            );
        }

        $plagas = $em->getRepository(\SICBundle\Entity\AdminTipoPlagas::class)->findAll();
        $stat_plagas = array();
        foreach ($plagas as $elemento) {
            array_push(
                $stat_plagas, 
                array(
                    'plagas' => $elemento->getNombre(),
                    'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\SituacionVivienda::class)->findByPresenciaInsectos($elemento)))
            );
        }

        $mascotas = $em->getRepository(\SICBundle\Entity\AdminTipoMascotas::class)->findAll();
        $stat_mascotas = array();
        foreach ($mascotas as $elemento) {
            array_push(
                $stat_mascotas, 
                array(
                    'mascotas' => $elemento->getNombre(),
                    'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\SituacionVivienda::class)->findByMascota($elemento)))
            );
        }

        $condicion_terreno = $em->getRepository(\SICBundle\Entity\AdminTipoCondicionTerreno::class)->findAll();
        $stat_condicion_terreno = array();
        foreach ($condicion_terreno as $elemento) {
            array_push(
                $stat_condicion_terreno, 
                array(
                    'condicion_terreno' => $elemento->getNombre(),
                    'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\SituacionVivienda::class)->findBy(
                                        array('condicionesTerreno' => $elemento->getId()))
                                    ))
            );
        }

        $sivih = $em->getRepository(\SICBundle\Entity\AdminRespCerrada::class)->findAll();
        $stat_sivih = array();
        foreach ($sivih as $elemento) {
            array_push(
                $stat_sivih, 
                array(
                    'resp' => $elemento->getRespuesta(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionVivienda::class)->findBy(
                                        array('sivih' => $elemento->getId())
                                        ))
                    )
            );
        }

        $leypoliticahabitacional = $em->getRepository(\SICBundle\Entity\AdminRespCerrada::class)->findAll();
        $stat_leypoliticahabitacional = array();
        foreach ($leypoliticahabitacional as $elemento) {
            array_push(
                $stat_leypoliticahabitacional, 
                array(
                    'resp' => $elemento->getRespuesta(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionVivienda::class)->findBy(
                                        array('leypoliticahabitacional' => $elemento->getId())
                                        ))
                    )
            );
        }

        return array(
            'situacionViviendas' => $situacionViviendas,
            'stat_tipo_vivienda' => $stat_tipo_vivienda,
            'stat_tipo_tenencia' => $stat_tipo_tenencia,
            'stat_ovc' => $stat_ovc,
            'stat_terreno' => $stat_terreno,
            'stat_cant_habitaciones' => $stat_cant_habitaciones,
            'stat_mascotas' => $stat_mascotas,
            'stat_plagas' => $stat_plagas,
            'stat_enseres' => $stat_enseres,
            'stat_habitaciones' => $stat_habitaciones,
            'stat_salubridad' => $stat_salubridad,
            'stat_techo' => $stat_techo,
            'stat_paredes' => $stat_paredes,
            'stat_condicion_terreno' => $stat_condicion_terreno,
            'stat_sivih' => $stat_sivih,
            'stat_leypoliticahabitacional' => $stat_leypoliticahabitacional,
            'total' => $total,
        );
    }

    /**
     * Lists all SituacionVivienda entities.
     *
     */
    public function indexAction()
    {
        $stats = $this->obtenerStat();
        return $this->render('situacionvivienda/index.html.twig', $stats);
    }

    /**
     * Creates a new SituacionVivienda entity.
     *
     */
    public function newAction(Request $request, $id_planilla)
    {
        /*Redireccionar cuando se accede por GET y evitar que se cree una nueva para la misma planilla*/
        $em = $this->getDoctrine()->getManager();
        $planilla = $em->getRepository(\SICBundle\Entity\Planillas::class)->findById($id_planilla);
        $p = $planilla[0];

        if($p->getSituacionVivienda() != NULL){
            $this->addFlash('error', 'Seleccione la sección que desea modificar');
            return $this->redirectToRoute('planillas_show', array('id' => $id_planilla));
        }

        $situacionVivienda = new SituacionVivienda();
        $form = $this->createForm(SituacionViviendaType::class, $situacionVivienda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($situacionVivienda);
            $p->setSituacionVivienda($situacionVivienda);
            $p->setTerminada('45');
            $em->persist($p);
            $bitacora = new Bitacora($this->getUser(),'agregó','la Situación de Vivienda a la planilla '.$id_planilla);
            $em->persist($bitacora);
            $em->flush();

            $this->addFlash('success', 'Se ha agregado la informacion de Situación de Vivienda de forma exitosa');

            return $this->redirectToRoute('situacionsalud_new', array('id_planilla' => $id_planilla));
            // return $this->redirectToRoute('situacionvivienda_show', array('id' => $situacionVivienda->getId()));
        }

        return $this->render('situacionvivienda/new.html.twig', array(
            'situacionVivienda' => $situacionVivienda,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a SituacionVivienda entity.
     *
     */
    public function showAction(SituacionVivienda $situacionVivienda)
    {
        $deleteForm = $this->createDeleteForm($situacionVivienda);

        return $this->render('situacionvivienda/show.html.twig', array(
            'situacionVivienda' => $situacionVivienda,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing SituacionVivienda entity.
     *
     */
    public function editAction(Request $request, SituacionVivienda $situacionVivienda)
    {
        $deleteForm = $this->createDeleteForm($situacionVivienda);
        $editForm = $this->createForm(SituacionViviendaType::class, $situacionVivienda);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($situacionVivienda);
            $bitacora = new Bitacora($this->getUser(),'modificó','la informacion Situación de Salud de la planilla '.$situacionVivienda->getPlanilla()->getId());
            $em->persist($bitacora);
            $em->flush();

            $this->addFlash('success', 'Se han actualizado los datos de la Situación de Vivienda de forma exitosa.');

            return $this->redirectToRoute('planillas_show', array('id' => $situacionVivienda->getPlanilla()->getId()));
            // return $this->redirectToRoute('situacionvivienda_edit', array('id' => $situacionVivienda->getId()));
        }

        return $this->render('situacionvivienda/edit.html.twig', array(
            'situacionVivienda' => $situacionVivienda,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a SituacionVivienda entity.
     *
     */
    public function deleteAction(Request $request, SituacionVivienda $situacionVivienda)
    {
        $form = $this->createDeleteForm($situacionVivienda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($situacionVivienda);
            $em->flush();
        }

        return $this->redirectToRoute('situacionvivienda_index');
    }

    /**
     * Creates a form to delete a SituacionVivienda entity.
     *
     * @param SituacionVivienda $situacionVivienda The SituacionVivienda entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SituacionVivienda $situacionVivienda)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('situacionvivienda_delete', array('id' => $situacionVivienda->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

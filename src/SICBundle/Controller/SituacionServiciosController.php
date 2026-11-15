<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\SituacionServicios;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\SituacionServiciosType;

/**
 * SituacionServicios controller.
 *
 */
class SituacionServiciosController extends BaseController
{
    private function obtenerStats()
    {
        $em = $this->getDoctrine()->getManager();

        $situacionServicios = $em->getRepository(\SICBundle\Entity\SituacionServicios::class)->findAll();
        
        $total = sizeof($situacionServicios);

        $aguasb = $em->getRepository(\SICBundle\Entity\AdminAguasBlancas::class)->findAll();
        $stat_aguasb = array();
        foreach ($aguasb as $elemento) {
            array_push(
                $stat_aguasb, 
                array(
                    'aguasb' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionServicios::class)->findBy(
                                        array('aguasBlancas' => $elemento->getId())
                                        ))
                    )
            );
        }

        $aguasservidas = $em->getRepository(\SICBundle\Entity\AdminAguasServidas::class)->findAll();
        $stat_aguasservidas = array();
        foreach ($aguasservidas as $elemento) {
            array_push(
                $stat_aguasservidas, 
                array(
                    'aguasservidas' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionServicios::class)->findBy(
                                        array('aguasServidas' => $elemento->getId())
                                        ))
                    )
            );
        }

        $gas = $em->getRepository(\SICBundle\Entity\AdminTipoGas::class)->findAll();
        $stat_gas = array();
        foreach ($gas as $elemento) {
            array_push(
                $stat_gas, 
                array(
                    'gas' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionServicios::class)->findBy(
                                        array('gas' => $elemento->getId())
                                        ))
                    )
            );
        }

        $sistemas_electrico = $em->getRepository(\SICBundle\Entity\AdminSistemaElectrico::class)->findAll();
        $stat_sistemas_electrico = array();
        foreach ($sistemas_electrico as $elemento) {
            array_push(
                $stat_sistemas_electrico, 
                array(
                    'sistemas_electrico' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionServicios::class)->findBy(
                                        array('sistemaElectrico' => $elemento->getId())
                                        ))
                    )
            );
        }

        $basura = $em->getRepository(\SICBundle\Entity\AdminRecoleccionBasura::class)->findAll();
        $stat_basura = array();
        foreach ($basura as $elemento) {
            array_push(
                $stat_basura, 
                array(
                    'basura' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionServicios::class)->findBy(
                                        array('recoleccionBasura' => $elemento->getId())
                                        ))
                    )
            );
        }

        $telefonia = $em->getRepository(\SICBundle\Entity\AdminTipoTelefonia::class)->findAll();
        $stat_telefonia = array();
        foreach ($telefonia as $elemento) {
            array_push(
                $stat_telefonia, 
                array(
                    'telefonia' => $elemento->getNombre(),
                    'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\SituacionServicios::class)->telefonia($elemento)))
            );
        }

        $transporte = $em->getRepository(\SICBundle\Entity\AdminTipoTransporte::class)->findAll();
        $stat_transporte = array();
        foreach ($transporte as $elemento) {
            array_push(
                $stat_transporte, 
                array(
                    'transporte' => $elemento->getNombre(),
                    'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\SituacionServicios::class)->transporte($elemento)))
            );
        }

        $mecanismoInformacion = $em->getRepository(\SICBundle\Entity\AdminMecanismoInformacion::class)->findAll();
        $stat_mecanismoInformacion = array();
        foreach ($mecanismoInformacion as $elemento) {
            array_push(
                $stat_mecanismoInformacion, 
                array(
                    'mecanismoInformacion' => $elemento->getNombre(),
                    'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\SituacionServicios::class)->mecanismoInformacion($elemento)))
            );
        }

        $serviciosComunales = $em->getRepository(\SICBundle\Entity\AdminServiciosComunales::class)->findAll();
        $stat_serviciosComunales = array();
        foreach ($serviciosComunales as $elemento) {
            array_push(
                $stat_serviciosComunales, 
                array(
                    'serviciosComunales' => $elemento->getNombre(),
                    'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\SituacionServicios::class)->serviciosComunales($elemento)))
            );
        }

        $resp_cerradas = $em->getRepository(\SICBundle\Entity\AdminRespCerrada::class)->findAll();
        $stat_medidor = array();
        foreach ($resp_cerradas as $resp) {
            array_push(
                $stat_medidor, 
                array(
                    'resp' => $resp->getRespuesta(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionServicios::class)->findBy(
                                        array('medidor' => $resp->getId())
                                        ))
                    )
            );
        }

        $si = 0; $no = 0;
        $tanques = $em->getRepository(\SICBundle\Entity\SituacionServicios::class)->findAll();
        $stat_tanque = array();
        $stat_pipotes = array();
        foreach ($tanques as $t) { if ($t->getLtsTanque() > 0) { $si++; }else{ $no++; } }
        array_push($stat_tanque, array('resp' => 'Si', 'cantidad' => $si));
        array_push($stat_tanque, array('resp' => 'No', 'cantidad' => $no));

        $si = 0; $no = 0;
        foreach ($tanques as $t) { if ($t->getCantPipotes() > 0) { $si++; }else{ $no++; } }
        array_push($stat_pipotes, array('resp' => 'Si', 'cantidad' => $si));
        array_push($stat_pipotes, array('resp' => 'No', 'cantidad' => $no));

        $empresaGas = $em->getRepository(\SICBundle\Entity\AdminEmpresaGas::class)->findAll();
        $stat_empresaGas = array();
        foreach ($empresaGas as $elemento) {
            array_push(
                $stat_empresaGas, 
                array(
                    'empresaGas' => $elemento->getNombre(),
                    'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\SituacionServicios::class)->findBy(
                        array('empresaGas' => $elemento->getId()))))
            );
        }

        $capacidadBombona = $em->getRepository(\SICBundle\Entity\AdminCapacidadBombona::class)->findAll();
        $stat_capacidadBombona = array();
        foreach ($capacidadBombona as $elemento) {
            array_push(
                $stat_capacidadBombona, 
                array(
                    'capacidadBombona' => $elemento->getNombre(),
                    'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\SituacionServicios::class)->findBy(
                        array('capacidadBombona' => $elemento->getId()))))
            );
        }

        $resp_cerradas = $em->getRepository(\SICBundle\Entity\AdminRespCerrada::class)->findAll();
        $stat_bombillosAhorradores = array();
        foreach ($resp_cerradas as $resp) {
            array_push(
                $stat_bombillosAhorradores, 
                array(
                    'resp' => $resp->getRespuesta(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionServicios::class)->findBy(
                                        array('bombillosAhorradores' => $resp->getId())
                                        ))
                    )
            );
        }

        $resp_cerradas = $em->getRepository(\SICBundle\Entity\AdminRespCerrada::class)->findAll();
        $stat_medidor_electrico = array();
        foreach ($resp_cerradas as $resp) {
            array_push(
                $stat_medidor_electrico, 
                array(
                    'resp' => $resp->getRespuesta(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\SituacionServicios::class)->findBy(
                                        array('medidorElectrico' => $resp->getId())
                                        ))
                    )
            );
        }

        return array(
            'situacionServicios' => $situacionServicios,
            'stat_serviciosComunales' => $stat_serviciosComunales,
            'stat_mecanismoInformacion' => $stat_mecanismoInformacion,
            'stat_transporte' => $stat_transporte,
            'stat_telefonia' => $stat_telefonia,
            'stat_basura' => $stat_basura,
            'stat_sistemas_electrico' => $stat_sistemas_electrico,
            'stat_medidor_electrico' => $stat_medidor_electrico,
            'stat_gas' => $stat_gas,
            'stat_aguasservidas' => $stat_aguasservidas,
            'stat_aguasb' => $stat_aguasb,
            'stat_bombillosAhorradores' => $stat_bombillosAhorradores,
            'stat_medidor' => $stat_medidor,
            'stat_tanque' => $stat_tanque,
            'stat_pipotes' => $stat_pipotes,
            'stat_empresaGas' => $stat_empresaGas,
            'stat_capacidadBombona' => $stat_capacidadBombona,
            'total' => $total,
        );
    }

    /**
     * Lists all SituacionServicios entities.
     *
     */
    public function indexAction()
    {
        $stats = $this->obtenerStats();
        return $this->render('situacionservicios/index.html.twig', $stats);
    }

    /**
     * Creates a new SituacionServicios entity.
     *
     */
    public function newAction(Request $request, $id_planilla)
    {
        /*Redireccionar cuando se accede por GET y evitar que se cree una nueva para la misma planilla*/
        $em = $this->getDoctrine()->getManager();
        $planilla = $em->getRepository(\SICBundle\Entity\Planillas::class)->findById($id_planilla);
        $p = $planilla[0];

        if($p->getSituacionServicios() != NULL){
            $this->addFlash('error', 'Seleccione la sección que desea modificar');
            return $this->redirectToRoute('planillas_show', array('id' => $id_planilla));
        }

        $situacionServicio = new SituacionServicios();
        $form = $this->createForm(SituacionServiciosType::class, $situacionServicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($situacionServicio);
            $p->setSituacionServicios($situacionServicio);
            $p->setTerminada('75');
            $em->persist($p);
            $bitacora = new Bitacora($this->getUser(),'agregó','la Situación de Servicios a la planilla '.$id_planilla);
            $em->persist($bitacora);
            $em->flush();

            $this->addFlash('success', 'Se ha agregado la informacion de Servicios de forma exitosa');

            return $this->redirectToRoute('participacioncomunitaria_new', array('id_planilla' => $id_planilla));
        }

        return $this->render('situacionservicios/new.html.twig', array(
            'duracionesBombona' => $this->duracionesBombonas(),
            'situacionServicio' => $situacionServicio,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a SituacionServicios entity.
     *
     */
    public function showAction(SituacionServicios $situacionServicio)
    {
        $deleteForm = $this->createDeleteForm($situacionServicio);

        return $this->render('situacionservicios/show.html.twig', array(
            'situacionServicio' => $situacionServicio,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing SituacionServicios entity.
     *
     */
    public function editAction(Request $request, SituacionServicios $situacionServicio)
    {
        $deleteForm = $this->createDeleteForm($situacionServicio);
        $editForm = $this->createForm(SituacionServiciosType::class, $situacionServicio);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($situacionServicio);
            $bitacora = new Bitacora($this->getUser(),'modificó','la informacion Situación de Servicios de la planilla '.$situacionServicio->getPlanilla()->getId());
            $em->persist($bitacora);
            $em->flush();

            $this->addFlash('success', 'Se han almacena los datos de Situación de Servicios de forma exitosa.');
            return $this->redirectToRoute('planillas_show', array('id' => $situacionServicio->getPlanilla()->getId()));
        }

        return $this->render('situacionservicios/edit.html.twig', array(
            'situacionServicio' => $situacionServicio,
            'duracionesBombona' => $this->duracionesBombonas(),
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a SituacionServicios entity.
     *
     */
    public function deleteAction(Request $request, SituacionServicios $situacionServicio)
    {
        $form = $this->createDeleteForm($situacionServicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($situacionServicio);
            $em->flush();
        }

        return $this->redirectToRoute('situacionservicios_index');
    }

    /**
     * Creates a form to delete a SituacionServicios entity.
     *
     * @param SituacionServicios $situacionServicio The SituacionServicios entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(SituacionServicios $situacionServicio)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('situacionservicios_delete', array('id' => $situacionServicio->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function duracionesBombonas()
    {
        $em = $this->getDoctrine()->getManager();
        $res = $em->getRepository(\SICBundle\Entity\SituacionServicios::class)->findDuracionesBombona();
        return $res;
    }
}

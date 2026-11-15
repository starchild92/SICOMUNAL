<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Entity\JefeGrupoFamiliar;
use SICBundle\Form\JefeGrupoFamiliarType;

/**
 * JefeGrupoFamiliar controller.
 *
 */
class JefeGrupoFamiliarController extends BaseController
{
    /**
     * Lists all JefeGrupoFamiliar entities.
     *
     */
    public function indexAction(Request $request)
    {
        $stats = $this->obtenerEstadisticas($request);
        return $this->render('jefegrupofamiliar/index.html.twig', $stats);
    }

    public function getPersonaByCedula($cedula)
    {
        $em = $this->getDoctrine()->getManager();
        $resultado = $em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(array('cedula' => $cedula));
        if (sizeof($resultado) > 0) {
            return $resultado[0];
        }else{
            $resultado = $em->getRepository(\SICBundle\Entity\Persona::class)->findBy(array('cedula' => $cedula));
            if (sizeof($resultado) > 0) {
                return $resultado[0];
            }else{
                return null;
            }
        }
    }

    /**
     * Creates a new JefeGrupoFamiliar entity.
     *
     */
    public function newAction(Request $request, $id_planilla)
    {
        /*Redireccionar cuando se accede por GET y evitar que se cree una nueva para la misma planilla*/
        $em = $this->getDoctrine()->getManager();
        $planilla = $em->getRepository(\SICBundle\Entity\Planillas::class)->findById($id_planilla);
        $p = $planilla[0];

        if($p->getJefeGrupoFamiliar() != NULL){
            $this->addFlash('danger', 'Seleccione la sección que desea modificar');
            return $this->redirectToRoute('planillas_show', array('id' => $id_planilla));
        }

        $jefeGrupoFamiliar = new JefeGrupoFamiliar();
        $form = $this->createForm(JefeGrupoFamiliarType::class, $jefeGrupoFamiliar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        	if ($this->getPersonaByCedula($jefeGrupoFamiliar->getCedula()) == NULL) {
                if ($jefeGrupoFamiliar->getEmail() != '') {
                    $jefeGrupoFamiliar->setRecibirCorreo(true);
                }else{
                    $jefeGrupoFamiliar->setRecibirCorreo(false);
                }
	            $p->setJefeGrupoFamiliar($jefeGrupoFamiliar);
                $p->setTerminada('10');
	            $em->persist($jefeGrupoFamiliar);
	            $em->persist($p);
                $this->addFlash('success', 'Se ha creado un Jefe de Grupo Familiar');
                $bitacora = new Bitacora($this->getUser(),'agregó','un Jefe de Grupo Familiar a la planilla '.$id_planilla);
                $em->persist($bitacora);
	            $em->flush();

	            // redirigir a (Caracteristicas Grupo Familiar)
	            return $this->redirectToRoute('grupofamiliar_new', array('id_planilla' => $id_planilla, 'id_grupofamiliar' => 0));
        	}else{
        		$this->addFlash('error', 'Ya existe una persona con este número de cédula, verifica sus datos en la Agenda de la Comunidad. Para poder agregarlo como Jefe de Grupo Familiar debe eliminarlo como miembro de Grupo Familiar.');
        	}
        }

        return $this->render('jefegrupofamiliar/new.html.twig', array(
            'jefeGrupoFamiliar' => $jefeGrupoFamiliar,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a JefeGrupoFamiliar entity.
     *
     */
    public function showAction(JefeGrupoFamiliar $jefeGrupoFamiliar)
    {
        $deleteForm = $this->createDeleteForm($jefeGrupoFamiliar);

        return $this->render('jefegrupofamiliar/show.html.twig', array(
            'jefeGrupoFamiliar' => $jefeGrupoFamiliar,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing JefeGrupoFamiliar entity.
     *
     */
    public function editAction(Request $request, JefeGrupoFamiliar $jefeGrupoFamiliar)
    {
        $deleteForm = $this->createDeleteForm($jefeGrupoFamiliar);
        $editForm = $this->createForm(JefeGrupoFamiliarType::class, $jefeGrupoFamiliar);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            if ($jefeGrupoFamiliar->getEmail() != '') {
                $jefeGrupoFamiliar->setRecibirCorreo(true);
            }else{
                $jefeGrupoFamiliar->setRecibirCorreo(false);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($jefeGrupoFamiliar);
            $bitacora = new Bitacora($this->getUser(),'modificó','un Jefe de Grupo Familiar');
            $em->persist($bitacora);
            $em->flush();

            $this->addFlash('success', 'Se han guardado los cambios de Jefe de Grupo Familiar');

            return $this->redirectToRoute('planillas_show', array('id' => $jefeGrupoFamiliar->getPlanilla()->getId()));
        }

        return $this->render('jefegrupofamiliar/edit.html.twig', array(
            'jefeGrupoFamiliar' => $jefeGrupoFamiliar,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a JefeGrupoFamiliar entity.
     *
     */
    public function deleteAction(Request $request, JefeGrupoFamiliar $jefeGrupoFamiliar)
    {
        $form = $this->createDeleteForm($jefeGrupoFamiliar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se ha eliminado a '.$jefeGrupoFamiliar->nombreyapellido().' Jefe de Grupo Familiar');
            $bitacora = new Bitacora($this->getUser(),'eliminó','a '.$jefeGrupoFamiliar->nombreyapellido().' un Jefe de Grupo Familiar');
            $em->remove($jefeGrupoFamiliar);
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('jefegrupofamiliar_index');
    }

    /**
     * Creates a form to delete a JefeGrupoFamiliar entity.
     *
     * @param JefeGrupoFamiliar $jefeGrupoFamiliar The JefeGrupoFamiliar entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(JefeGrupoFamiliar $jefeGrupoFamiliar)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('jefegrupofamiliar_delete', array('id' => $jefeGrupoFamiliar->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function obtenerEstadisticas($request)
    {
        $em = $this->getDoctrine()->getManager();

        $jefeGrupoFamiliars = $em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findAll();
        $total = sizeof($jefeGrupoFamiliars);

        $nacionalidades = $em->getRepository(\SICBundle\Entity\AdminNacionalidad::class)->findAll();
        $stat_nacionalidad = array();
        foreach ($nacionalidades as $nacionalidad) {
            array_push(
                $stat_nacionalidad, 
                array(
                    'nacionalidad' => $nacionalidad->getNacionalidad(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(
                                        array('nacionalidad' => $nacionalidad->getId())
                                        ))
                    )
            );
        }

        $stat_sexo = array();
        array_push(
            $stat_sexo, 
            array(
                'sexo' => 'Masculino',
                'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(
                                    array('sexo' => 'Masculino')
                                    ))
                )
        );
        array_push(
            $stat_sexo, 
            array(
                'sexo' => 'Femenino',
                'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(
                                    array('sexo' => 'Femenino')
                                    ))
                )
        );

        $resp_cerradas = $em->getRepository(\SICBundle\Entity\AdminRespCerrada::class)->findAll();
        $stat_cne = array();
        foreach ($resp_cerradas as $resp) {
            array_push($stat_cne, array('resp' => $resp->getRespuesta(),'cantidad'=> sizeof($em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(array('cne' => $resp->getId())))));
        }
        $stat_embarazada = array();
        foreach ($resp_cerradas as $resp) {
            array_push($stat_embarazada, array('resp' => $resp->getRespuesta(),'cantidad'=> sizeof($em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(array('embarazada' => $resp->getId())))));
        }
        
        $stat_empleado = array();
        foreach ($resp_cerradas as $resp) {
            array_push(
                $stat_empleado, 
                array(
                    'resp' => $resp->getRespuesta(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(
                                        array('trabajaActualmente' => $resp->getId())
                                        ))
                    )
            );
        }

        $edo_civil = $em->getRepository(\SICBundle\Entity\AdminEstadoCivil::class)->findAll();
        $stat_edo_civil = array();
        foreach ($edo_civil as $elemento) {
            array_push(
                $stat_edo_civil, 
                array(
                    'edo_civil' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(
                                        array('estadoCivil' => $elemento->getId())
                                        ))
                    )
            );
        }

        $instruccion = $em->getRepository(\SICBundle\Entity\AdminNivelInstruccion::class)->findAll();
        $stat_instruccion = array();
        foreach ($instruccion as $elemento) {
            array_push(
                $stat_instruccion, 
                array(
                    'instruccion' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(
                                        array('nivelInstruccion' => $elemento->getId())
                                        ))
                    )
            );
        }

        $profesiones = $em->getRepository(\SICBundle\Entity\AdminProfesion::class)->findAll();
        $stat_profesiones = array();
        foreach ($profesiones as $elemento) {
            array_push(
                $stat_profesiones, 
                array(
                    'profesiones' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(
                                        array('profesion' => $elemento->getId())
                                        ))
                    )
            );
        }

        $incapacidades = $em->getRepository(\SICBundle\Entity\AdminIncapacidades::class)->findAll();
        $stat_incapacidades = array();
        foreach ($incapacidades as $elemento) {
            array_push(
                $stat_incapacidades, 
                array(
                    'incapacidades' => $elemento->getIncapacidad(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(
                                        array('incapacitadoTipo' => $elemento->getId())
                                        ))
                    )
            );
        }

        $pensionados = $em->getRepository(\SICBundle\Entity\AdminPensionadoInstitucion::class)->findAll();
        $stat_pensionados = array();
        foreach ($pensionados as $elemento) {
            array_push(
                $stat_pensionados, 
                array(
                    'pensionados' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(
                                        array('pensionadoInstitucion' => $elemento->getId())
                                        ))
                    )
            );
        }

        $ingresofam = $em->getRepository(\SICBundle\Entity\AdminClasIngresoFamiliar::class)->findAll();
        $stat_ingresofam = array();
        foreach ($ingresofam as $elemento) {
            array_push($stat_ingresofam, array('ingresofam' => $elemento->getNombre(),'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(array('ingresoFamiliar' => $elemento->getId())))));
        }


        return array(
            'total' => $total,
            'stat_nacionalidad' => $stat_nacionalidad,
            'stat_sexo' => $stat_sexo,
            'stat_cne' => $stat_cne,
            'stat_edo_civil' => $stat_edo_civil,
            'stat_instruccion' => $stat_instruccion,
            'stat_profesiones' => $stat_profesiones,
            'stat_empleado' => $stat_empleado,
            'stat_incapacidades' => $stat_incapacidades,
            'stat_pensionados' => $stat_pensionados,
            'stat_ingresofam' => $stat_ingresofam,
            'stat_embarazada' => $stat_embarazada,
            'base_dir' => $this->get('kernel')->getRootDir() . '/../web' . $request->getBasePath(),
        );
    }

    public function vistaDocAction(Request $request)
    {
        $stats = $this->obtenerEstadisticas($request);
        return $this->render('jefegrupofamiliar/resumen_doc.html.twig', $stats);
    }

    public function obtenerDocumentoAction(Request $request){

        $stats = $this->obtenerEstadisticas($request);
        $html = $this->renderView('jefegrupofamiliar/resumen_doc.html.twig', $stats);
        
        echo "Actualizar a DOMPDF";
        die();
        // return new Response(
        //     $this->get('knp_snappy.pdf')->getOutputFromHtml($html, 
        //         array(
        //             'images'            => true,
        //             'no-background'     => false,
        //             'footer-right'      => utf8_decode('Pagina [page] de [topage] - '.date('\ d-m-Y\ h:i a')),
        //             'footer-left'       => utf8_decode('Sistema de Información Comunal')
        //             )),
        //     200,
        //     array(
        //         'Content-Type'          => 'application/pdf',
        //         'Content-Disposition'   => 'attachment; filename="ReporteJefesGrupoFamiliar.pdf"'
        // ));

    }
}

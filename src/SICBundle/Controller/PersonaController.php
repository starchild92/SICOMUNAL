<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Persona;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\PersonaType;

/**
 * Persona controller.
 *
 */
class PersonaController extends BaseController
{
    private function obtenerStats(){
        $em = $this->getDoctrine()->getManager();

        $personas = $em->getRepository('SICBundle:Persona')->findAll();

        $total = sizeof($personas);
        $stat_edad = array();
        $mayorh = 0;
        $menorh = 0;
        $mayorm = 0;
        $menorm = 0;
        foreach ($personas as $p) {
            if ($p->getEdad() >= 18 ) {
                if ($p->getSexo() == 'Femenino') {
                    $mayorm++;
                }else{
                    $mayorh++;
                }
            }else{
                if ($p->getSexo() == 'Femenino') {
                    $menorm++;
                }else{
                    $menorh++;
                }
            }
        }
        array_push($stat_edad, array('a' => 'Hombres Mayores de Edad', 'cantidad' => $mayorh));
        array_push($stat_edad, array('a' => 'Hombres Menores de Edad', 'cantidad' => $menorh));
        array_push($stat_edad, array('a' => 'Mujeres Mayores de Edad', 'cantidad' => $mayorm));
        array_push($stat_edad, array('a' => 'Mujeres Menores de Edad', 'cantidad' => $menorm));

        $stat_sexo = array();
        array_push(
            $stat_sexo, 
            array(
                'sexo' => 'Masculino',
                'cantidad' => sizeof($em->getRepository('SICBundle:Persona')->findBy(
                                    array('sexo' => 'Masculino')
                                    ))
                )
        );
        array_push(
            $stat_sexo, 
            array(
                'sexo' => 'Femenino',
                'cantidad' => sizeof($em->getRepository('SICBundle:Persona')->findBy(
                                    array('sexo' => 'Femenino')
                                    ))
                )
        );
        $resp_cerradas = $em->getRepository('SICBundle:AdminRespCerrada')->findAll();
        $stat_cne = array();
        foreach ($resp_cerradas as $resp) {
            array_push(
                $stat_cne, 
                array(
                    'resp' => $resp->getRespuesta(),
                    'cantidad'     => sizeof($em->getRepository('SICBundle:Persona')->findBy(
                                        array('cne' => $resp->getId())
                                        ))
                    )
            );
        }
        $stat_embarazo = array();
        foreach ($resp_cerradas as $resp) {
            array_push(
                $stat_embarazo, 
                array(
                    'resp' => $resp->getRespuesta(),
                    'cantidad'     => sizeof($em->getRepository('SICBundle:Persona')->findBy(
                                        array('embarazoTemprano' => $resp->getId())
                                        ))
                    )
            );
        }
        $instruccion = $em->getRepository('SICBundle:AdminNivelInstruccion')->findAll();
        $stat_instruccion = array();
        foreach ($instruccion as $elemento) {
            array_push(
                $stat_instruccion, 
                array(
                    'instruccion' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository('SICBundle:Persona')->findBy(
                                        array('gradoInstruccion' => $elemento->getId())
                                        ))
                    )
            );
        }
        $profesiones = $em->getRepository('SICBundle:AdminProfesion')->findAll();
        $stat_profesiones = array();
        foreach ($profesiones as $elemento) {
            array_push(
                $stat_profesiones, 
                array(
                    'profesiones' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository('SICBundle:Persona')->findBy(
                                        array('profesion' => $elemento->getId())
                                        ))
                    )
            );
        }

        $incapacidades = $em->getRepository('SICBundle:AdminIncapacidades')->findAll();
        $stat_incapacidades = array();
        foreach ($incapacidades as $elemento) {
            array_push(
                $stat_incapacidades, 
                array(
                    'incapacidades' => $elemento->getIncapacidad(),
                    'cantidad'     => sizeof($em->getRepository('SICBundle:Persona')->findBy(
                                        array('incapacitadoTipo' => $elemento->getId())
                                        ))
                    )
            );
        }

        $pensionados = $em->getRepository('SICBundle:AdminPensionadoInstitucion')->findAll();
        $stat_pensionados = array();
        foreach ($pensionados as $elemento) {
            array_push(
                $stat_pensionados, 
                array(
                    'pensionados' => $elemento->getNombre(),
                    'cantidad'     => sizeof($em->getRepository('SICBundle:Persona')->findBy(
                                        array('pensionadoInstitucion' => $elemento->getId())
                                        ))
                    )
            );
        }

        return array(
            'stat_sexo' => $stat_sexo,
            'stat_cne' => $stat_cne,
            'stat_instruccion' => $stat_instruccion,
            'stat_profesiones' => $stat_profesiones,
            'stat_incapacidades' => $stat_incapacidades,
            'stat_pensionados' => $stat_pensionados,
            'stat_embarazo' => $stat_embarazo,
            'stat_edad' => $stat_edad,
            'total' => $total,
        );
    }

    /**
     * Lists all Persona entities.
     *
     */
    public function indexAction()
    {
        $stat = $this->obtenerStats();
        return $this->render('persona/index.html.twig', $stat);
    }

    /**
     * Creates a new Persona entity.
     *
     */
    public function newAction(Request $request, $id_planilla, $id_grupofamiliar)
    {
        $persona = new Persona();
        $form = $this->createForm('SICBundle\Form\PersonaType', $persona);
        $form->handleRequest($request);
        
        $em = $this->getDoctrine()->getManager();
        $GrupoFam = $em->getRepository('SICBundle:GrupoFamiliar')->findById($id_grupofamiliar);
        // $cantMiembros = 1;
        if ($GrupoFam != NULL) {
            $Grupo = $GrupoFam[0];
            $aux = sizeof($Grupo->getMiembros()) + 1;
            $Grupo->setCantidadMiembros($aux);
        }else{
            $this->addFlash('error', 'Seleccione la sección que desea modificar');
            return $this->redirectToRoute('planillas_show', array('id' => $id_planilla));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $Grupo->addMiembro($persona);
            $aux = sizeof($Grupo->getMiembros()) + 1;
            $Grupo->setCantidadMiembros($aux);
            if ($persona->getEmail() != '') {
                $persona->setRecibirCorreo(true);
            }else{
                $persona->setRecibirCorreo(false);
            }
            $persona->setGrupofamiliar($Grupo);
            $em->persist($persona);
            $em->persist($Grupo);
            $bitacora = new Bitacora($this->getUser(),'agregó','a '.$persona->nombreyapellido().' al Grupo Familiar '.$Grupo->getApellidos());
            $em->persist($bitacora);
            $em->flush();

            $cantMiembros = sizeof($Grupo->getMiembros()) + 1;
            $this->addFlash('success', 'Se agregó a '.$persona->nombreyapellido().' al grupo familiar de forma correcta.');

            return $this->redirectToRoute('personas_new', array(
                'id_planilla' => $id_planilla,
                'id_grupofamiliar' => $id_grupofamiliar,
                'cantMiembros' => $cantMiembros));
        }

        $cantMiembros = sizeof($Grupo->getMiembros()) + 1;

        return $this->render('persona/new.html.twig', array(
            'persona' => $persona,
            'parentescos' => $this->obtener_parentescos(),
            'id_planilla' => $id_planilla,
            'id_grupofamiliar' => $id_grupofamiliar,
            'cantMiembros' => $cantMiembros,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Persona entity.
     *
     */
    public function showAction(Persona $persona)
    {
        $deleteForm = $this->createDeleteForm($persona);

        return $this->render('persona/show.html.twig', array(
            'persona' => $persona,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Persona entity.
     *
     */
    public function editAction(Request $request, Persona $persona)
    {
        $deleteForm = $this->createDeleteForm($persona);
        $editForm = $this->createForm('SICBundle\Form\PersonaType', $persona);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if ($persona->getEmail() != '') {
                $persona->setRecibirCorreo(true);
            }else{
                $persona->setRecibirCorreo(false);
            }
            $em->persist($persona);
            $bitacora = new Bitacora($this->getUser(),'modificó','los datos de '.$persona->nombreyapellido());
            $em->persist($bitacora);
            $em->flush();

            $this->addFlash('success', 'Se han actualizado los datos de forma correcta.');

            return $this->redirectToRoute('personas_show', array('id' => $persona->getId()));
        }

        return $this->render('persona/edit.html.twig', array(
            'persona' => $persona,
            'parentescos' => $this->obtener_parentescos(),
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Persona entity.
     *
     */
    public function deleteAction(Request $request, Persona $persona)
    {
        $form = $this->createDeleteForm($persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($persona);
            $bitacora = new Bitacora($this->getUser(),'eliminó','a '.$persona->nombreyapellido().' del Sistema');
            $em->persist($bitacora);

            $this->addFlash('success', 'Se ha Eliminado a '.$persona->nombreyapellido().' del Grupo Familiar de forma correcta.');

            $em->flush();
        }

        return $this->redirectToRoute('planillas_show', array('id' => $persona->getGrupofamiliar()->getPlanilla()->getId()));
    }

    /**
     * Creates a form to delete a Persona entity.
     *
     * @param Persona $persona The Persona entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Persona $persona)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('personas_delete', array('id' => $persona->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function cmp($a, $b){
        if((int)$a->getCedula() == (int)$b->getCedula())return 0;
        if((int)$a->getCedula()  > (int)$b->getCedula())return 1;
        if((int)$a->getCedula()  < (int)$b->getCedula())return -1;
    }
    public function agendaAction()
    {
        $em = $this->getDoctrine()->getManager();
        $jefes = $em->getRepository('SICBundle:JefeGrupoFamiliar')->findAll();
        $personass = $em->getRepository('SICBundle:Persona')->findAll();

        $personas = array();
        foreach ($jefes as $j) { array_push($personas, $j); }
        foreach ($personass as $p) { array_push($personas, $p); }

        usort($personas, array($this, "cmp"));

        return $this->render('persona/agenda_comunitaria.html.twig', array(
            'personas' => $personas,
        ));
    }

    public function nuevoMiembroAction(Request $request, $id_planilla, $id_grupofamiliar)
    {
        $persona = new Persona();
        $form = $this->createForm('SICBundle\Form\PersonaType', $persona);
        $form->handleRequest($request);
        
        $em = $this->getDoctrine()->getManager();
        $GrupoFam = $em->getRepository('SICBundle:GrupoFamiliar')->findById($id_grupofamiliar);
        // $cantMiembros = 1;
        if ($GrupoFam != NULL) {
            $Grupo = $GrupoFam[0];
            $aux = sizeof($Grupo->getMiembros()) + 1;
            $Grupo->setCantidadMiembros($aux);
        }else{
            $this->addFlash('error', 'Seleccione la sección que desea modificar');
            return $this->redirectToRoute('planillas_show', array('id' => $id_planilla));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $Grupo->addMiembro($persona);
            $aux = sizeof($Grupo->getMiembros()) + 1;
            $Grupo->setCantidadMiembros($aux);
            $persona->setGrupofamiliar($Grupo);

            if ($persona->getEmail() != '') {
                $persona->setRecibirCorreo(true);
            }else{
                $persona->setRecibirCorreo(false);
            }
            $em->persist($persona);
            $em->persist($Grupo);
            $bitacora = new Bitacora($this->getUser(),'agregó','a '.$persona->nombreyapellido().' al Grupo Familiar '.$Grupo->getApellidos());
            $em->persist($bitacora);
            $em->flush();

            $cantMiembros = sizeof($Grupo->getMiembros()) + 1;
            $this->addFlash('success', 'Se agregó a '.$persona->nombreyapellido().' al grupo familiar de forma correcta.');

            return $this->redirectToRoute('planillas_show', array('id' => $id_planilla));
        }

        $cantMiembros = sizeof($Grupo->getMiembros()) + 1;

        return $this->render('persona/nuevomiembro.html.twig', array(
            'persona' => $persona,
            'id_planilla' => $id_planilla,
            'id_grupofamiliar' => $id_grupofamiliar,
            'grupoFamiliarApellidos' => $Grupo->getApellidos(),
            'parentescos' => $this->obtener_parentescos(),
            'cantMiembros' => $cantMiembros,
            'form' => $form->createView(),
        ));
    }

    /*Obtiene todos los parentescos que se han agregado a la base de datos*/
    public function obtener_parentescos()
    {
        $em = $this->getDoctrine()->getManager();
        $parentescos = $em->getRepository('SICBundle:Persona')->findParentescos();
        return $parentescos;
    }
}

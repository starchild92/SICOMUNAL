<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

use Slik\DompdfBundle\DomPDF;

use SICBundle\Entity\Planillas;
use SICBundle\Entity\AdminVentaVivienda;
use SICBundle\Entity\AdminTipoHabitacionesVivienda;
use SICBundle\Entity\AdminRecoleccionBasura;
use SICBundle\Entity\AdminServiciosComunales;
use SICBundle\Entity\AdminMecanismoInformacion;
use SICBundle\Entity\AdminOrgComunitaria;
use SICBundle\Entity\AdminTipoTransporte;
use SICBundle\Entity\AdminTipoTelefonia;
use SICBundle\Entity\AdminTipoPadecencia;
use SICBundle\Entity\AdminMisionesComunidad;
use SICBundle\Entity\AdminTipoAyudaEspecial;
use SICBundle\Entity\AdminTipoMascotas;
use SICBundle\Entity\AdminTipoPlagas;
use SICBundle\Entity\AdminTipoEnseres;
use SICBundle\Entity\AdminProfesion;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\PlanillasType;

/**
 * Planillas controller.
 *
 */
class PlanillasController extends BaseController
{
    /**
     * Lists all Planillas entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $planillas = $em->getRepository('SICBundle:Planillas')->findAll();

        return $this->render('planillas/index.html.twig', array(
            'planillas' => $planillas,
        ));
    }

    /**
     * Creates a new Planillas entity.
     *
     */
    public function newAction(Request $request)
    {
        $planilla = new Planillas();
        $form = $this->createForm('SICBundle\Form\PlanillasType', $planilla);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //Colocando el empadronador
            $planilla->setEmpadronador($this->getUser());
            $planilla->setTerminada(0);
            $planilla->setFecha(new \DateTime('now'));

            $em->persist($planilla);
            $em->flush();

            // redirigir a (Datos Personales del Jefe Grupo Familiar)
            return $this->redirectToRoute('jefegrupofamiliar_new', array('id_planilla' => $planilla->getId()));
        }

        return $this->render('planillas/new.html.twig', array(
            'planilla' => $planilla,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Planillas entity.
     *
     */
    public function showAction(Planillas $planilla)
    {
        $deleteForm = $this->createDeleteForm($planilla);

        return $this->render('planillas/show.html.twig', array(
            'planilla' => $planilla,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Planillas entity.
     *
     */
    public function editAction(Request $request, Planillas $planilla)
    {
        $deleteForm = $this->createDeleteForm($planilla);
        $editForm = $this->createForm('SICBundle\Form\PlanillasType', $planilla);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($planilla);
            $em->flush();

            return $this->redirectToRoute('planillas_edit', array('id' => $planilla->getId()));
        }

        return $this->render('planillas/edit.html.twig', array(
            'planilla' => $planilla,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Planillas entity.
     *
     */
    public function deleteAction(Request $request, Planillas $planilla)
    {
        $form = $this->createDeleteForm($planilla);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($planilla);
            $bitacora = new Bitacora($this->getUser(),'eliminó','Una planilla.');
            $em->persist($bitacora);
            $this->addFlash('success', 'Se eliminó la planilla de forma exitosa.');

            $em->flush();
        }

        return $this->redirectToRoute('planillas_index');
    }

    /**
     * Creates a form to delete a Planillas entity.
     *
     * @param Planillas $planilla The Planillas entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Planillas $planilla)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('planillas_delete', array('id' => $planilla->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function cargarObservacionAction(Request $request, $id_planilla)
    {
        $em = $this->getDoctrine()->getManager();
        $planilla = $em->getRepository('SICBundle:Planillas')->findById($id_planilla);

        $data = $request->get('observaciones');
        $planilla[0]->setObservaciones($data);
        $em->persist($planilla[0]);
        $bitacora = new Bitacora($this->getUser(),'modificó','Las observaciones de la planilla '.$id_planilla);
        $em->persist($bitacora);
        $em->flush();
        $this->addFlash('success', 'Se ha registrado la observación');
    }

    public function conitnuarEncuestaAction($id_encuesta = null)
    {
        $em = $this->getDoctrine()->getManager();
        $planilla = $em->getRepository('SICBundle:Planillas')->findById($id_encuesta);

        if (sizeof($planilla) == 1) {
            $planilla = $planilla[0];

            $bitacora = new Bitacora($this->getUser(),'continuó','con el llenado de la encuesta '.$id_encuesta);
            $em->persist($bitacora);
            $em->flush();
            
            $aux = $planilla->getJefeGrupoFamiliar();
            if ($aux != NULL) {
                $aux = $planilla->getGrupoFamiliar();
                if ($aux != NULL) {
                    $miembros = $aux->getCantidadMiembros();
                    $id = $aux->getId();

                    $aux = $planilla->getSituacionEconomica();
                    // Si la cantidad de miembros es 0, agregar nuevos miembros
                    if($miembros <= 1 && $aux == null){
                        return $this->redirectToRoute('personas_new', array(
                        'id_planilla' => $planilla->getId(),
                        'id_grupofamiliar' => $id));
                    }

                    if ($aux != NULL) {
                        $aux = $planilla->getSituacionVivienda();
                        if ($aux != NULL) {
                            $aux = $planilla->getSituacionSalud();
                            if ($aux != NULL) {
                                $aux = $planilla->getSituacionServicios();
                                if ($aux != NULL) {
                                    $aux = $planilla->getParticipacionComunitaria();
                                    if ($aux != NULL) {
                                        $aux = $planilla->getSituacionComunidad();
                                        if ($aux != NULL) {
                                            $this->addFlash('success', 'La planilla ya está completa');
                                            return $this->redirectToRoute('planillas_index');
                                        }else{
                                            return $this->redirectToRoute('situacioncomunidad_new', array('id_planilla' => $planilla->getId()));
                                        }
                                    }else{
                                        return $this->redirectToRoute('participacioncomunitaria_new', array('id_planilla' => $planilla->getId()));
                                    }
                                }else{
                                    return $this->redirectToRoute('situacionservicios_new', array('id_planilla' => $planilla->getId()));
                                }
                            }else{
                                return $this->redirectToRoute('situacionsalud_new', array('id_planilla' => $planilla->getId()));
                            }
                        }else{
                            return $this->redirectToRoute('situacionvivienda_new', array('id_planilla' => $planilla->getId()));
                        }
                    }else{
                        return $this->redirectToRoute('situacioneconomica_new', array('id_planilla' => $planilla->getId()));
                    }
                }else{
                    return $this->redirectToRoute('grupofamiliar_new', array('id_planilla' => $planilla->getId()));
                }
            }else{
                return $this->redirectToRoute('jefegrupofamiliar_new', array('id_planilla' => $planilla->getId()));
            }
        }

        $this->addFlash('danger', 'La planilla que indicó no existe.');
        return $this->redirectToRoute('planillas_index');
    }

    public function new_element_ajaxAction(Request $request)
    {
        $form = $request->request->all();
        $response = new Response(json_encode(null));
        $em = $this->getDoctrine()->getManager();

        if ($form['tipo'] == "jgf_profesion") {
            $resultado = $em->getRepository('SICBundle:AdminProfesion')->findBy(array('nombre' => $form['nombre']));
            if (sizeof($resultado) == 0) {
                $nuevo = new AdminProfesion();
                $nuevo->setNombre($form['nombre']);
                $em->persist($nuevo);
                $em->flush();

                $response = new Response(json_encode([
                'codigo' => '500',
                'nombre' => $form['nombre'],
                'respuesta' => 'Se ha añadido la profesión "'.$form['nombre'].'" a la base de datos',
                'id'        => $nuevo->getId(),
                ]));
            }
        }elseif ($form['tipo'] == "actividad_vivienda") {
            $resultado = $em->getRepository('SICBundle:AdminVentaVivienda')->findBy(array('nombre' => $form['nombre']));
            if (sizeof($resultado) == 0) {
                $nuevo = new AdminVentaVivienda();
                $nuevo->setNombre($form['nombre']);
                $em->persist($nuevo);
                $em->flush();

                $response = new Response(json_encode([
                'codigo' => '500',
                'nombre' => $form['nombre'],
                'respuesta' => 'Se ha añadido "'.$form['nombre'].'" a la base de datos',
                'id'        => $nuevo->getId(),
                ]));
            }
        }elseif ($form['tipo'] == "situacion_vivienda_habitaciones") {
            $resultado = $em->getRepository('SICBundle:AdminTipoHabitacionesVivienda')->findBy(array('nombre' => $form['nombre']));
            if (sizeof($resultado) == 0) {
                $nuevo = new AdminTipoHabitacionesVivienda();
                $nuevo->setNombre($form['nombre']);
                $em->persist($nuevo);
                $em->flush();

                $response = new Response(json_encode([
                'codigo' => '500',
                'nombre' => $form['nombre'],
                'respuesta' => 'Se ha añadido un nuevo tipo de habitación de vivienda, llamado "'.$form['nombre'].'" a la base de datos',
                'id'        => $nuevo->getId(),
                ]));
            }
        }elseif ($form['tipo'] == "situacion_vivienda_enseres") {
            $resultado = $em->getRepository('SICBundle:AdminTipoEnseres')->findBy(array('nombre' => $form['nombre']));
            if (sizeof($resultado) == 0) {
                $nuevo = new AdminTipoEnseres();
                $nuevo->setNombre($form['nombre']);
                $em->persist($nuevo);
                $em->flush();

                $response = new Response(json_encode([
                'codigo' => '500',
                'nombre' => $form['nombre'],
                'respuesta' => 'Se ha añadido un nuevo enser, llamado "'.$form['nombre'].'" a la base de datos',
                'id'        => $nuevo->getId(),
                ]));
            }
        }elseif ($form['tipo'] == "situacion_vivienda_presenciaInsectos") {
            $resultado = $em->getRepository('SICBundle:AdminTipoPlagas')->findBy(array('nombre' => $form['nombre']));
            if (sizeof($resultado) == 0) {
                $nuevo = new AdminTipoPlagas();
                $nuevo->setNombre($form['nombre']);
                $em->persist($nuevo);
                $em->flush();

                $response = new Response(json_encode([
                'codigo' => '500',
                'nombre' => $form['nombre'],
                'respuesta' => 'Se ha añadido un tipo de plaga nueva, llamado "'.$form['nombre'].'" a la base de datos',
                'id'        => $nuevo->getId(),
                ]));
            }
        }elseif ($form['tipo'] == "situacion_vivienda_mascota") {
            $resultado = $em->getRepository('SICBundle:AdminTipoMascotas')->findBy(array('nombre' => $form['nombre']));
            if (sizeof($resultado) == 0) {
                $nuevo = new AdminTipoMascotas();
                $nuevo->setNombre($form['nombre']);
                $em->persist($nuevo);
                $em->flush();

                $response = new Response(json_encode([
                'codigo' => '500',
                'nombre' => $form['nombre'],
                'respuesta' => 'Se ha añadido un tipo de mascota nueva, llamado "'.$form['nombre'].'" a la base de datos',
                'id'        => $nuevo->getId(),
                ]));
            }
        }elseif ($form['tipo'] == "situacion_salud_padecencia") {
            $resultado = $em->getRepository('SICBundle:AdminTipoPadecencia')->findBy(array('nombre' => $form['nombre']));
            if (sizeof($resultado) == 0) {
                $nuevo = new AdminTipoPadecencia();
                $nuevo->setNombre($form['nombre']);
                $em->persist($nuevo);
                $em->flush();

                $response = new Response(json_encode([
                'codigo' => '500',
                'nombre' => $form['nombre'],
                'respuesta' => 'Se ha añadido un tipo de padecencia nueva, llamado "'.$form['nombre'].'" a la base de datos',
                'id'        => $nuevo->getId(),
                ]));
            }
        }elseif ($form['tipo'] == "situacion_servicios_transporte") {
            $resultado = $em->getRepository('SICBundle:AdminTipoTransporte')->findBy(array('nombre' => $form['nombre']));
            if (sizeof($resultado) == 0) {
                $nuevo = new AdminTipoTransporte();
                $nuevo->setNombre($form['nombre']);
                $em->persist($nuevo);
                $em->flush();

                $response = new Response(json_encode([
                'codigo' => '500',
                'nombre' => $form['nombre'],
                'respuesta' => 'Se ha añadido un tipo de transporte, llamado "'.$form['nombre'].'" a la base de datos',
                'id'        => $nuevo->getId(),
                ]));
            }
        }elseif ($form['tipo'] == "situacion_servicios_mecanismoInformacion") {
            $resultado = $em->getRepository('SICBundle:AdminMecanismoInformacion')->findBy(array('nombre' => $form['nombre']));
            if (sizeof($resultado) == 0) {
                $nuevo = new AdminMecanismoInformacion();
                $nuevo->setNombre($form['nombre']);
                $em->persist($nuevo);
                $em->flush();

                $response = new Response(json_encode([
                'codigo' => '500',
                'nombre' => $form['nombre'],
                'respuesta' => 'Se ha añadido un mecanismo de información, llamado "'.$form['nombre'].'" a la base de datos',
                'id'        => $nuevo->getId(),
                ]));
            }
        }elseif ($form['tipo'] == "situacion_servicios_serviciosComunales") {
            $resultado = $em->getRepository('SICBundle:AdminServiciosComunales')->findBy(array('nombre' => $form['nombre']));
            if (sizeof($resultado) == 0) {
                $nuevo = new AdminServiciosComunales();
                $nuevo->setNombre($form['nombre']);
                $em->persist($nuevo);
                $em->flush();

                $response = new Response(json_encode([
                'codigo' => '500',
                'nombre' => $form['nombre'],
                'respuesta' => 'Se ha añadido un nuevo servicio comunal, llamado "'.$form['nombre'].'" a la base de datos',
                'id'        => $nuevo->getId(),
                ]));
            }
        }elseif ($form['tipo'] == "situacion_servicios_recoleccionBasura") {
            $resultado = $em->getRepository('SICBundle:AdminRecoleccionBasura')->findBy(array('nombre' => $form['nombre']));
            if (sizeof($resultado) == 0) {
                $nuevo = new AdminRecoleccionBasura();
                $nuevo->setNombre($form['nombre']);
                $em->persist($nuevo);
                $em->flush();

                $response = new Response(json_encode([
                'codigo' => '500',
                'nombre' => $form['nombre'],
                'respuesta' => 'Se ha añadido un nuevo sistema de recolección de basura, llamado "'.$form['nombre'].'" a la base de datos',
                'id'        => $nuevo->getId(),
                ]));
            }
        }elseif ($form['tipo'] == "situacion_servicios_telefonia") {
            $resultado = $em->getRepository('SICBundle:AdminTipoTelefonia')->findBy(array('nombre' => $form['nombre']));
            if (sizeof($resultado) == 0) {
                $nuevo = new AdminTipoTelefonia();
                $nuevo->setNombre($form['nombre']);
                $em->persist($nuevo);
                $em->flush();

                $response = new Response(json_encode([
                'codigo' => '500',
                'nombre' => $form['nombre'],
                'respuesta' => 'Se ha añadido una nuevo Tipo de Telefonía, llamada "'.$form['nombre'].'" a la base de datos',
                'id'        => $nuevo->getId(),
                ]));
            }
        }elseif ($form['tipo'] == "situacion_salud_ayudaEspecial") {
            $resultado = $em->getRepository('SICBundle:AdminTipoAyudaEspecial')->findBy(array('nombre' => $form['nombre']));
            if (sizeof($resultado) == 0) {
                $nuevo = new AdminTipoAyudaEspecial();
                $nuevo->setNombre($form['nombre']);
                $em->persist($nuevo);
                $em->flush();

                $response = new Response(json_encode([
                'codigo' => '500',
                'nombre' => $form['nombre'],
                'respuesta' => 'Se ha añadido un nuevo tipo de ayuda especial, llamado "'.$form['nombre'].'" a la base de datos',
                'id'        => $nuevo->getId(),
                ]));
            }
        }elseif ($form['tipo'] == "participacion_comunitaria_existenOrganizaciones") {
            $resultado = $em->getRepository('SICBundle:AdminOrgComunitaria')->findBy(array('nombre' => $form['nombre']));
            if (sizeof($resultado) == 0) {
                $nuevo = new AdminOrgComunitaria();
                $nuevo->setNombre($form['nombre']);
                $em->persist($nuevo);
                $em->flush();

                $response = new Response(json_encode([
                'codigo' => '500',
                'nombre' => $form['nombre'],
                'respuesta' => 'Se ha añadido una nueva Organización Comunitaria, llamado "'.$form['nombre'].'" a la base de datos',
                'id'        => $nuevo->getId(),
                ]));
            }
        }elseif ($form['tipo'] == "participacion_comunitaria_misionesComunidad") {
            $resultado = $em->getRepository('SICBundle:AdminMisionesComunidad')->findBy(array('nombre' => $form['nombre']));
            if (sizeof($resultado) == 0) {
                $nuevo = new AdminMisionesComunidad();
                $nuevo->setNombre($form['nombre']);
                $em->persist($nuevo);
                $em->flush();

                $response = new Response(json_encode([
                'codigo' => '500',
                'nombre' => $form['nombre'],
                'respuesta' => 'Se ha añadido una nueva Misión a la Comunidad, llamada "'.$form['nombre'].'" a la base de datos',
                'id'        => $nuevo->getId(),
                ]));
            }
        }

        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /* Es para imprimir y cuando no puede por 100 devuelve una vista */
    public function imprimirAction(Planillas $planilla)
    {
        if ($planilla->getTerminada() != 100) {
            $this->addFlash('danger', 'No puedes imprimir una planilla que no este completada.');
            return $this->render('estadisticas/imprimir-error.html.twig', array(
                'mensaje' => 'Este estudio no está terminado por lo cual no lo puede imprimir, completelo y regrese.'));
        }else{
            if( $this->isGranted('IS_AUTHENTICATED_FULLY') )
            {
                $em = $this->getDoctrine()->getManager();
                $comunidad = $em->getRepository('SICBundle:Comunidad')->findAll();
                $consejo = $em->getRepository('SICBundle:ConsejoComunal')->findAll();
                $comunidad_info = $comunidad[0];
                $cc = $consejo[0];

                $dompdf = new \DOMPDF();
                $dompdf->set_paper(array(0,0,612.00,1008.00), 'landscape');
                $dompdf->load_html($this->renderView('pdfs/planilla-pdf.html.twig',
                    array(
                        'planilla' => $planilla,
                        'consejo' => $cc,
                        'comunidad' => $comunidad_info)
                ));
                $dompdf->render();

                $entrada = new Bitacora($this->getUser(),'generó','un PDF de la planilla '.$planilla->getId());
                $em->persist($entrada);
                $em->flush();

                // Or get the output to handle it yourself
                $response = new Response();
                $response->setContent($dompdf->stream("planilla".$planilla->getId().".pdf", array("Attachment"=>0)));
                $response->setStatusCode(200);
                $response->headers->set('Content-Type', 'application/pdf');
                return $response;
            }
        }
            
    }

    public function obtenerTodasAction()
    {
        set_time_limit(0);
        if( $this->isGranted('IS_AUTHENTICATED_FULLY') )
            {
                $em = $this->getDoctrine()->getManager();
                $planillas = $em->getRepository('SICBundle:Planillas')->findAll();
                $comunidad = $em->getRepository('SICBundle:Comunidad')->findAll();
                $consejo = $em->getRepository('SICBundle:ConsejoComunal')->findAll();
                $comunidad_info = $comunidad[0];
                $cc = $consejo[0];

                $dompdf = new \DOMPDF();
                $dompdf->set_paper(array(0,0,612.00,1008.00), 'landscape');
                $dompdf->load_html($this->renderView('pdfs/planilla-pdf-todas.html.twig',
                    array(
                        'planillas' => $planillas,
                        'consejo' => $cc,
                        'comunidad' => $comunidad_info)
                ));
                $dompdf->render();

                $entrada = new Bitacora($this->getUser(),'generó','un PDF de TODAS las planillas terminadas en el sistema.');
                $em->persist($entrada);
                $em->flush();

                // Or get the output to handle it yourself
                $response = new Response();
                $response->setContent($dompdf->stream("todas_las_planillas.pdf", array("Attachment"=>0)));
                $response->setStatusCode(200);
                $response->headers->set('Content-Type', 'application/pdf');
                return $response;
            }
    }
}

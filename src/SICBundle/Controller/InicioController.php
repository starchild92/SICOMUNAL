<?php

namespace SICBundle\Controller;

use SICBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use SICBundle\Entity\Bitacora;

class InicioController extends BaseController
{
    public function inicioAction(){
        if( $this->isGranted('IS_AUTHENTICATED_FULLY') )
        {
            $em = $this->getDoctrine()->getManager();
            $cant_usuarios = sizeof($em->getRepository(\SICBundle\Entity\Usuario::class)->findAll());
            $cant_jgf = sizeof($em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findAll());
            $cant_planillas = sizeof($em->getRepository(\SICBundle\Entity\Planillas::class)->findAll());
            $cant_personas = sizeof($em->getRepository(\SICBundle\Entity\Persona::class)->findAll());

            return $this->render('inicio/inicio.html.twig', array(
                'cant_usuarios' => $cant_usuarios,
                'cant_jgf' => $cant_jgf,
                'cant_personas' => $cant_personas,
                'cant_planillas' => $cant_planillas,
                ));
        }else{
            return $this->redirectToRoute('sic_homepage_login');
        }
    }

    public function loginAction(AuthenticationUtils $authenticationUtils){
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('Security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => '',
        ]);
    }

    public function cmpVotos($a, $b){
        if ($a['votos'] == $b['votos']) { return 0; }
        return ($a['votos'] > $b['votos']) ? -1 : 1;
    }
    public function administrarEntidadesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $comunidad = $em->getRepository(\SICBundle\Entity\Comunidad::class)->findAll();
        $cc = $em->getRepository(\SICBundle\Entity\ConsejoComunal::class)->findAll();

        $unidades_eje = array();
        $unidad_ejecutiva = $em->getRepository(\SICBundle\Entity\Comite::class)->findBy(array('tipoUnidad' => 'Unidad Ejecutiva'));
        foreach ($unidad_ejecutiva as $ue) {
            $personas = array();
            $voceros = $ue->getVoceros();

            foreach ($voceros as $v) {
                $r = $em->getRepository(\SICBundle\Entity\Persona::class)->findBy(array('cedula' => $v->getPersona()));
                if (sizeof($r)>0) {
                    $voce = $r[0];
                }else{
                    $r = $em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(array('cedula' => $v->getPersona()));
                    if (sizeof($r)>0) {
                        $voce = $r[0];
                    }else{
                        $em->remove($v);
                        // print_r($v->getPersona());
                        // echo "La cédula no se encotró para el JefeGrupoFamiliar, despues de buscar en Personas";
                        // die();
                    }
                }

                array_push($personas, array(
                    "tipo" => $v->getTipo(),
                    "votos" => $v->getVotosElecto(),
                    "vocero" => $voce,
                ));
                usort($personas, array($this, "cmpVotos"));
            }

            array_push($unidades_eje, array(
                "tipoUnidad" => $ue->getTipoUnidad(),
                "id" => $ue->getId(),
                "nombre" => $ue->getNombre(),
                "voceros" => $personas,
            ));
        }

        $em->flush();

        $unidades_restantes = array();
        $unid_res = $em->getRepository(\SICBundle\Entity\Comite::class)->findAll();
        foreach ($unid_res as $ue) {
            if ($ue->getTipoUnidad() != 'Unidad Ejecutiva') {            
                $personas = array();
                $voceros = $ue->getVoceros();
                foreach ($voceros as $v) {
                    $cedula = filter_var($v->getPersona(), FILTER_SANITIZE_NUMBER_INT);
                    $r = $em->getRepository(\SICBundle\Entity\Persona::class)->findBy(array('cedula' => $cedula));
                    if (sizeof($r)>0) {
                        $voce = $r[0];
                    }else{
                        $r = $em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(array('cedula' => $cedula));
                        if (sizeof($r)>0) {
                            $voce = $r[0];
                        }else{
                            $em->remove($v);
                        }
                    }

                    array_push($personas, array(
                        "tipo" => $v->getTipo(),
                        "votos" => $v->getVotosElecto(),
                        "vocero" => $voce,
                    ));
                }

                array_push($unidades_restantes, array(
                    "id" => $ue->getId(),
                    "tipoUnidad" => $ue->getTipoUnidad(),
                    "voceros" => $personas,
                ));
                usort($personas, array($this, "cmpVotos"));
            }
        }
        
        $em->flush();

        // Solo una sola instancia de esta entidad
        if (count($comunidad) > 0) { $comunidad = $comunidad[0]; }
        if (count($cc) > 0) { $cc = $cc[0]; }

        return $this->render('administracion/administrar_entidades.html.twig', 
            array(
                'comunidad' =>  $comunidad,
                'consejo'   =>  $cc,
                'unidades_eje'   =>  $unidades_eje,
                'unidades_restantes'   =>  $unidades_restantes,
            ));
    }

    public function administrarParametrosAction()
    {
        return $this->render('administracion/parametros.html.twig');
    }

    public function volverParametrosAction($index)
    {
        return $this->render('administracion/parametros.html.twig');
    }

    // Reportes Solicitados
    public function cmp($a, $b){
        if((int)$a->getCedula() == (int)$b->getCedula())return 0;
        if((int)$a->getCedula()  > (int)$b->getCedula())return 1;
        if((int)$a->getCedula()  < (int)$b->getCedula())return -1;
    }
    public function cuadernoVotacionAction()
    {
        $em = $this->getDoctrine()->getManager();
        $comunidad = $em->getRepository(\SICBundle\Entity\Comunidad::class)->findAll();
        $consejo = $em->getRepository(\SICBundle\Entity\ConsejoComunal::class)->findAll();

        if (sizeof($comunidad) > 0) {
            $comunidad_info = $comunidad[0];
            $cc = $consejo[0];
            
            $jefes_grupo_familiar = $em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->mayores_de(15);
            $personas = $em->getRepository(\SICBundle\Entity\Persona::class)->mayores_de(15);
            $votantes = array();
            foreach ($jefes_grupo_familiar as $j) { array_push($votantes, $j); }
            foreach ($personas as $p) { array_push($votantes, $p); }
            usort($votantes, array($this, "cmp"));

            return $this->render('inicio/cuaderno-votacion.html.twig',
                array(
                    'consejo' => $cc,
                    'votantes' => $votantes,
                    'comunidad' => $comunidad_info));
        }else{
            $this->addFlash('danger', 'No se puede generar el Cuaderno de Votación hasta tanto no haya agregado los datos de la Comunidad');
            return $this->redirectToRoute('sic_homepage');
        }
    }
    public function cuadernoVotacionPDFAction()
    {
        $em = $this->getDoctrine()->getManager();
        $comunidad = $em->getRepository(\SICBundle\Entity\Comunidad::class)->findAll();
        $consejo = $em->getRepository(\SICBundle\Entity\ConsejoComunal::class)->findAll();

        if (sizeof($comunidad) > 0) {
            $comunidad_info = $comunidad[0];
            $cc = $consejo[0];

            $jefes_grupo_familiar = $em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->mayores_de(15);
            $personas = $em->getRepository(\SICBundle\Entity\Persona::class)->mayores_de(15);
            $votantes = array();
            foreach ($jefes_grupo_familiar as $j) { array_push($votantes, $j); }
            foreach ($personas as $p) { array_push($votantes, $p); }
            usort($votantes, array($this, "cmp"));

            // Genero el PDF Aqui
            $dompdf = new \DOMPDF();
            $dompdf->set_paper(array(0,0,612.00,792.00), 'landscape');
            $dompdf->load_html($this->renderView('pdfs/cuaderno-votacion-pdf.html.twig',
                array(
                    'consejo' => $cc,
                    'votantes' => $votantes,
                    'comunidad' => $comunidad_info))
            );
            $dompdf->render();

            $entrada = new Bitacora($this->getUser(),'generó','un Cuaderno de Votación');
            $em->persist($entrada);
            $em->flush();

            // Or get the output to handle it yourself
            $response = new Response();
            $response->setContent($dompdf->stream("cuaderno-votacion.pdf", array("Attachment"=>0)));
            $response->setStatusCode(200);
            $response->headers->set('Content-Type', 'application/pdf');
            return $response;
        }else{
            $this->addFlash('danger', 'No se puede generar el Cuaderno de Votación hasta tanto no haya agregado los datos de la Comunidad');
            return $this->redirectToRoute('sic_homepage');
        }
    }

    // public function cmpsector($a, $b){ return strcmp($a->getAvenida(), $b->getAvenida()); }
    /* Estas funciones son identicas, difieren en el response, si cambias las contultas de una, debes cambiar las consultas de la otra. Obviamente el response se mantiene. */
    public function cmpdireccion($a, $b){ return strcmp($a->getAvenidaCalle(), $b->getAvenidaCalle()); }
    public function resumenCensoAction()
    {
        $em = $this->getDoctrine()->getManager();
        $comunidad = $em->getRepository(\SICBundle\Entity\Comunidad::class)->findAll();
        $consejo = $em->getRepository(\SICBundle\Entity\ConsejoComunal::class)->findAll();
        if (sizeof($comunidad) > 0 && sizeof($consejo)) {
            $comunidad_info = $comunidad[0];
            $cc = $consejo[0];

            $sectores = $em->getRepository(\SICBundle\Entity\GrupoFamiliar::class)->findSectores(); //Cantidad de Sectores para realizar la contabilización de los datos
            // usort($sectores, array($this, "cmpsector")); //esto los organizará, lo que no tiene sentido porque todos son diferentes

            $datos =  array();
            foreach ($sectores as $s) {
                $nombre_avenida = $s->getAvenida();
                $grupos_del_sector = $em->getRepository(\SICBundle\Entity\GrupoFamiliar::class)->findBy(array('avenida' => $nombre_avenida));
                usort($grupos_del_sector, array($this, "cmpdireccion"));
                $num_viviendas = sizeof($em->getRepository(\SICBundle\Entity\GrupoFamiliar::class)->findNumeroViviendas($nombre_avenida));
                $habitantes_sector = $em->getRepository(\SICBundle\Entity\GrupoFamiliar::class)->findCantidadMiembros($nombre_avenida);

                //Variables para construir la tabla del resultados
                // echo "En el sector: ".$nombre_avenida;
                // echo ", hay ".sizeof($grupos_del_sector). " grupos familiares con ";
                // echo $num_viviendas." viviendas";
                // echo " y en este sector viven ".$habitantes_sector['cantidad']." personas <br>";

                // Me toca anidar un foreach para consultar el resto de la informacion de cada miemnro del grupo familiar y jefe de grupo familiar
                // echo sizeof($grupos_del_sector);
                $miembros = 0;
                $entreQyD = 0;
                $menores = 0;
                $mayor_edad = 0;
                $cne = 0;
                $electores = 0;
                foreach ($grupos_del_sector as $grupo) {
                    // echo $grupo->getAvenida()."/".$grupo->getAvenidaCalle()." miembros ".sizeof($grupo->getMiembros())."<br>";
                    $miembros = sizeof($grupo->getMiembros()) + $miembros + 1;
                    $personas = $grupo->getMiembros();
                    foreach ($personas as $p) {
                        if ($p->getEdad() < 15) { $menores++; }

                        if ($p->getEdad() >= 15 && $p->getEdad() <= 17) { $entreQyD++; }
                        if ($p->getEdad() >= 15) { $electores++; }
                        if ($p->getEdad() >= 18) { $mayor_edad++; }
                        if ($p->getCne()->getRespuesta() == 'Si') { $cne++; }
                    }
                    if ($grupo->getPlanilla() != null) {
                        $jfg = $grupo->getPlanilla()->getJefeGrupoFamiliar();
                        if ($jfg != null) {
                            if ($jfg->getEdad() < 15) { $menores++; }
                            if ($jfg->getEdad() >= 15 && $jfg->getEdad() <= 17) { $entreQyD++; }
                            if ($jfg->getEdad() >= 15) { $electores++; }
                            if ($jfg->getEdad() >= 18) { $mayor_edad++; }
                            if ($jfg->getCne()->getRespuesta() == 'Si') { $cne++; }
                        }
                    }
                }
                // echo "Total personas: ".$miembros."<br>";
                // echo "15 - 17 años: ".$entreQyD."<br>";
                // echo "18 >= ".$mayor_edad."<br>";
                // echo "CNE: ".$cne."<br>";
                // echo "Electores:  ".$electores."<br>";

                array_push($datos,
                    array(
                        'sector' => $nombre_avenida,
                        'num_viviendas' => $num_viviendas,
                        'num_familias' => sizeof($grupos_del_sector),
                        'num_habitantes' => $habitantes_sector['cantidad'],
                        'mayoresde' => $entreQyD,
                        'menores' => $menores,
                        'mayor_edad' => $mayor_edad,
                        'cne' => $cne,
                        'electores' => $electores));
            }

            return $this->render('inicio/resumen-censo.html.twig',
                array(
                    'sectores' => $datos,
                    'comunidad' => $comunidad_info,
                    'consejo' => $cc->getNombre()));
        }else{
            $this->addFlash('danger', 'No se puede generar el Resumen del Censo Demográfico hasta tanto no haya agregado los datos de la Comunidad y/o del Consejo Comunal.');
            return $this->redirectToRoute('sic_homepage');
        }
    }
    public function resumenCensoPDFAction()
    {
        $em = $this->getDoctrine()->getManager();
        $comunidad = $em->getRepository(\SICBundle\Entity\Comunidad::class)->findAll();
        $consejo = $em->getRepository(\SICBundle\Entity\ConsejoComunal::class)->findAll();
        if (sizeof($comunidad) > 0 && sizeof($consejo)) {
            $comunidad_info = $comunidad[0];
            $cc = $consejo[0];
            $sectores = $em->getRepository(\SICBundle\Entity\GrupoFamiliar::class)->findSectores();
            $datos =  array();
            foreach ($sectores as $s) {
                $nombre_avenida = $s->getAvenida();
                $grupos_del_sector = $em->getRepository(\SICBundle\Entity\GrupoFamiliar::class)->findBy(array('avenida' => $nombre_avenida));
                usort($grupos_del_sector, array($this, "cmpdireccion"));
                $num_viviendas = sizeof($em->getRepository(\SICBundle\Entity\GrupoFamiliar::class)->findNumeroViviendas($nombre_avenida));
                $habitantes_sector = $em->getRepository(\SICBundle\Entity\GrupoFamiliar::class)->findCantidadMiembros($nombre_avenida);
                $miembros = 0;
                $entreQyD = 0;
                $menores = 0;
                $mayor_edad = 0;
                $cne = 0;
                $electores = 0;
                foreach ($grupos_del_sector as $grupo) {
                    $miembros = sizeof($grupo->getMiembros()) + $miembros + 1;
                    $personas = $grupo->getMiembros();
                    foreach ($personas as $p) {
                        if ($p->getEdad() < 15) { $menores++; }
                        if ($p->getEdad() >= 15 && $p->getEdad() <= 17) { $entreQyD++; }
                        if ($p->getEdad() >= 15) { $electores++; }
                        if ($p->getEdad() >= 18) { $mayor_edad++; }
                        if ($p->getCne()->getRespuesta() == 'Si') { $cne++; }
                    }
                    if ($grupo->getPlanilla() != null) {
                        $jfg = $grupo->getPlanilla()->getJefeGrupoFamiliar();
                        if ($jfg != null) {
                            if ($jfg->getEdad() < 15) { $menores++; }
                            if ($jfg->getEdad() >= 15 && $jfg->getEdad() <= 17) { $entreQyD++; }
                            if ($jfg->getEdad() >= 15) { $electores++; }
                            if ($jfg->getEdad() >= 18) { $mayor_edad++; }
                            if ($jfg->getCne()->getRespuesta() == 'Si') { $cne++; }
                        }
                    }
                }
                array_push($datos,array(
                    'sector' => $nombre_avenida,
                    'num_viviendas' => $num_viviendas,
                    'num_familias' => sizeof($grupos_del_sector),
                    'num_habitantes' => $habitantes_sector['cantidad'],
                    'mayoresde' => $entreQyD,
                    'menores' => $menores,
                    'mayor_edad' => $mayor_edad,
                    'cne' => $cne,
                    'electores' => $electores));
            }

            $dompdf = new \DOMPDF();
            $dompdf->set_paper(array(0,0,612.00,792.00), 'landscape');
            $dompdf->load_html($this->renderView('pdfs/resumen-censo-pdf.html.twig',
                array(
                    'sectores' => $datos,
                    'comunidad' => $comunidad_info,
                    'base_path' => $_SERVER["DOCUMENT_ROOT"],
                    'consejo' => $cc->getNombre()))
            );
            $dompdf->render();
            $entrada = new Bitacora($this->getUser(),'generó','un Resumen del Censo Demográfico');
            $em->persist($entrada);
            $em->flush();

            $response = new Response();
            $response->setContent($dompdf->stream("resumen-censo-demografico.pdf", array("Attachment"=>0)));
            $response->setStatusCode(200);
            $response->headers->set('Content-Type', 'application/pdf');
            return $response;
        }else{
            $this->addFlash('danger', 'No se puede generar el Resumen del Censo Demográfico hasta tanto no haya agregado los datos de la Comunidad y/o del Consejo Comunal.');
            return $this->redirectToRoute('sic_homepage');
        }
    }

    /* Estas funciones son identicas, difieren en el response, si cambias las contultas de una, debes cambiar las consultas de la otra. Obviamente el response se mantiene. */
    public function registroElectoralAction()
    {
        $em = $this->getDoctrine()->getManager();
        $comunidad = $em->getRepository(\SICBundle\Entity\Comunidad::class)->findAll();
        $consejo = $em->getRepository(\SICBundle\Entity\ConsejoComunal::class)->findAll();
        if (sizeof($comunidad) > 0) {
            $comunidad_info = $comunidad[0];
            $cc = $consejo[0];

            $jefes_grupo_familiar = $em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->mayores_de(15);
            $personas = $em->getRepository(\SICBundle\Entity\Persona::class)->mayores_de(15);
            $votantes = array();
            foreach ($jefes_grupo_familiar as $j) { array_push($votantes, $j); }
            foreach ($personas as $p) { array_push($votantes, $p); }

            usort($votantes, array($this, "cmp"));

            return $this->render('inicio/registro-electoral.html.twig',
                array(
                    'votantes' => $votantes,
                    'comunidad' => $comunidad_info,
                    'consejo' => $cc));
        }else{
            $this->addFlash('danger', 'No se puede generar el Registro Electoral Preliminar hasta tanto no haya agregado los datos de la Comunidad');
            return $this->redirectToRoute('sic_homepage');
        }
    }
    public function registroElectoralPDFAction()
    {
        $em = $this->getDoctrine()->getManager();
        $comunidad = $em->getRepository(\SICBundle\Entity\Comunidad::class)->findAll();
        $consejo = $em->getRepository(\SICBundle\Entity\ConsejoComunal::class)->findAll();
        if (sizeof($comunidad) > 0) {
            $comunidad_info = $comunidad[0];
            $cc = $consejo[0];

            $jefes_grupo_familiar = $em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->mayores_de(15);
            $personas = $em->getRepository(\SICBundle\Entity\Persona::class)->mayores_de(15);
            $votantes = array();
            foreach ($jefes_grupo_familiar as $j) { array_push($votantes, $j); }
            foreach ($personas as $p) { array_push($votantes, $p); }

            usort($votantes, array($this, "cmp"));

            $dompdf = new \DOMPDF();
            $dompdf->set_paper(array(0,0,612.00,792.00), 'portrait');
            $dompdf->load_html($this->renderView('pdfs/registro-electoral-pdf.html.twig',
                array(
                    'votantes' => $votantes,
                    'comunidad' => $comunidad_info,
                    'base_path' => $_SERVER["DOCUMENT_ROOT"],
                    'consejo' => $cc)));

            $dompdf->render();
            $entrada = new Bitacora($this->getUser(),'generó','un Registro Electoral Preliminar');
            $em->persist($entrada);
            $em->flush();

            $response = new Response();
            $response->setContent($dompdf->stream("registro-electoral.pdf", array("Attachment"=>0)));
            $response->setStatusCode(200);
            $response->headers->set('Content-Type', 'application/pdf');
            return $response;

        }else{
            $this->addFlash('danger', 'No se puede generar el Registro Electoral Preliminar hasta tanto no haya agregado los datos de la Comunidad');
            return $this->redirectToRoute('sic_homepage');
        }
    }

    /* Estas funciones son identicas, difieren en el response, si cambias las contultas de una, debes cambiar las consultas de la otra. Obviamente el response se mantiene. */
    public function registroPreliminarAction()
    {
        $em = $this->getDoctrine()->getManager();
        $comunidad = $em->getRepository(\SICBundle\Entity\Comunidad::class)->findAll();
        $consejo = $em->getRepository(\SICBundle\Entity\ConsejoComunal::class)->findAll();
        if (sizeof($comunidad) > 0) {
            $comunidad_info = $comunidad[0];
            $cc = $consejo[0];

            $jefes_grupo_familiar = $em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->mayores_de(15);
            $personas = $em->getRepository(\SICBundle\Entity\Persona::class)->mayores_de(15);
            $votantesV = array();
            $votantesE = array();
            foreach ($jefes_grupo_familiar as $j) {
                if ($j->getNacionalidad() == 'V') { array_push($votantesV, $j); }
                if ($j->getNacionalidad() == 'E') { array_push($votantesE, $j); }
            }
            foreach ($personas as $p) {
                if ($p->getNacionalidad() == 'V') { array_push($votantesV, $p); }
                if ($p->getNacionalidad() == 'E') { array_push($votantesE, $p); }
            }

            usort($votantesV, array($this, "cmp"));
            usort($votantesE, array($this, "cmp"));

            return $this->render('inicio/registro-preliminar.html.twig',
                array(
                    'votantesV' => $votantesV,
                    'votantesE' => $votantesE,
                    'comunidad' => $comunidad_info,
                    'consejo' => $cc));
        }else{
            $this->addFlash('danger', 'No se puede generar el Cuaderno de Votación hasta tanto no haya agregado los datos de la Comunidad');
            return $this->redirectToRoute('sic_homepage');
        }
    }
    public function registroPreliminarPDFAction()
    {
        $em = $this->getDoctrine()->getManager();
        $comunidad = $em->getRepository(\SICBundle\Entity\Comunidad::class)->findAll();
        $consejo = $em->getRepository(\SICBundle\Entity\ConsejoComunal::class)->findAll();
        if (sizeof($comunidad) > 0) {
            $comunidad_info = $comunidad[0];
            $cc = $consejo[0];

            $jefes_grupo_familiar = $em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->mayores_de(15);
            $personas = $em->getRepository(\SICBundle\Entity\Persona::class)->mayores_de(15);
            $votantesV = array();
            $votantesE = array();
            foreach ($jefes_grupo_familiar as $j) {
                if ($j->getNacionalidad() == 'V') { array_push($votantesV, $j); }
                if ($j->getNacionalidad() == 'E') { array_push($votantesE, $j); }
            }
            foreach ($personas as $p) {
                if ($p->getNacionalidad() == 'V') { array_push($votantesV, $p); }
                if ($p->getNacionalidad() == 'E') { array_push($votantesE, $p); }
            }

            usort($votantesV, array($this, "cmp"));
            usort($votantesE, array($this, "cmp"));

            $dompdf = new \DOMPDF();
            $dompdf->set_paper(array(0,0,612.00,792.00), 'landscape');
            $dompdf->load_html($this->renderView('pdfs/registro-preliminar-pdf.html.twig',
                array(
                    'votantesV' => $votantesV,
                    'votantesE' => $votantesE,
                    'comunidad' => $comunidad_info,
                    'base_path' => $_SERVER["DOCUMENT_ROOT"],
                    'consejo' => $cc)));

            $dompdf->render();
            $entrada = new Bitacora($this->getUser(),'generó','un Registro Electoral Preliminar');
            $em->persist($entrada);
            $em->flush();

            $response = new Response();
            $response->setContent($dompdf->stream("registro-electoral-preliminar.pdf", array("Attachment"=>0)));
            $response->setStatusCode(200);
            $response->headers->set('Content-Type', 'application/pdf');
            return $response;

        }else{
            $this->addFlash('danger', 'No se puede generar el Cuaderno de Votación hasta tanto no haya agregado los datos de la Comunidad');
            return $this->redirectToRoute('sic_homepage');
        }
    }

    /* Funcion de busqueda del inicio de la app */
    public function buscadorAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $request->get('cadena');
        $cosas = array('query' => $query);

        /* Buscando en las tablas */
        $qb = $em->createQueryBuilder();

        $personas = $qb->select('p')->from(\SICBundle\Entity\Persona::class, 'p')
          ->where( $qb->expr()->like('p.cedula', $qb->expr()->literal('%' . $query . '%')) )
          ->orWhere( $qb->expr()->like('p.nombre', $qb->expr()->literal('%' . $query . '%')) )
          ->orWhere( $qb->expr()->like('p.apellido', $qb->expr()->literal('%' . $query . '%')) )
          ->getQuery()->getResult();

        // if (sizeof($personas) > 0) { $cosas['personas'] = $personas; }
        $cosas['personas'] = $personas;

        $jgf = $qb->select('j')->from(\SICBundle\Entity\JefeGrupoFamiliar::class, 'j')
          ->where( $qb->expr()->like('j.cedula', $qb->expr()->literal('%' . $query . '%')) )
          ->orWhere( $qb->expr()->like('j.nombres', $qb->expr()->literal('%' . $query . '%')) )
          ->orWhere( $qb->expr()->like('j.apellidos', $qb->expr()->literal('%' . $query . '%')) )
          ->getQuery()->getResult();

        // if (sizeof($jgf) > 0) { $cosas['jgf'] = $jgf; }
        $cosas['personas'] = $cosas['personas'] + $jgf;

        return $this->render('inicio/resultados_busqueda.html.twig', array('cosas' => $cosas));
    }

    public function ayudaSistemaAction(Request $request)
    {
        return $this->render('inicio/ayuda.html.twig');
    }

    public function descargarManualAction($tipo_manual)
    {
        $filename = "MANUAL DE USUARIO.pdf";
        $path = $this->getParameter('kernel.project_dir') . '/web/documentos/';
        $content = file_get_contents($path.$filename);

        $response = new Response();

        //set headers
        $response->headers->set('Content-Type', 'mime/type');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$filename);

        $response->setContent($content);
        return $response;
    }
}

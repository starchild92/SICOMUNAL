<?php

namespace SICBundle\Controller;

use SICBundle\Controller\BaseController;
use Slik\DompdfBundle\DomPDF;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SICBundle\Entity\Bitacora;

class EstadisticasController extends BaseController
{
    public function egPersonasAction(Request $request){
    	$todos = $this->estadisticasPersonas();
        return $this->render('estadisticas/estadisticas-generales-personas.html.twig',array('personas' => $todos));   
    }
    /*Se encarga de obtener las personas y separarlas segun el parametro que se tiene de la entidad*/
    public function estadisticasPersonas()
    {
        $em = $this->getDoctrine()->getManager();

        $nacionalidades = $em->getRepository('SICBundle:AdminNacionalidad')->findAll();
        $stat_nacionalidad_jgf = array();
        $stat_nacionalidad_p = array();
        foreach ($nacionalidades as $nacionalidad) {
            array_push(
                $stat_nacionalidad_jgf, array(
                    'nacionalidad' => $nacionalidad->getNacionalidad(),
                    'quienes'     => $em->getRepository('SICBundle:JefeGrupoFamiliar')->findBy(array('nacionalidad' => $nacionalidad->getId()))
                )
            );
            array_push(
                $stat_nacionalidad_p, array(
                    'nacionalidad' => $nacionalidad->getNacionalidad(),
                    'quienes'     => $em->getRepository('SICBundle:Persona')->findBy(array('nacionalidad' => $nacionalidad->getId()))
                    )
            );
        }

        $resp_cerradas = $em->getRepository('SICBundle:AdminRespCerrada')->findAll();
        $stat_cne_jgf = array();
        $stat_cne_p = array();
        foreach ($resp_cerradas as $resp) {
            array_push($stat_cne_jgf, array('resp' => $resp->getRespuesta(),'quienes'     => $em->getRepository('SICBundle:JefeGrupoFamiliar')->findBy(array('cne' => $resp->getId()))));
            array_push($stat_cne_p, array('resp' => $resp->getRespuesta(),'quienes'     => $em->getRepository('SICBundle:Persona')->findBy(array('cne' => $resp->getId()))));
        }
        
        $stat_empleado_jgf = array();
        foreach ($resp_cerradas as $resp) {
            array_push($stat_empleado_jgf, array('resp' => $resp->getRespuesta(),'quienes'=> $em->getRepository('SICBundle:JefeGrupoFamiliar')->findBy(array('trabajaActualmente' => $resp->getId()))));
        }

        $instruccion = $em->getRepository('SICBundle:AdminNivelInstruccion')->findAll();
        $stat_instruccion_jgf = array();
        $stat_instruccion_p = array();
        foreach ($instruccion as $elemento) {
        	$whos = $em->getRepository('SICBundle:JefeGrupoFamiliar')->findBy(array('nivelInstruccion' => $elemento->getId()));
			if (sizeof($whos)>0) { array_push($stat_instruccion_jgf, array('nivel' => $elemento->getNombre(),'quienes'=> $whos)); }
            $whos = $em->getRepository('SICBundle:Persona')->findBy(array('gradoInstruccion' => $elemento->getId()));
			if (sizeof($whos)>0) { array_push($stat_instruccion_p, array('nivel' => $elemento->getNombre(),'quienes'=> $whos)); }
        }

        $profesiones = $em->getRepository('SICBundle:AdminProfesion')->findAll();
        $stat_profesiones_jgf = array();
        $stat_profesiones_p = array();
        foreach ($profesiones as $elemento) {
        	$whos = $em->getRepository('SICBundle:JefeGrupoFamiliar')->findBy(array('profesion' => $elemento->getId()));
        	if (sizeof($whos)) { array_push($stat_profesiones_jgf, array('profesion' => $elemento->getNombre(),'quienes'=> $whos)); }
        	$whos = $em->getRepository('SICBundle:Persona')->findBy(array('profesion' => $elemento->getId()));
        	if (sizeof($whos)) { array_push($stat_profesiones_p, array('profesion' => $elemento->getNombre(),'quienes'=> $whos)); }
            
        }

        $discapacidades = $em->getRepository('SICBundle:AdminIncapacidades')->findAll();
        $stat_discapacidades_jgf = array();
        $stat_discapacidades_p = array();
        foreach ($discapacidades as $elemento) {
            $whos = $em->getRepository('SICBundle:JefeGrupoFamiliar')->findBy(array('incapacitadoTipo' => $elemento->getId()));
            if(sizeof($whos) > 0){ array_push($stat_discapacidades_jgf, array('discapacidad' => $elemento->getIncapacidad(),'quienes'=> $whos)); }
            $whos = $em->getRepository('SICBundle:Persona')->findBy(array('incapacitadoTipo' => $elemento->getId()));
            if(sizeof($whos) > 0){ array_push($stat_discapacidades_p, array('discapacidad' => $elemento->getIncapacidad(),'quienes'=> $whos)); }
            
        }

        $pensionados = $em->getRepository('SICBundle:AdminPensionadoInstitucion')->findAll();
        $stat_pensionados_jgf = array();
        $stat_pensionados_p = array();
        foreach ($pensionados as $elemento) {
            array_push($stat_pensionados_jgf, array('pensionados' => $elemento->getNombre(),'quienes'=> $em->getRepository('SICBundle:JefeGrupoFamiliar')->findBy(array('pensionadoInstitucion' => $elemento->getId()))));
            array_push($stat_pensionados_p, array('pensionados' => $elemento->getNombre(),'quienes'=> $em->getRepository('SICBundle:Persona')->findBy(array('pensionadoInstitucion' => $elemento->getId()))));
        }

        return array(
            'stat_nacionalidad_jgf' => $stat_nacionalidad_jgf,
            'stat_nacionalidad_p' => $stat_nacionalidad_p,

            'stat_cne_jgf' => $stat_cne_jgf,
            'stat_cne_p' => $stat_cne_p,

            'stat_instruccion_jgf' => $stat_instruccion_jgf,
            'stat_instruccion_p' => $stat_instruccion_p,

            'stat_profesiones_jgf' => $stat_profesiones_jgf,
            'stat_profesiones_p' => $stat_profesiones_p,

            'stat_empleado_jgf' => $stat_empleado_jgf,

            'stat_discapacidades_jgf' => $stat_discapacidades_jgf,
            'stat_discapacidades_p' => $stat_discapacidades_p,

            'stat_pensionados_jgf' => $stat_pensionados_jgf,
            'stat_pensionados_p' => $stat_pensionados_p,
        );
    }

    public function egEconomicaAction(Request $request){
        $todas = $this->estadisticasEconomica();
        return $this->render('estadisticas/estadisticas-generales-economica.html.twig',array('situacion' => $todas));   
    }
    public function estadisticasEconomica()
    {
        $em = $this->getDoctrine()->getManager();

        $ubic_trabajo = $em->getRepository('SICBundle:AdminUbicacionTrabajo')->findAll();
        $stat_ubic_trabajo = array();
        foreach ($ubic_trabajo as $elemento) {
            array_push($stat_ubic_trabajo, array('trabajo' => $elemento->getNombre(),'situacion' => $em->getRepository('SICBundle:SituacionEconomica')->findBy(array('dondeTrabaja' => $elemento->getId()))));
        }

        $venta_vivienda = $em->getRepository('SICBundle:AdminVentaVivienda')->findAll();
        $stat_venta_vivienda = array();
        foreach ($venta_vivienda as $elemento) {
            array_push($stat_venta_vivienda, array('venta_vivienda' => $elemento->getNombre(),'situacion' => $em->getRepository('SICBundle:SituacionEconomica')->findByActividadComercialVivienda($elemento)));
        }

        return array(
            'stat_ubic_trabajo'  => $stat_ubic_trabajo,
            'stat_venta_vivienda'  => $stat_venta_vivienda,
        );
    }

    public function egViviendaAction(Request $request){
        $todas = $this->estadisticasVivienda();
        return $this->render('estadisticas/estadisticas-generales-vivienda.html.twig',array('situacion' => $todas));   
    }
    public function estadisticasVivienda()
    {
        $em = $this->getDoctrine()->getManager();
        $situacionViviendas = $em->getRepository('SICBundle:SituacionVivienda')->findAll();

        $condicion_terreno = $em->getRepository('SICBundle:AdminTipoCondicionTerreno')->findAll();
        $stat_condicion_terreno = array();
        foreach ($condicion_terreno as $elemento) {
            array_push($stat_condicion_terreno, array('elemento' => $elemento->getNombre(),'situacion' => $em->getRepository('SICBundle:SituacionVivienda')->findBy(array('condicionesTerreno' => $elemento->getId()))));
        }

        $tipo_vivienda = $em->getRepository('SICBundle:AdminTipoVivienda')->findAll();
        $stat_tipo_vivienda = array();
        foreach ($tipo_vivienda as $elemento) {
            array_push($stat_tipo_vivienda, array('elemento' => $elemento->getNombre(),'situacion' => $em->getRepository('SICBundle:SituacionVivienda')->findBy(array('tipo' => $elemento->getId()))));
        }

        $tipo_tenencia = $em->getRepository('SICBundle:AdminTipoTenencia')->findAll();
        $stat_tipo_tenencia = array();
        foreach ($tipo_tenencia as $elemento) {
            array_push($stat_tipo_tenencia, array('elemento' => $elemento->getForma(),'situacion' => $em->getRepository('SICBundle:SituacionVivienda')->findBy(array('tenencia' => $elemento->getId()))));
        }

        $terreno = $em->getRepository('SICBundle:AdminRespCerrada')->findAll();
        $stat_terreno = array();
        foreach ($terreno as $elemento) {
            array_push($stat_terreno, array('elemento' => $elemento->getRespuesta(),'situacion' => $em->getRepository('SICBundle:SituacionVivienda')->findBy(array('terrenoPropio' => $elemento->getId()))));
        }

        $ovc = $em->getRepository('SICBundle:AdminRespCerrada')->findAll();
        $stat_ovc = array();
        foreach ($ovc as $elemento) {
            array_push($stat_ovc, array('elemento' => $elemento->getRespuesta(),'situacion' => $em->getRepository('SICBundle:SituacionVivienda')->findBy(array('ovc' => $elemento->getId()))));
        }

        $paredes = $em->getRepository('SICBundle:AdminTipoParedes')->findAll();
        $stat_paredes = array();
        foreach ($paredes as $elemento) {
            array_push($stat_paredes, array('elemento' => $elemento->getNombre(),'situacion' => $em->getRepository('SICBundle:SituacionVivienda')->findBy(array('paredes' => $elemento->getId()))));
        }

        $sivih = $em->getRepository('SICBundle:AdminRespCerrada')->findAll();
        $stat_sivih = array();
        foreach ($sivih as $elemento) {
            array_push($stat_sivih, array('elemento' => $elemento->getRespuesta(),'situacion' => $em->getRepository('SICBundle:SituacionVivienda')->findBy(array('sivih' => $elemento->getId()))));
        }

        $leypoliticahabitacional = $sivih;
        $stat_leypoliticahabitacional = array();
        foreach ($leypoliticahabitacional as $elemento) {
            array_push($stat_leypoliticahabitacional, array('elemento' => $elemento->getRespuesta(),'situacion' => $em->getRepository('SICBundle:SituacionVivienda')->findBy(array('leypoliticahabitacional' => $elemento->getId()))));
        }

        $techo = $em->getRepository('SICBundle:AdminTipoTecho')->findAll();
        $stat_techo = array();
        foreach ($techo as $elemento) {
            array_push($stat_techo, array('elemento' => $elemento->getNombre(),'situacion' => $em->getRepository('SICBundle:SituacionVivienda')->findBy(array('techo' => $elemento->getId()))));
        }

        $enseres = $em->getRepository('SICBundle:AdminTipoEnseres')->findAll();
        $stat_enseres = array();
        foreach ($enseres as $elemento) {
            array_push($stat_enseres, array('elemento' => $elemento->getNombre(),'situacion' => $em->getRepository('SICBundle:SituacionVivienda')->findByEnseres($elemento)));
        }

        $salubridad = $em->getRepository('SICBundle:AdminSalubridadVivienda')->findAll();
        $stat_salubridad = array();
        foreach ($salubridad as $elemento) {
            array_push($stat_salubridad, array('elemento' => $elemento->getNombre(),'situacion' => $em->getRepository('SICBundle:SituacionVivienda')->findBy(array('salubridad' => $elemento->getId()))));
        }

        $plagas = $em->getRepository('SICBundle:AdminTipoPlagas')->findAll();
        $stat_plagas = array();
        foreach ($plagas as $elemento) {
            array_push($stat_plagas, array('elemento' => $elemento->getNombre(),'situacion' => $em->getRepository('SICBundle:SituacionVivienda')->findByPresenciaInsectos($elemento)));
        }

        $mascotas = $em->getRepository('SICBundle:AdminTipoMascotas')->findAll();
        $stat_mascotas = array();
        foreach ($mascotas as $elemento) {
            array_push($stat_mascotas, array('elemento' => $elemento->getNombre(),'situacion' => $em->getRepository('SICBundle:SituacionVivienda')->findByMascota($elemento)));
        }

        return array(
            'stat_condicion_terreno' => array('array' => $stat_condicion_terreno, 'title' => 'Condicion del Terreno'),
            'stat_tipo_tenencia' => array('array' => $stat_tipo_tenencia, 'title' => 'Tipos de Tenencia'),
            'stat_tipo_vivienda' => array('array' => $stat_tipo_vivienda, 'title' => 'Tipos de Vivienda'),
            'stat_ovc' => array('array' => $stat_ovc, 'title' => '¿Pertenece Ud. a Una OCV?'),
            'stat_terreno' => array('array' => $stat_terreno, 'title' => '¿Terreno Propio?'),
            'stat_paredes' => array('array' => $stat_paredes, 'title' => 'Paredes'),
            'stat_techo' => array('array' => $stat_techo, 'title' => 'Techo'),
            'stat_sivih' => array('array' => $stat_sivih, 'title' => '¿Está inscrita en el SIVIH?'),
            'stat_leypoliticahabitacional' => array('array' => $stat_leypoliticahabitacional, 'title' => 'Cotizantes Ley de Política Habitacional'),
            'stat_enseres' => array('array' => $stat_enseres, 'title' => 'Enres Vivienda'),
            'stat_salubridad' => array('array' => $stat_salubridad, 'title' => 'Salubridad'),
            'stat_plagas' => array('array' => $stat_plagas, 'title' => 'Presencia de Insectos y Roedores'),
            'stat_mascotas' => array('array' => $stat_mascotas, 'title' => 'Mascotas'),
        );
    }

    public function egSaludAction(Request $request){
        $todas = $this->estadisticasSalud();
        return $this->render('estadisticas/estadisticas-generales-salud.html.twig',array('situacion' => $todas));   
    }
    public function estadisticasSalud()
    {
        $em = $this->getDoctrine()->getManager();

        $padecencias = $em->getRepository('SICBundle:AdminTipoPadecencia')->findAll();
        $stat_padecencias = array();
        foreach ($padecencias as $elemento) {
            array_push($stat_padecencias, array('elemento' => $elemento->getNombre(),'situacion' => ($em->getRepository('SICBundle:SituacionSalud')->padecencia($elemento))));
        }

        $ayuda_especial = $em->getRepository('SICBundle:AdminTipoAyudaEspecial')->findAll();
        $stat_ayuda_especial = array();
        foreach ($ayuda_especial as $elemento) {
            array_push($stat_ayuda_especial, array('elemento' => $elemento->getNombre(),'situacion' => ($em->getRepository('SICBundle:SituacionSalud')->ayudaEspecial($elemento))));
        }

        $situacion_exclusion = $em->getRepository('SICBundle:AdminTipoSituacion')->findAll();
        $stat_situacion_exclusion = array();
        foreach ($situacion_exclusion as $elemento) {
            array_push($stat_situacion_exclusion, array('elemento' => $elemento->getSituacion(),'situacion' => ($em->getRepository('SICBundle:SituacionSalud')->situacionExclusion($elemento))));
        }

        return array(
            'stat_ayuda_especial' => $stat_ayuda_especial,
            'stat_padecencias' => $stat_padecencias,
            'stat_situacion_exclusion' => $stat_situacion_exclusion,
        );
    }

    public function egServiciosAction(Request $request){
        $todas = $this->estadisticasServicios();
        return $this->render('estadisticas/estadisticas-generales-servicios.html.twig',array('situacion' => $todas));   
    }
    public function estadisticasServicios()
    {
        $em = $this->getDoctrine()->getManager();
        $situacionServicios = $em->getRepository('SICBundle:SituacionServicios')->findAll();

        $aguasb = $em->getRepository('SICBundle:AdminAguasBlancas')->findAll();
        $stat_aguasb = array();
        foreach ($aguasb as $elemento) {
            array_push($stat_aguasb, array('elemento' => $elemento->getNombre(), 'situacion' => $em->getRepository('SICBundle:SituacionServicios')->findBy(array('aguasBlancas' => $elemento->getId()))));
        }

        $tanques = $em->getRepository('SICBundle:SituacionServicios')->findAll();
        $stat_tanque = array();
        $stat_tanque_si = array();
        $stat_tanque_no = array();
        foreach ($tanques as $t) {
            if ($t->getLtsTanque() > 0) { array_push($stat_tanque_si, $t); }else{ array_push($stat_tanque_no, $t); }
        }
        
        $stat_pipotes = array();
        $stat_pipotes_si = array();
        $stat_pipotes_no = array();
        foreach ($tanques as $t) { 
            if ($t->getCantPipotes() > 0) { array_push($stat_pipotes_si, $t); }else{ array_push($stat_pipotes_no, $t); }
        }
        array_push($stat_pipotes, array('elemento' => 'Si', 'situacion' => $stat_pipotes_si));
        array_push($stat_pipotes, array('elemento' => 'No', 'situacion' => $stat_pipotes_no));

        $aguasservidas = $em->getRepository('SICBundle:AdminAguasServidas')->findAll();
        $stat_aguasservidas = array();
        foreach ($aguasservidas as $elemento) {
            array_push($stat_aguasservidas, array('elemento' => $elemento->getNombre(), 'situacion' => $em->getRepository('SICBundle:SituacionServicios')->findBy(array('aguasServidas' => $elemento->getId()))));
        }

        $gas = $em->getRepository('SICBundle:AdminTipoGas')->findAll();
        $stat_gas = array();
        foreach ($gas as $elemento) {
            array_push($stat_gas, array('elemento' => $elemento->getNombre(), 'situacion' => $em->getRepository('SICBundle:SituacionServicios')->findBy(array('gas' => $elemento->getId()))));
        }

        $empresaGas = $em->getRepository('SICBundle:AdminEmpresaGas')->findAll();
        $stat_empresaGas = array();
        foreach ($empresaGas as $elemento) {
            array_push($stat_empresaGas, array('elemento' => $elemento->getNombre(), 'situacion' => $em->getRepository('SICBundle:SituacionServicios')->findBy(array('empresaGas' => $elemento->getId()))));
        }

        $sistemas_electrico = $em->getRepository('SICBundle:AdminSistemaElectrico')->findAll();
        $stat_sistemas_electrico = array();
        foreach ($sistemas_electrico as $elemento) {
            array_push($stat_sistemas_electrico, array('elemento' => $elemento->getNombre(), 'situacion' => $em->getRepository('SICBundle:SituacionServicios')->findBy(array('sistemaElectrico' => $elemento->getId()))));
        }

        $resp_cerradas = $em->getRepository('SICBundle:AdminRespCerrada')->findAll();
        $stat_medidor_electrico = array();
        foreach ($resp_cerradas as $resp) {
            array_push($stat_medidor_electrico, array('elemento' => $resp->getRespuesta(), 'situacion' => $em->getRepository('SICBundle:SituacionServicios')->findBy(array('medidorElectrico' => $resp->getId()))));
        }

        $basura = $em->getRepository('SICBundle:AdminRecoleccionBasura')->findAll();
        $stat_basura = array();
        foreach ($basura as $elemento) {
            array_push($stat_basura, array('elemento' => $elemento->getNombre(), 'situacion' => $em->getRepository('SICBundle:SituacionServicios')->findBy(array('recoleccionBasura' => $elemento->getId()))));
        }

        $telefonia = $em->getRepository('SICBundle:AdminTipoTelefonia')->findAll();
        $stat_telefonia = array();
        foreach ($telefonia as $elemento) {
            array_push($stat_telefonia, array('elemento' => $elemento->getNombre(), 'situacion' => $em->getRepository('SICBundle:SituacionServicios')->telefonia($elemento)));
        }

        $transporte = $em->getRepository('SICBundle:AdminTipoTransporte')->findAll();
        $stat_transporte = array();
        foreach ($transporte as $elemento) {
            array_push($stat_transporte, array('elemento' => $elemento->getNombre(), 'situacion' => $em->getRepository('SICBundle:SituacionServicios')->transporte($elemento)));
        }

        $mecanismoInformacion = $em->getRepository('SICBundle:AdminMecanismoInformacion')->findAll();
        $stat_mecanismoInformacion = array();
        foreach ($mecanismoInformacion as $elemento) {
            array_push($stat_mecanismoInformacion, array('elemento' => $elemento->getNombre(), 'situacion' => $em->getRepository('SICBundle:SituacionServicios')->mecanismoInformacion($elemento)));
        }

        $serviciosComunales = $em->getRepository('SICBundle:AdminServiciosComunales')->findAll();
        $stat_serviciosComunales = array();
        foreach ($serviciosComunales as $elemento) {
            array_push($stat_serviciosComunales, array('elemento' => $elemento->getNombre(), 'situacion' => $em->getRepository('SICBundle:SituacionServicios')->serviciosComunales($elemento)));
        }

        $resp_cerradas = $em->getRepository('SICBundle:AdminRespCerrada')->findAll();
        $stat_medidor = array();
        foreach ($resp_cerradas as $resp) {
            array_push($stat_medidor, array('elemento' => $resp->getRespuesta(), 'situacion' => $em->getRepository('SICBundle:SituacionServicios')->findBy(array('medidor' => $resp->getId()))));
        }



        $capacidadBombona = $em->getRepository('SICBundle:AdminCapacidadBombona')->findAll();
        $stat_capacidadBombona = array();
        foreach ($capacidadBombona as $elemento) {
            array_push($stat_capacidadBombona, array('elemento' => $elemento->getNombre(), 'situacion' => $em->getRepository('SICBundle:SituacionServicios')->findBy(array('capacidadBombona' => $elemento->getId()))));
        }

        $resp_cerradas = $em->getRepository('SICBundle:AdminRespCerrada')->findAll();
        $stat_bombillosAhorradores = array();
        foreach ($resp_cerradas as $resp) {
            array_push($stat_bombillosAhorradores, array('elemento' => $resp->getRespuesta(), 'situacion' => $em->getRepository('SICBundle:SituacionServicios')->findBy(array('bombillosAhorradores' => $resp->getId()))));
        }

        return array(
            'stat_aguasb' => array('array' => $stat_aguasb, 'title' => 'Aguas Blancas'),
            'stat_medidor' => array('array' => $stat_medidor, 'title' => 'Medidor de Aguas Blancas'),
            'stat_tanque' => array('array' => $stat_tanque, 'title' => 'Posee Tanque'),
            'stat_pipotes' => array('array' => $stat_pipotes, 'title' => 'Pipotes'),
            'stat_aguasservidas' => array('array' => $stat_aguasservidas, 'title' => 'Aguas Servidas'),
            'stat_gas' => array('array' => $stat_gas, 'title' => 'Posee Gas'),
            'stat_empresaGas' => array('array' => $stat_empresaGas, 'title' => 'Proveedor de Gas'),
            'stat_sistemas_electrico' => array('array' => $stat_sistemas_electrico, 'title' => 'Sistema Electrico'),
            'stat_medidor_electrico' => array('array' => $stat_medidor_electrico, 'title' => 'Medidor de Electricidad'),
            'stat_serviciosComunales' => array('array' => $stat_serviciosComunales, 'title' => 'Servicios Comunales'),
            'stat_mecanismoInformacion' => array('array' => $stat_mecanismoInformacion, 'title' => 'Mecanismos de Información'),
            'stat_transporte' => array('array' => $stat_transporte, 'title' => 'Medio de Transporte'),
            'stat_telefonia' => array('array' => $stat_telefonia, 'title' => 'Telefonia'),
            'stat_basura' => array('array' => $stat_basura, 'title' => 'Sistema de Recoleccion de Basura'),
            'stat_bombillosAhorradores' => array('array' => $stat_bombillosAhorradores, 'title' => 'Bombillos Ahorradores'),
            'stat_capacidadBombona' => array('array' => $stat_capacidadBombona, 'title' => 'Capacidades Bombonas'),
        );
    }

    public function egParticipacionAction(Request $request){
        $todas = $this->estadisticasParticipacion();
        return $this->render('estadisticas/estadisticas-generales-participacion.html.twig',array('situacion' => $todas));   
    }
    public function estadisticasParticipacion()
    {
        $em = $this->getDoctrine()->getManager();

        $participacionComunitarias = $em->getRepository('SICBundle:ParticipacionComunitaria')->findAll();

        $orgs = $em->getRepository('SICBundle:AdminOrgComunitaria')->findAll();
        $stat_orgs = array();
        foreach ($orgs as $elemento) {
            array_push($stat_orgs, array('elemento' => $elemento->getNombre(),'situacion' => $em->getRepository('SICBundle:ParticipacionComunitaria')->existenOrganizaciones($elemento)));
        }

        $participacion = $em->getRepository('SICBundle:AdminRespCerrada')->findAll();
        $stat_participacion = array();
        foreach ($participacion as $elemento) {
            array_push($stat_participacion, array('elemento' => $elemento->getRespuesta(),'situacion'     => $em->getRepository('SICBundle:ParticipacionComunitaria')->findBy(array('participaOrganizacion' => $elemento->getId()))));
        }

        $parte_miembros = $em->getRepository('SICBundle:AdminRespCerrada')->findAll();
        $stat_parte_miembros = array();
        foreach ($parte_miembros as $elemento) {
            array_push($stat_parte_miembros, array('elemento' => $elemento->getRespuesta(),'situacion' => $em->getRepository('SICBundle:ParticipacionComunitaria')->findBy(array('participaMiembroOrganizacion' => $elemento->getId()))));
        }

        $misiones = $em->getRepository('SICBundle:AdminMisionesComunidad')->findAll();
        $stat_misiones = array();
        foreach ($misiones as $elemento) {
            array_push($stat_misiones, array('elemento' => $elemento->getNombre(),'situacion' => $em->getRepository('SICBundle:ParticipacionComunitaria')->misionesComunidad($elemento)));
        }

        $areatrabajo = $em->getRepository('SICBundle:AdminAreaTrabajoCC')->findAll();
        $stat_areatrabajo = array();
        foreach ($areatrabajo as $elemento) {
            array_push($stat_areatrabajo, array('elemento' => $elemento->getNombre(),'situacion' => $em->getRepository('SICBundle:ParticipacionComunitaria')->areaTabajoCC($elemento)));
        }

        return array(
            'stat_areatrabajo' => array('array' => $stat_areatrabajo, 'title' => 'Areas de Trabajo'),
            'stat_misiones' => array('array' => $stat_misiones, 'title' => 'Misiones Comunidad'),
            'stat_orgs' => array('array' => $stat_orgs, 'title' => 'Organizaciones'),
            'stat_participacion' => array('array' => $stat_participacion, 'title' => 'Participación Comunitaria'),
            'stat_parte_miembros' => array('array' => $stat_parte_miembros, 'title' => ' Participación Miembros'),
        );
    }
}

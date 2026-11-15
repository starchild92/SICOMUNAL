<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Noticia;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\NoticiaType;

/**
 * Noticia controller.
 *
 */
class NoticiaController extends BaseController
{
    /**
     * Lists all Noticia entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $noticias = $em->getRepository(\SICBundle\Entity\Noticia::class)->findAll();

        return $this->render('noticia/index.html.twig', array(
            'noticias' => $noticias,
        ));
    }

    /**
     * Creates a new Noticia entity.
     *
     */
    public function newAction(Request $request)
    {
        $noticium = new Noticia();
        $form = $this->createForm('SICBundle\Form\NoticiaType', $noticium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $noticium->setUsuario($this->getUser());
            $noticium->setFecha(new \DateTime('now'));
            $noticium->setFechaPub(new \DateTime('now'));
            $this->addFlash('success', 'Se ha agregado una nueva noticia, titulada "'.$noticium->getTitulo().'"');
            $entrada = new Bitacora($this->getUser(),'agregó','una Noticia nueva, titulada: '.$noticium->getTitulo().'.');
            $em->persist($noticium);
            $em->persist($entrada);

            $em->flush();

            return $this->redirectToRoute('noticia_show', array('id' => $noticium->getId()));
        }

        return $this->render('noticia/new.html.twig', array(
            'noticium' => $noticium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Noticia entity.
     *
     */
    public function showAction(Noticia $noticium)
    {
        $deleteForm = $this->createDeleteForm($noticium);

        return $this->render('noticia/show.html.twig', array(
            'noticium' => $noticium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Noticia entity.
     *
     */
    public function editAction(Request $request, Noticia $noticium)
    {
        $deleteForm = $this->createDeleteForm($noticium);
        $editForm = $this->createForm('SICBundle\Form\NoticiaType', $noticium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($noticium);
            $this->addFlash('success', 'Se han modificado una noticia');
            $entrada = new Bitacora($this->getUser(),'modificó','una la noticia '.$noticium->getTitulo().'.');
            $em->persist($entrada);
            $em->flush();

            return $this->redirectToRoute('noticia_edit', array('id' => $noticium->getId()));
        }

        return $this->render('noticia/edit.html.twig', array(
            'noticium' => $noticium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Noticia entity.
     *
     */
    public function deleteAction(Request $request, Noticia $noticium)
    {
        $form = $this->createDeleteForm($noticium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $this->addFlash('success', 'Se eliminó la noticia: '.$noticium->getTitulo());
            $entrada = new Bitacora($this->getUser(),'eliminó','una Noticia '.$noticium->getTitulo());
            $em->remove($noticium);
            $em->persist($entrada);
            $em->flush();
        }

        return $this->redirectToRoute('noticia_index');
    }

    /**
     * Creates a form to delete a Noticia entity.
     *
     * @param Noticia $noticium The Noticia entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Noticia $noticium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('noticia_delete', array('id' => $noticium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    public function newsletterAction()
    {
        $em = $this->getDoctrine()->getManager();
        /* Obtiene las últimas 5 noticias en orden descendiente y que están visibles */
        $noticias = $em->getRepository(\SICBundle\Entity\Noticia::class)->NoticiasOrdenDesc(); //solo las noticias que están visibles

        return $this->render('noticia/newsletter.html.twig', array(
            'noticias' => $noticias,
        ));
    }

    public function cmpVotos($a, $b){
        if ($a['votos'] == $b['votos']) { return 0; }
        return ($a['votos'] > $b['votos']) ? -1 : 1;
    }
    public function voceriasAction()
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
                    usort($personas, array($this, "cmpVotos"));
                }

                array_push($unidades_restantes, array(
                    "id" => $ue->getId(),
                    "tipoUnidad" => $ue->getTipoUnidad(),
                    "voceros" => $personas,
                ));
            }
        }
        
        $em->flush();

        // Solo una sola instancia de esta entidad
        if (count($comunidad) > 0) { $comunidad = $comunidad[0]; }
        if (count($cc) > 0) { $cc = $cc[0]; }

        return $this->render('noticia/vocerias.html.twig', 
            array(
                'comunidad' =>  $comunidad,
                'consejo'   =>  $cc,
                'unidades_eje'   =>  $unidades_eje,
                'unidades_restantes'   =>  $unidades_restantes,
            ));
    }

    /* cambia el estado de visto de una noticia*/
    public function alternarVisibilidadAction(Request $request, Noticia $noticium)
    {
        if ($this->getUser() != NULL) {
            $em = $this->getDoctrine()->getManager();
            if ($noticium->getVisibilidad() == '0') {
                //a invisible
                $noticium->setVisibilidad('1');
                $this->addFlash('success', 'Se ha colocado la noticia "'.$noticium->getTitulo().'" como visible.');
                $entrada = new Bitacora($this->getUser(),'modificó','la visibilidad de '.$noticium->getTitulo().' haciendola visible');
            }else{
                // a visible
                $noticium->setVisibilidad('0');
                $this->addFlash('success', 'Se ha colocado la noticia "'.$noticium->getTitulo().'" como invisible.');
                $entrada = new Bitacora($this->getUser(),'modificó','la visibilidad de '.$noticium->getTitulo().' haciendola invisible');
            }
            $em->persist($noticium);
            $em->persist($entrada);
            $em->flush();

            return NULL;
        }

        return $this->redirectToRoute('sic_homepage');
    }

    // Devuelve los comunicados enviados a la vista de correo.html.twig
    public function correosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $comunicados = $em->getRepository(\SICBundle\Entity\Comunicado::class)->findAll();
        $usuarios_reciben_correo = $em->getRepository(\SICBundle\Entity\Noticia::class)->CantidadPersonasCorreo();

        return $this->render('noticia/correos.html.twig', array(
            'comunicados' => $comunicados,
            'reciben_correo' => $usuarios_reciben_correo,
        ));
    }

    public function correosUsuariosAction()
    {
        $em = $this->getDoctrine()->getManager();
        $jfg = $em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findAll();
        $persona = $em->getRepository(\SICBundle\Entity\Persona::class)->findAll();
        $usuarios_reciben_correo = $em->getRepository(\SICBundle\Entity\Noticia::class)->CantidadPersonasCorreo();

        return $this->render('noticia/correos.usuarios.html.twig', array(
            'jfg' => $jfg,
            'persona' => $persona,
            'reciben_correo' => $usuarios_reciben_correo,
        ));
    }

    public function alternarSubscripcionAction($correo)
    {
        $em = $this->getDoctrine()->getManager();

        $jfg = $em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(array('email' => $correo));
        $personas = $em->getRepository(\SICBundle\Entity\Persona::class)->findBy(array('email' => $correo));

        if (sizeof($jfg) > 0) {
            $persona = $jfg[0];
            if ($persona->getRecibirCorreo()) {
                $persona->setRecibirCorreo(false);
                $bitacora = new Bitacora($this->getUser(),'modificó','cambio el estatus para que '.$correo.' no reciba los correos del sistema.');
            }else{
                $persona->setRecibirCorreo(true);
                $bitacora = new Bitacora($this->getUser(),'modificó','cambio el estatus para que '.$correo.' reciba los correos del sistema.');
            }
        }

        if (sizeof($personas) > 0) {
            $persona = $personas[0];
            if ($persona->getRecibirCorreo()) {
                $persona->setRecibirCorreo(false);
                $bitacora = new Bitacora($this->getUser(),'modificó','cambio el estatus para que '.$correo.' no reciba los correos del sistema.');
            }else{
                $persona->setRecibirCorreo(true);
                $bitacora = new Bitacora($this->getUser(),'modificó','cambio el estatus para que '.$correo.' reciba los correos del sistema.');
            }
        }

        $em->persist($bitacora);
        $em->persist($persona);
        $em->flush();

        return $this->redirectToRoute('noticia_correo_usuarios');
    }
}

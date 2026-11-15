<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Comite;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\ComiteType;

/**
 * Comite controller.
 *
 */
class ComiteController extends BaseController
{
    public function getPersonaByCedula($cedula)
    {
        $em = $this->getDoctrine()->getManager();
        $resultado = $em->getRepository('SICBundle:JefeGrupoFamiliar')->findBy(array('cedula' => $cedula));
        if (sizeof($resultado) > 0) {
            return $resultado[0];
        }else{
            $resultado = $em->getRepository('SICBundle:Persona')->findBy(array('cedula' => $cedula));
            if (sizeof($resultado) > 0) {
                return $resultado[0];
            }else{
                return null;
            }
        }
    }

    public function ExisteTipoUnidad(Comite $comite)
    {
        $em = $this->getDoctrine()->getManager();

        if ($comite->getTipoUnidad() == "Unidad Ejecutiva") {
            if ($comite->getNombre() != "") {
                $comites = $em->getRepository('SICBundle:Comite')->findBy(array(
                    'tipoUnidad' => "Unidad Ejecutiva", "nombre" => $comite->getNombre()));
                if (sizeof($comites) > 0) {
                    $this->addFlash('error', 'Ya hay una "'.$comite->getTipoUnidad().'" de Nombre: "'.$comite->getNombre().'" en el sistema.');
                    return true;
                }
            }else{
                $this->addFlash('error', 'Para una nueva "'.$comite->getTipoUnidad().'" debe agregar un nombre.');
                return true;
            }
        }else{
            $comites = $em->getRepository('SICBundle:Comite')->findAll();
            foreach ($comites as $key) {
                if ($comite->getId() != $key->getId()) {
                    if ($comite->getTipoUnidad() == $key->getTipoUnidad()) {
                        $this->addFlash('error', 'Ya hay una "'.$comite->getTipoUnidad().'" de este Tipo conformada.');
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function personaEsVocero($cedula, $comite = '')
    {
        $em = $this->getDoctrine()->getManager();
        //En la entidad el atributo es unique, esto debe ser binario
        $vocero = $em->getRepository('SICBundle:Vocero')->findBy(array('persona' => $cedula));
        
        if (sizeof($vocero) > 0) {
            $comites = $vocero[0]->getComite();
            foreach ($comites as $com) { if ($com->getId() == $comite) { return false; } }
            $persona = $this->getPersonaByCedula($cedula);
            $this->addFlash('error', 'El usuario "'.$persona->nombreyapellido().'" ya es miembro de un comité.');
            return true;
        }else{
            return false;
        }
    }

    public function PermitirNuevoVocero(Comite $comite)
    {
        /*
        Las opciones son
        Unidad Ejectuiva
            Por cada unidad existen solo 2 voceros (1 principal / 1 suplente)
        Administrativa
            5 voceros (5 principales / 5 suplentes) = 10 voceros
        Contraloria
            5 voceros (5 principales / 5 suplentes) = 10 voceros
        Comision Electoral
            5 voceros (5 principales / 5 suplentes) = 10 voceros
        */
        switch ( $comite->getTipoUnidad() ) {
            case 'Unidad Ejecutiva':
                if ( $comite->getCantVoceros() <= 2) {
                    return true;
                }else{
                    $this->addFlash('error', $comite->getTipoUnidad().' no admite nuevos voceros.');
                    return false;
                }
                break;
            
            default:
                if ( $comite->getCantVoceros() <= 10) {
                    return true;
                }else{
                    $this->addFlash('error', $comite->getTipoUnidad().' no admite nuevos voceros.');
                    return false;
                }
                break;
        }
    }

    /**
     * Lists all Comite entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $comites = $em->getRepository('SICBundle:Comite')->findAll();

        return $this->render('comite/index.html.twig', array(
            'comites' => $comites,
        ));
    }

    /**
     * Creates a new Comite entity.
     *
     */
    public function newAction(Request $request)
    {
        $comite = new Comite();
        $form = $this->createForm('SICBundle\Form\ComiteType', $comite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->ExisteTipoUnidad($comite)) {
                $em = $this->getDoctrine()->getManager();

                if ($this->PermitirNuevoVocero($comite)) {
                    $voceros = $comite->getVoceros();
                    foreach ($voceros as $v) {

                        $cedula = filter_var($v->getPersona(), FILTER_SANITIZE_NUMBER_INT);
                        if ($this->getPersonaByCedula($cedula) == NULL) {
                            $v->setPersona($cedula);
                            $this->addFlash('danger', 'La cédula '.$cedula.' no existe en el sistema.');
                            return $this->render('comite/new.html.twig', array(
                                'comite' => $comite,
                                'form' => $form->createView(),
                            ));
                        }

                        //Pasa la cedula a la función para verificar si es miembro de un comité
                        if ($this->personaEsVocero($v->getPersona(), '')) {
                            return $this->render('comite/new.html.twig', array(
                                'comite' => $comite,
                                'form' => $form->createView(),
                            ));
                        }
                    }
                    $comite->setCantVoceros(sizeof($voceros));

                    $bitacora = new Bitacora($this->getUser(),'agregó', sizeof($voceros).' miembro(s) a '.$comite->getTipoUnidad());
                    $em->persist($bitacora);

                    $em->persist($comite);
                    $em->flush();

                    $this->addFlash('success', 'Se ha creado el comité de forma exitosa, asignadosé '.sizeof($voceros).' voceros a el.');

                    return $this->redirectToRoute('comites_index');
                }
            }
        }

        return $this->render('comite/new.html.twig', array(
            'comite' => $comite,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Comite entity.
     *
     */
    public function showAction(Comite $comite)
    {
        $deleteForm = $this->createDeleteForm($comite);

        return $this->render('comite/show.html.twig', array(
            'comite' => $comite,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Comite entity.
     *
     */
    public function editAction(Request $request, Comite $comite)
    {
        $deleteForm = $this->createDeleteForm($comite);
        $editForm = $this->createForm('SICBundle\Form\ComiteType', $comite);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $voceros = $comite->getVoceros();

            /* En la cedulas solo se permiten numeros */
            foreach ($voceros as $vocero) {
                $cedula = filter_var($vocero->getPersona(), FILTER_SANITIZE_NUMBER_INT);
                $vocero->setPersona($cedula);

                if ($this->getPersonaByCedula($cedula) == NULL) {
                    $this->addFlash('danger', 'La cédula '.$cedula.' no existe en el sistema.');
                    return $this->render('comite/edit.html.twig', array(
                        'comite' => $comite,
                        'edit_form' => $editForm->createView(),
                        'delete_form' => $deleteForm->createView(),
                    ));
                }

                if ($this->personaEsVocero($cedula, $comite->getId())) {
                    return $this->render('comite/edit.html.twig', array(
                        'comite' => $comite,
                        'edit_form' => $editForm->createView(),
                        'delete_form' => $deleteForm->createView(),
                    ));
                }
            }

            if ($this->PermitirNuevoVocero($comite)) {
                $comite->setCantVoceros(sizeof($voceros));

                $bitacora = new Bitacora($this->getUser(),'modificó', $comite->getTipoUnidad());
                $em->persist($bitacora);

                $em->persist($comite);
                $em->flush();

                $this->addFlash('success', 'Se modificó con exito '.$comite->getTipoUnidad());
                return $this->redirectToRoute('comites_index');
            }
        }

        return $this->render('comite/edit.html.twig', array(
            'comite' => $comite,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Comite entity.
     *
     */
    public function deleteAction(Request $request, Comite $comite)
    {
        $form = $this->createDeleteForm($comite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $bitacora = new Bitacora($this->getUser(),'eliminó', $comite->getTipoUnidad()." y ".$comite->getCantVoceros()." sus voceros.");
                    $em->persist($bitacora);
            $em->remove($comite);
            $em->flush();

            $this->addFlash('success', 'Se eliminó con exito '.$comite->getTipoUnidad());
        }

        return $this->redirectToRoute('comites_index');
    }

    /**
     * Creates a form to delete a Comite entity.
     *
     * @param Comite $comite The Comite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Comite $comite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comites_delete', array('id' => $comite->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

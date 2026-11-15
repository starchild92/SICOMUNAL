<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\ParticipacionComunitaria;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\ParticipacionComunitariaType;

/**
 * ParticipacionComunitaria controller.
 *
 */
class ParticipacionComunitariaController extends BaseController
{
    private function obtenerStats()
    {
        $em = $this->getDoctrine()->getManager();

        $participacionComunitarias = $em->getRepository(\SICBundle\Entity\ParticipacionComunitaria::class)->findAll();
        $total = sizeof($participacionComunitarias);
        $orgs = $em->getRepository(\SICBundle\Entity\AdminOrgComunitaria::class)->findAll();
        $stat_orgs = array();
        foreach ($orgs as $elemento) {
            array_push(
                $stat_orgs, 
                array(
                    'orgs' => $elemento->getNombre(),
                    'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\ParticipacionComunitaria::class)->existenOrganizaciones($elemento)))
            );
        }

        $participacion = $em->getRepository(\SICBundle\Entity\AdminRespCerrada::class)->findAll();
        $stat_participacion = array();
        foreach ($participacion as $elemento) {
            array_push(
                $stat_participacion, 
                array(
                    'participacion' => $elemento->getRespuesta(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\ParticipacionComunitaria::class)->findBy(
                                        array('participaOrganizacion' => $elemento->getId())
                                        ))
                    )
            );
        }

        $parte_miembros = $em->getRepository(\SICBundle\Entity\AdminRespCerrada::class)->findAll();
        $stat_parte_miembros = array();
        foreach ($parte_miembros as $elemento) {
            array_push(
                $stat_parte_miembros, 
                array(
                    'parte_miembros' => $elemento->getRespuesta(),
                    'cantidad'     => sizeof($em->getRepository(\SICBundle\Entity\ParticipacionComunitaria::class)->findBy(
                                        array('participaMiembroOrganizacion' => $elemento->getId())
                                        ))
                    )
            );
        }

        $misiones = $em->getRepository(\SICBundle\Entity\AdminMisionesComunidad::class)->findAll();
        $stat_misiones = array();
        foreach ($misiones as $elemento) {
            array_push(
                $stat_misiones, 
                array(
                    'misiones' => $elemento->getNombre(),
                    'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\ParticipacionComunitaria::class)->misionesComunidad($elemento)))
            );
        }

        $areatrabajo = $em->getRepository(\SICBundle\Entity\AdminAreaTrabajoCC::class)->findAll();
        $stat_areatrabajo = array();
        foreach ($areatrabajo as $elemento) {
            array_push(
                $stat_areatrabajo, 
                array(
                    'areatrabajo' => $elemento->getNombre(),
                    'cantidad' => sizeof($em->getRepository(\SICBundle\Entity\ParticipacionComunitaria::class)->areaTabajoCC($elemento)))
            );
        }

        return array(
            'stat_areatrabajo' => $stat_areatrabajo,
            'stat_misiones' => $stat_misiones,
            'stat_orgs' => $stat_orgs,
            'stat_participacion' => $stat_participacion,
            'stat_parte_miembros' => $stat_parte_miembros,
            'total' => $total,
        );
    }
    /**
     * Lists all ParticipacionComunitaria entities.
     *
     */
    public function indexAction()
    {
        $stats = $this->obtenerStats();
        return $this->render('participacioncomunitaria/index.html.twig', $stats);
    }

    /**
     * Creates a new ParticipacionComunitaria entity.
     *
     */
    public function newAction(Request $request, $id_planilla)
    {
        /*Redireccionar cuando se accede por GET y evitar que se cree una nueva para la misma planilla*/
        $em = $this->getDoctrine()->getManager();
        $planilla = $em->getRepository(\SICBundle\Entity\Planillas::class)->findById($id_planilla);
        $p = $planilla[0];

        if($p->getParticipacionComunitaria() != NULL){
            $this->addFlash('error', 'Seleccione la sección que desea modificar');
            return $this->redirectToRoute('planillas_show', array('id' => $id_planilla));
        }

        $participacionComunitarium = new ParticipacionComunitaria();
        $form = $this->createForm('SICBundle\Form\ParticipacionComunitariaType', $participacionComunitarium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($participacionComunitarium);
            $p->setParticipacionComunitaria($participacionComunitarium);
            $p->setTerminada('85');
            $em->persist($p);
            $this->addFlash('success', 'Se han agregado una Participación Comunitaria');
            $entrada = new Bitacora($this->getUser(),'agregó','información de Participación Comunitaria a la planilla '.$id_planilla);
            $em->persist($entrada);
            $em->flush();

            return $this->redirectToRoute('situacioncomunidad_new', array('id_planilla' => $id_planilla));
        }

        return $this->render('participacioncomunitaria/new.html.twig', array(
            'participacionComunitarium' => $participacionComunitarium,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ParticipacionComunitaria entity.
     *
     */
    public function showAction(ParticipacionComunitaria $participacionComunitarium)
    {
        $deleteForm = $this->createDeleteForm($participacionComunitarium);

        return $this->render('participacioncomunitaria/show.html.twig', array(
            'participacionComunitarium' => $participacionComunitarium,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ParticipacionComunitaria entity.
     *
     */
    public function editAction(Request $request, ParticipacionComunitaria $participacionComunitarium)
    {
        $deleteForm = $this->createDeleteForm($participacionComunitarium);
        $editForm = $this->createForm('SICBundle\Form\ParticipacionComunitariaType', $participacionComunitarium);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $questions = $participacionComunitarium->getPreguntasParticipacionComunitaria();
            foreach ($questions as $q) {
                if ($q->getInterrogante() == '') {
                    $this->addFlash('warning', 'No puede haber una Interrogante en blanco. A continuación eliga la pregunta y coloque su respuesta.');
                    return $this->render('participacioncomunitaria/edit.html.twig', array(
                        'participacionComunitarium' => $participacionComunitarium,
                        'edit_form' => $editForm->createView(),
                        'delete_form' => $deleteForm->createView(),
                    ));
                }
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($participacionComunitarium);
            $bitacora = new Bitacora($this->getUser(),'modificó','La Participación Comunitaria de la planilla '.$participacionComunitarium->getPlanilla()->getId());
            $em->persist($bitacora);
            $em->flush();

            $this->addFlash('success', 'Se ha modificado la información de participación comunitaria de forma correcta');
            
            return $this->redirectToRoute('planillas_show', array('id' => $participacionComunitarium->getPlanilla()->getId()));
        }

        return $this->render('participacioncomunitaria/edit.html.twig', array(
            'participacionComunitarium' => $participacionComunitarium,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ParticipacionComunitaria entity.
     *
     */
    public function deleteAction(Request $request, ParticipacionComunitaria $participacionComunitarium)
    {
        $form = $this->createDeleteForm($participacionComunitarium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($participacionComunitarium);
            $this->addFlash('success', 'Se han eliminado información de Participación Comunitaria');
            $entrada = new Bitacora($this->getUser(),'eliminó','información de Participación Comunitaria');
            $em->persist($entrada);
            $em->flush();
        }

        return $this->redirectToRoute('participacioncomunitaria_index');
    }

    /**
     * Creates a form to delete a ParticipacionComunitaria entity.
     *
     * @param ParticipacionComunitaria $participacionComunitarium The ParticipacionComunitaria entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ParticipacionComunitaria $participacionComunitarium)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('participacioncomunitaria_delete', array('id' => $participacionComunitarium->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SICBundle\Entity\Planillas;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\PlanillasType;

/**
 * Planillas controller.
 *
 */
class PlanillasController extends Controller
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

            // $em->remove($planilla);
            // $em->flush();
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
        $em->flush();
        $this->get('session')->getFlashBag()
        ->add('success', 'Se ha registrado la observación');
    }

    public function conitnuarEncuestaAction($id_encuesta = null)
    {
        $em = $this->getDoctrine()->getManager();
        $planilla = $em->getRepository('SICBundle:Planillas')->findById($id_encuesta);

        if (sizeof($planilla) == 1) {
            $planilla = $planilla[0];
            
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
                                            $this->get('session')->getFlashBag()
                                            ->add('success', 'La planilla ya está completa');
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

        $this->get('session')->getFlashBag()
        ->add('danger', 'La planilla que indicó no existe.');
        return $this->redirectToRoute('planillas_index');
    }
}

<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Comunicado;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\ComunicadoType;

/**
 * Comunicado controller.
 *
 */
class ComunicadoController extends BaseController
{
    /**
     * Lists all Comunicado entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $comunicados = $em->getRepository(\SICBundle\Entity\Comunicado::class)->findAll();

        return $this->render('comunicado/index.html.twig', array(
            'comunicados' => $comunicados,
        ));
    }

    /**
     * Creates a new Comunicado entity.
     *
     */
    public function newAction(Request $request)
    {
        $comunicado = new Comunicado();
        $form = $this->createForm(ComunicadoType::class, $comunicado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            $comunicado->setEmisor($this->getUser());
            $comunicado->setFecha(new \DateTime('now'));

            //obtener los usuarios a los que se enviará el correo y la cantidad
            $destinatarios = $em->getRepository(\SICBundle\Entity\Comunicado::class)->PersonasCorreo();
            $comunicado->setNumDestinatarios(sizeof($destinatarios));

            foreach ($destinatarios as $p) {
                $message = \Swift_Message::newInstance()
                    ->setSubject('Consejo Comunal')
                    ->setFrom(array('gpscaracas@registro1991.com.ve' => 'Consejo Comunal'))
                    ->setTo($p->getEmail())
                    ->setBody(
                        $this->renderView(
                            'comunicado/comunicadomasivo.html.twig',array(
                                'titulo' => $comunicado->getTitulo(),
                                'cuerpo' => $comunicado->getCuerpo(),
                                'correo' => $p->getEmail(),
                            )
                        ),
                        'text/html'
                    );
                $this->get('mailer')->send($message);
            }

            $em->persist($comunicado);
            $bitacora = new Bitacora($this->getUser(),'envió','un comunicado másivo a '.sizeof($destinatarios).' personas.');
            $em->persist($bitacora);
            $em->flush();

            $this->addFlash('success', 'Se ha enviado el mensaje a los distintos usuarios.');
            return $this->redirectToRoute('noticia_correo');
        }

        return $this->render('comunicado/new.html.twig', array(
            'comunicado' => $comunicado,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Comunicado entity.
     *
     */
    public function showAction(Comunicado $comunicado)
    {
        $deleteForm = $this->createDeleteForm($comunicado);

        return $this->render('comunicado/show.html.twig', array(
            'comunicado' => $comunicado,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Comunicado entity.
     *
     */
    public function editAction(Request $request, Comunicado $comunicado)
    {
        $deleteForm = $this->createDeleteForm($comunicado);
        $editForm = $this->createForm(ComunicadoType::class, $comunicado);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($comunicado);
            $em->flush();

            return $this->redirectToRoute('comunicado_edit', array('id' => $comunicado->getId()));
        }

        return $this->render('comunicado/edit.html.twig', array(
            'comunicado' => $comunicado,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Comunicado entity.
     *
     */
    public function deleteAction(Request $request, Comunicado $comunicado)
    {
        $form = $this->createDeleteForm($comunicado);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($comunicado);
            $em->flush();
        }

        return $this->redirectToRoute('comunicado_index');
    }

    /**
     * Creates a form to delete a Comunicado entity.
     *
     * @param Comunicado $comunicado The Comunicado entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Comunicado $comunicado)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('comunicado_delete', array('id' => $comunicado->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /* Desactiva las notificaciones de correo/comunicados para una persona */
    public function desactivarSubscripcionAction($correo)
    {
        $em = $this->getDoctrine()->getManager();
        $persona = $em->getRepository(\SICBundle\Entity\Persona::class)->findBy(array('email' => $correo));
        if (sizeof($persona)>0) {
            $p = $persona[0];
            $p->setRecibirCorreo(false); //No recibirá correos
            $em->persist($p);
        }else{
            $persona = $em->getRepository(\SICBundle\Entity\JefeGrupoFamiliar::class)->findBy(array('email' => $correo));
            if (sizeof($persona)>0) {
                $p = $persona[0];
                $p->setRecibirCorreo(false); //No recibirá correos
                $em->persist($p);
            }
        }
        $em->flush();
        return $this->redirectToRoute('comunicado_no_recibir_estatus', array('correo' => $correo));
    }

    /* Vista a la que se va, despues de deshabilitar la subcripción de correo para una persona */
    public function subscripcionEstatusAction($correo)
    {
        $em = $this->getDoctrine()->getManager();
        $noticias = $em->getRepository(\SICBundle\Entity\Noticia::class)->NoticiasOrdenDesc();
        return $this->render('comunicado/subscripcion_estatus.html.twig', array(
            'correo' => $correo,
            'noticias' => $noticias,
        ));
    }
}

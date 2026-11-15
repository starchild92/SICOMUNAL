<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Bitacora;
use SICBundle\Form\BitacoraType;

/**
 * Bitacora controller.
 *
 */
class BitacoraController extends BaseController
{
    /**
     * Lists all Bitacora entities.
     *
     */
    public function indexAction()
    {   
        $ini = new \DateTime('today');
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository(\SICBundle\Entity\Bitacora::class)->findRango($ini->format('Y-m-d').' 00:00:00',$ini->format('Y-m-d').' 23:59:59', 'todos');
        return $this->render('bitacora/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    public function porAction(Request $request)
    {
        $form = $request->request->all();

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository(\SICBundle\Entity\Bitacora::class)->findRango($form['fechaInicio'].' 00:00:00', $form['fechaFinal'].' 23:59:59', $form['criteria']);
        return $this->render('bitacora/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new Bitacora entity.
     *
     */
    public function newAction(Request $request)
    {
        $bitacora = new Bitacora();
        $form = $this->createForm(BitacoraType::class, $bitacora);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('bitacora_show', array('id' => $bitacora->getId()));
        }

        return $this->render('bitacora/new.html.twig', array(
            'bitacora' => $bitacora,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Bitacora entity.
     *
     */
    public function showAction(Bitacora $bitacora)
    {
        $deleteForm = $this->createDeleteForm($bitacora);

        return $this->render('bitacora/show.html.twig', array(
            'bitacora' => $bitacora,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Bitacora entity.
     *
     */
    public function editAction(Request $request, Bitacora $bitacora)
    {
        $deleteForm = $this->createDeleteForm($bitacora);
        $editForm = $this->createForm(BitacoraType::class, $bitacora);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($bitacora);
            $em->flush();

            return $this->redirectToRoute('bitacora_edit', array('id' => $bitacora->getId()));
        }

        return $this->render('bitacora/edit.html.twig', array(
            'bitacora' => $bitacora,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Bitacora entity.
     *
     */
    public function deleteAction(Request $request, Bitacora $bitacora)
    {
        $form = $this->createDeleteForm($bitacora);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('bitacora_index');
    }

    /**
     * Creates a form to delete a Bitacora entity.
     *
     * @param Bitacora $bitacora The Bitacora entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Bitacora $bitacora)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('bitacora_delete', array('id' => $bitacora->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

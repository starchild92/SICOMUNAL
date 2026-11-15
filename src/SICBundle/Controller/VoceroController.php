<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\Vocero;
use SICBundle\Form\VoceroType;

/**
 * Vocero controller.
 *
 */
class VoceroController extends BaseController
{
    /**
     * Lists all Vocero entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $voceros = $em->getRepository(\SICBundle\Entity\Vocero::class)->findAll();

        return $this->render('vocero/index.html.twig', array(
            'voceros' => $voceros,
        ));
    }

    /**
     * Creates a new Vocero entity.
     *
     */
    public function newAction(Request $request)
    {
        $vocero = new Vocero();
        $form = $this->createForm(VoceroType::class, $vocero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vocero);
            $em->flush();

            return $this->redirectToRoute('vocero_show', array('id' => $vocero->getId()));
        }

        return $this->render('vocero/new.html.twig', array(
            'vocero' => $vocero,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Vocero entity.
     *
     */
    public function showAction(Vocero $vocero)
    {
        $deleteForm = $this->createDeleteForm($vocero);

        return $this->render('vocero/show.html.twig', array(
            'vocero' => $vocero,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Vocero entity.
     *
     */
    public function editAction(Request $request, Vocero $vocero)
    {
        $deleteForm = $this->createDeleteForm($vocero);
        $editForm = $this->createForm(VoceroType::class, $vocero);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($vocero);
            $em->flush();

            return $this->redirectToRoute('vocero_edit', array('id' => $vocero->getId()));
        }

        return $this->render('vocero/edit.html.twig', array(
            'vocero' => $vocero,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Vocero entity.
     *
     */
    public function deleteAction(Request $request, Vocero $vocero)
    {
        $form = $this->createDeleteForm($vocero);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($vocero);
            $em->flush();
        }

        return $this->redirectToRoute('vocero_index');
    }

    /**
     * Creates a form to delete a Vocero entity.
     *
     * @param Vocero $vocero The Vocero entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Vocero $vocero)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('vocero_delete', array('id' => $vocero->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

<?php

namespace SICBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SICBundle\Controller\BaseController;

use SICBundle\Entity\ConsejoComunal;
use SICBundle\Entity\Bitacora;
use SICBundle\Form\ConsejoComunalType;

/**
 * ConsejoComunal controller.
 *
 */
class ConsejoComunalController extends BaseController
{
    /**
     * Lists all ConsejoComunal entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $consejoComunals = $em->getRepository(\SICBundle\Entity\ConsejoComunal::class)->findAll();

        return $this->render('consejocomunal/index.html.twig', array(
            'consejoComunals' => $consejoComunals,
        ));
    }

    /**
     * Creates a new ConsejoComunal entity.
     *
     */
    public function newAction(Request $request)
    {
        $consejoComunal = new ConsejoComunal();
        $form = $this->createForm('SICBundle\Form\ConsejoComunalType', $consejoComunal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($consejoComunal);
            $bitacora = new Bitacora($this->getUser(),'agregó','la información del Consejo Comunal.');
            $em->persist($bitacora);
            $em->flush();

            $this->addFlash('success', 'Ha agregado la información basé del Consejo Comunal de forma exitosa.');

            return $this->redirectToRoute('sic_administacion_entidades');
        }

        return $this->render('consejocomunal/new.html.twig', array(
            'consejoComunal' => $consejoComunal,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a ConsejoComunal entity.
     *
     */
    public function showAction(ConsejoComunal $consejoComunal)
    {
        $deleteForm = $this->createDeleteForm($consejoComunal);

        return $this->render('consejocomunal/show.html.twig', array(
            'consejoComunal' => $consejoComunal,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ConsejoComunal entity.
     *
     */
    public function editAction(Request $request, ConsejoComunal $consejoComunal)
    {
        $deleteForm = $this->createDeleteForm($consejoComunal);
        $editForm = $this->createForm('SICBundle\Form\ConsejoComunalType', $consejoComunal);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($consejoComunal);

            $bitacora = new Bitacora($this->getUser(),'modificó','la información del Consejo Comunal.');
            $em->persist($bitacora);

            $em->flush();

            $this->addFlash('success', 'Se ha actualizado la información del Consejo Comunal de forma exitosa.');

            return $this->redirectToRoute('sic_administacion_entidades');
        }

        return $this->render('consejocomunal/edit.html.twig', array(
            'consejoComunal' => $consejoComunal,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ConsejoComunal entity.
     *
     */
    public function deleteAction(Request $request, ConsejoComunal $consejoComunal)
    {
        $form = $this->createDeleteForm($consejoComunal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($consejoComunal);
            
            $this->addFlash('success', 'Se ha eliminado la información del Consejo Comunal de forma exitosa.');
            $bitacora = new Bitacora($this->getUser(),'eliminó','la información del Consejo Comunal.');
            $em->persist($bitacora);
            $em->flush();
        }

        return $this->redirectToRoute('consejocomunal_index');
    }

    /**
     * Creates a form to delete a ConsejoComunal entity.
     *
     * @param ConsejoComunal $consejoComunal The ConsejoComunal entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ConsejoComunal $consejoComunal)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('consejocomunal_delete', array('id' => $consejoComunal->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}

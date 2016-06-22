<?php

namespace SICBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanillasType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('empadronador')
            ->add('jefeGrupoFamiliar')
            ->add('miembrosGrupoFamiliar')
            ->add('situacionEconomica')
            ->add('situacionVivienda')
            ->add('situacionSalud')
            ->add('situacionServicios')
            ->add('participacionComunitaria')
            ->add('situacionComunidad')
            ->add('observaciones')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SICBundle\Entity\Planillas'
        ));
    }
}

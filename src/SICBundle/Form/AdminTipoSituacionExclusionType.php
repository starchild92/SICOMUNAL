<?php

namespace SICBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AdminTipoSituacionExclusionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('situacion', EntityType::class, array(
                'class'     => 'SICBundle:AdminTipoSituacion',
                'label'     => 'Tipo Situación',
                'placeholder'   => 'Seleccione una',
                'attr'  =>  array(
                    'class' => 'ui dropdown')))
            ->add('cantidad')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SICBundle\Entity\AdminTipoSituacionExclusion'
        ));
    }
}

<?php

namespace SICBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use SICBundle\Form\AdminTipoSituacionExclusionType;

class SituacionSaludType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('padecencia', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminTipoPadecencia::class,
                'label'     => 'Existen En Su Núcleo Familiar Personas Que Padezcan De:',
                'placeholder'   => 'Seleccione todas las que apliquen',
                'required'      => false,
                'multiple' => true,
                'attr'  =>  array(
                    'multiple' => '',
                    'class' => 'fluid multiple search selection')))

            ->add('ayudaEspecial', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminTipoAyudaEspecial::class,
                'label'     => '¿Necesita Usted De Alguna Ayuda Especial Para Familiares Enfermos En Su Hogar? - ¿Cuál(es)?',
                'placeholder'   => 'Escriba las que aplican',
                'required'      => false,
                'multiple' => true,
                'attr'  =>  array(
                    'multiple' => '',
                    'class' => 'fluid multiple search selection')))

            ->add('situacionExclusion', CollectionType::class,array(
                    'required' => false,
                    'entry_type' => AdminTipoSituacionExclusionType::class,
                    'attr' => array('class' => 'exclusiones'),
                    'allow_add'=>'true',
                    'by_reference'=>'false',
                    'allow_delete' =>'true',
                    'data_class' => null,
                    'label'     => '¿Situación de Exclusión?'
                ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SICBundle\Entity\SituacionSalud'
        ));
    }
}

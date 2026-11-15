<?php

namespace SICBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SituacionViviendaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cantidadHabitaciones', TextType::class,array(
                'label'     => '¿Cantidad de habitaciones con las que dispone en su Hogar?'))

            ->add('tipo', EntityType::class, array(
                'class' => 'SICBundle:AdminTipoVivienda',
                'placeholder'   => 'Seleccione una',
                'label'     => '¿Su vivienda es ...?',
                'attr'  =>  array(
                    'class' => 'ui dropdown')))

            ->add('tenencia', EntityType::class, array(
                'class' => 'SICBundle:AdminTipoTenencia',
                'placeholder'   =>  'Seleccione una',
                'label'     => 'Forma Tenencia',
                'attr'  =>  array(
                    'class' => 'ui dropdown')))

            ->add('terrenoPropio', EntityType::class, array(
                'class' => 'SICBundle:AdminRespCerrada',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'respuesta',
                'attr'  =>  array(
                    'class' => 'ui dropdown')))

            ->add('ovc', EntityType::class, array(
                'class' => 'SICBundle:AdminRespCerrada',
                'label'   => 'Organizaciones Civiles de Viviendas (OCV)',
                'placeholder'   => '¿Pertenece a una OCV?',
                'choice_label' => 'respuesta',
                'attr'  =>  array(
                    'class' => 'ui dropdown'))
            )

            ->add('habitaciones', EntityType::class, array(
                'class' => 'SICBundle:AdminTipoHabitacionesVivienda',
                'multiple' => true,
                'label'     => '¿Cuales tipo de Habitaciones componen su hogar?',
                'attr'  =>  array(
                    'multiple' => '',
                    'class' => 'fluid multiple search selection')))

            ->add('paredes', EntityType::class, array(
                'class' => 'SICBundle:AdminTipoParedes',
                'placeholder'       => 'Seleccione una',
                'attr'  =>  array(
                    'class' => 'ui dropdown')))

            ->add('techo', EntityType::class, array(
                'class' => 'SICBundle:AdminTipoTecho',
                'placeholder'       => 'Seleccione una',
                'attr'  =>  array(
                    'class' => 'ui dropdown')))

            ->add('enseres', EntityType::class, array(
                'class' => 'SICBundle:AdminTipoEnseres',
                'multiple' => true,
                'attr'  =>  array(
                    'multiple' => '',
                    'class' => 'fluid multiple search selection')))

            ->add('salubridad', EntityType::class, array(
                'class' => 'SICBundle:AdminSalubridadVivienda',
                'placeholder'       => 'Seleccione una',
                'attr'  =>  array(
                    'class' => 'ui dropdown')))

            ->add('presenciaInsectos', EntityType::class, array(
                'class' => 'SICBundle:AdminTipoPlagas',
                'multiple' => true,
                'required'  => false,
                'attr'  =>  array(
                    'multiple' => '',
                    'class' => 'fluid multiple search selection')))

            ->add('mascota', EntityType::class, array(
                'class' => 'SICBundle:AdminTipoMascotas',
                'multiple' => true,
                'required'  => false,
                'attr'  =>  array(
                    'multiple' => '',
                    'class' => 'fluid multiple search selection')))

            ->add('leypoliticahabitacional', EntityType::class, array(
                'label' => '¿Cotiza Ley Política Habitacional?',
                'class' => 'SICBundle:AdminRespCerrada',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'respuesta',
                'attr'  =>  array(
                    'class' => 'ui dropdown')))

            ->add('sivih', EntityType::class, array(
                'label' => '¿Está inscrita en el SIVIH?',
                'class' => 'SICBundle:AdminRespCerrada',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'respuesta',
                'attr'  =>  array(
                    'class' => 'ui dropdown')))

            ->add('condicionesTerreno', EntityType::class, array(
                'label' => 'Condiciones del Terreno',
                'class' => 'SICBundle:AdminTipoCondicionTerreno',
                'placeholder' => 'Selecciona una',
                'attr'  =>  array(
                    'class' => 'ui dropdown')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SICBundle\Entity\SituacionVivienda'
        ));
    }
}

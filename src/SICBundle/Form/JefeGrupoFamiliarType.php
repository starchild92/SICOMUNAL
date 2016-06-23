<?php

namespace SICBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use SICBundle\Form\GeneroType;

class JefeGrupoFamiliarType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombres')
            ->add('apellidos')
            ->add('cedula')
            ->add('nacionalidad', EntityType::class, array(
                // query choices from this entity
                'class' => 'SICBundle:AdminNacionalidad',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'Nacionalidad',

                // used to render a select box, check boxes or radios
                 // 'multiple' => true,
                 // 'expanded' => true,
            ))
            ->add('fechaNacimiento', 'date')
            ->add('edad') //Se calcula automaticamente
            ->add('cne', EntityType::class, array(
                // query choices from this entity
                'class' => 'SICBundle:AdminRespCerrada',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'respuesta',
            ))
            ->add('tiempoEnComunidad')
            ->add('sexo', GeneroType::class, array(
                'placeholder' => 'Elija uno'))
            ->add('incapacitado', EntityType::class, array(
                'class' => 'SICBundle:AdminRespCerrada',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'respuesta',
            ))
            ->add('incapacitadoTipo', EntityType::class, array(
                'class' => 'SICBundle:AdminIncapacidades',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'incapacidad',
            ))
            ->add('pensionado', EntityType::class, array(
                'class' => 'SICBundle:AdminRespCerrada',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'respuesta',
            ))
            ->add('pensionadoInstitucion', EntityType::class, array(
                'class' => 'SICBundle:AdminPensionadoInstitucion',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'nombre',
            ))
            ->add('estadoCivil', EntityType::class, array(
                'class' => 'SICBundle:AdminEstadoCivil',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'nombre',
            ))
            ->add('nivelInstruccion', EntityType::class, array(
                'class' => 'SICBundle:AdminNivelInstruccion',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'nombre',
            ))
            ->add('profesion', EntityType::class, array(
                'class' => 'SICBundle:AdminProfesion',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'nombre',
            ))
            ->add('trabajaActualmente', EntityType::class, array(
                'class' => 'SICBundle:AdminRespCerrada',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'respuesta',
            ))
            ->add('telefono')
            ->add('email')
            ->add('ingresoFamiliar', EntityType::class, array(
                'class' => 'SICBundle:AdminClasIngresoFamiliar',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'nombre',
            ))
            ->add('ingresoMensual')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SICBundle\Entity\JefeGrupoFamiliar'
        ));
    }
}

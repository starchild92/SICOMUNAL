<?php

namespace SICBundle\Form;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

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
            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                array($this, 'onPreSubmit')
            )

            ->add('nombres')
            ->add('apellidos')
            
            ->add('embarazada', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminRespCerrada::class,
                'placeholder' => 'Seleccione',
            ))

            ->add('cedula', TextType::class, array(
                'label' => 'Cédula de Identidad'))
            
            ->add('nacionalidad', EntityType::class, array(
                // query choices from this entity
                'class' => \SICBundle\Entity\AdminNacionalidad::class,
                'placeholder' => 'Selecciona una',
                'choice_label' => 'Nacionalidad',

                // used to render a select box, check boxes or radios
                 // 'multiple' => true,
                 // 'expanded' => true,
            ))
            ->add('fechaNacimiento', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'label' => 'Fecha de Nacimiento',
                'attr' => ['class' => 'js-datepicker', 'placeholder' => 'AAAA-MM-DD'],
            ))

            ->add('edad', TextType::class,array(
                'attr' => array(
                'readonly' => true))) //Se calcula automaticamente

            ->add('cne', EntityType::class, array(
                'label'     => '¿Está inscrito en el CNE?',
                'class' => \SICBundle\Entity\AdminRespCerrada::class,
                'placeholder' => 'Selecciona una',
                'choice_label' => 'respuesta',
            ))
            ->add('tiempoEnComunidad', TextType::class, array(
                'label' => 'Tiempo en la Comunidad'))
            ->add('sexo', GeneroType::class, array(
                'placeholder' => 'Elija uno'))

            ->add('incapacitado', ChoiceType::class, array(
                'label' => '¿Es Discapacitado?',
                'choices'  => array(
                    'Seleccione' => '',
                    'Si' => 'si',
                    'No' => 'no',
                    // 'Otro' => 'otro'
                ),
            ))
            
            ->add('incapacitadoTipo', EntityType::class, array(
                'label' => '¿Cúal es la discapacidad que padece?',
                'class' => \SICBundle\Entity\AdminIncapacidades::class,
                'placeholder' => 'Selecciona una',
                'choice_label' => 'incapacidad',
                'attr'      => array(
                    'required'  => false,
                    // 'class' => 'ui fluid search dropdown'
                    )
            ))
            
            ->add('pensionado', ChoiceType::class, array(
                'label' => '¿Es pensionado?',
                'choices'  => array(
                    'Seleccione' => '',
                    'Si' => 'si',
                    'No' => 'no',
                    // 'Otro' => 'otro'
                ),
            ))
            
            ->add('pensionadoInstitucion', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminPensionadoInstitucion::class,
                'label' => 'Institución Pensionado',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'nombre',
                'attr'      => array(
                    // 'class' => 'ui dropdown',
                    'required'  => false)
            ))
            
            ->add('estadoCivil', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminEstadoCivil::class,
                'label' => 'Estado Civil',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'nombre',
                'attr'      => array(
                    'class' => 'ui dropdown',)
            ))
            
            ->add('nivelInstruccion', EntityType::class, array(
                'label' => '¿Cúal es el Nivel de Instrucción?',
                'class' => \SICBundle\Entity\AdminNivelInstruccion::class,
                'placeholder' => 'Selecciona una',
                'choice_label' => 'nombre',
                'attr' => array(
                    'class' => 'ui dropdown')
            ))
            
            ->add('profesion', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminProfesion::class,
                'label' => 'Profesión',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'nombre',
                'attr'      => array(
                    'required'  => false,
                    'class' => 'ui fluid search dropdown')
            ))
            
            ->add('trabajaActualmente', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminRespCerrada::class,
                'label' => '¿Trabaja actualmente?',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'respuesta',
            ))

            ->add('telefono', CollectionType::class, array(
                'required' => false,
                'entry_type' => TelefonoType::class,
                'attr' => array('class' => 'telefonos'),
                'allow_add'=>'true',
                'by_reference'=>'false',
                'allow_delete' =>'true',
                'data_class' => null,
                'label' => 'Número(s) teléfonicos',
            ))

            ->add('email', TextType::class, array(
                'label'     => 'Correo Electrónico',
                'required'  => false,
            ))

            ->add('ingresoFamiliar', EntityType::class, array(
                'label' => 'Clasificación del Ingreso Familiar',
                'class' => \SICBundle\Entity\AdminClasIngresoFamiliar::class,
                'placeholder' => 'Selecciona una',
                'choice_label' => 'nombre',
            ))
            
            ->add('ingresoMensual', TextType::class, array(
                'label' => 'Estimado de Ingreso Mensual',
                'attr' => array(
                    'value' => 0)
                ))
        ;
    }

    public function onPreSubmit(\Symfony\Component\Form\FormEvent $event)
    {
        $jgf = $event->getData();
        // $form = $event->getForm();

        $jgf['tiempoEnComunidad'] = filter_var($jgf['tiempoEnComunidad'], FILTER_SANITIZE_NUMBER_INT);
        $jgf['cedula'] = filter_var($jgf['cedula'], FILTER_SANITIZE_NUMBER_INT);
        
        $event->setData($jgf); //Para guardar los cambios
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

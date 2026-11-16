<?php

namespace SICBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use SICBundle\Form\GeneroType;
use SICBundle\Form\TelefonoType;

class PersonaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class,array(
                'label'     => 'Nombre(s)'))
            ->add('apellido', TextType::class,array(
                'label'     => 'Apellidos(s)'))

            ->add('sexo', GeneroType::class, array(
                'placeholder' => 'Elija uno',
                // 'attr' => array('class' => 'ui dropdown')
                ))

            ->add('nacionalidad', EntityType::class, array(
                // query choices from this entity
                'class' => \SICBundle\Entity\AdminNacionalidad::class,
                'placeholder' => 'Selecciona una',
                'choice_label' => 'Nacionalidad',

                // used to render a select box, check boxes or radios
                 // 'multiple' => true,
                 // 'expanded' => true,
            ))
            
            ->add('cedula', TextType::class, array(
                'label'     => 'Cédula de Identidad',
                'required' => false,))
            
            ->add('email', TextType::class,array(
                'required' => false,
                'label' => 'Correo Electronico',
                'attr' => array(
                    // 'required' => false,
                    'placeholder' => 'example@mail.com')))
            
            ->add('fechaNacimiento', DateType::class, array(
                'widget'    => 'single_text',
                'html5'     => false,
                'attr'      => ['class' => 'js-datepicker', 'placeholder' => 'AÑO-MES-DIA'],
                'label'     => 'Fecha de Nacimiento'
            ))

            ->add('edad', TextType::class, array(
                'attr' => array(
                    'readonly' => true)))

            ->add('parentesco', TextType::class, array(
                'label'     => 'Parentesco que guarda con el Jefe del Grupo Familiar'))
            
            ->add('gradoInstruccion', EntityType::class, array(
                'label' => '¿Cúal es su Nivel de Instrucción?',
                'class' => \SICBundle\Entity\AdminNivelInstruccion::class,
                'placeholder' => 'Selecciona una',
                'choice_label' => 'nombre',
                'attr'      => array(
                    'class' => 'ui search dropdown')
            ))

            ->add('profesion', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminProfesion::class,
                'placeholder' => 'Selecciona una',
                'choice_label' => 'nombre',
                'attr'      => array(
                    'class' => 'search additions',
                    'required'  => false),
                'label'     => 'Indique una Profesión'
            ))

            ->add('cne', EntityType::class, array(
                'label'     => '¿Está inscrito en el CNE?',
                'class' => \SICBundle\Entity\AdminRespCerrada::class,
                'placeholder' => 'Selecciona una',
                'choice_label' => 'respuesta',
            ))

            ->add('embarazoTemprano', EntityType::class, array(
                'label'     => '¿Embarazo Temprano?',
                'class' => \SICBundle\Entity\AdminRespCerrada::class,
                'placeholder' => 'Selecciona una',
                'choice_label' => 'respuesta',
            ))

            ->add('incapacitado', ChoiceType::class, array(
                'label' => 'Discapacidad',
                'choices'  => array(
                    'Seleccione una' => '',
                    'Si' => 'si',
                    'No' => 'no',
                    // 'Otro' => 'otro'
                ),
            ))
            ->add('incapacitadoTipo', EntityType::class, array(
                'label' => 'Tipo de Discapacidad',
                'class' => \SICBundle\Entity\AdminIncapacidades::class,
                'placeholder' => 'Selecciona una',
                'choice_label' => 'incapacidad',
                'attr'      => array(
                    // 'class' => 'ui dropdown',
                    'required'  => false)
            ))

            ->add('pensionado', ChoiceType::class, array(
                'label' => '¿Es Pensionado?',
                'choices'  => array(
                    'Seleccione una' => '',
                    'Si' => 'si',
                    'No' => 'no',
                    // 'Otro' => 'otro'
                ),
            ))
            
            ->add('pensionadoInstitucion', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminPensionadoInstitucion::class,
                'placeholder' => 'Selecciona una',
                'choice_label' => 'nombre',
                'attr'      => array(
                    // 'class' => 'ui dropdown',
                    'required'  => false)
            ))

            ->add('ingresoMensual', TextType::class, array(
                'label' => 'Estimado de Ingreso Mensual',
                'attr' => array(
                    'value' => 0)
                ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SICBundle\Entity\Persona'
        ));
    }
}

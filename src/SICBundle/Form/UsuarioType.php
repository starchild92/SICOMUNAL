<?php

namespace SICBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use SICBundle\Form\TelefonoType;

class UsuarioType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $permissions = array(
            'Administrador' => 'ROLE_ADMIN',
            'Empadronador'  => 'ROLE_USER',
        );
        
        $builder
            ->add('email', EmailType::class, array('label' => 'Correo Electrónico'))
            ->add('username', TextType::class, array('label' => 'Nombre de Usuario'))
            ->add('plainPassword', RepeatedType::class, array(
                'type'            => PasswordType::class,
                'mapped'          => false,
                'required'        => true,
                'first_options'   => array('label' => 'Contraseña'),
                'second_options'  => array('label' => 'Repita Contraseña'),
            ))
            ->add('primerNombre')
            ->add('segundoNombre', TextType::class, array(
                'label'     => 'Segundo Nombre',
                'required'  => false,
            ))
            ->add('primerApellido')
            ->add('segundoApellido', TextType::class, array(
                'label'     => 'Segundo Apellido',
                'required'  => false,
            ))
            ->add('cedula')
            ->add('fechaNacimiento', DateType::class, array(
                'widget' => 'single_text',
                'html5' => false,
                'label' => 'Fecha de Nacimiento',
                'attr' => ['class' => 'js-datepicker', 'placeholder' => 'AAAA-MM-DD'],
            ))
            ->add('telefono', CollectionType::class, array(
                        'required' => false,
                        'entry_type' => TelefonoType::class,
                        'attr' => array('class' => 'telefonos'),
                        'allow_add' => true,
                        'by_reference' => false,
                        'allow_delete' => true,
                        'label' => 'Número(s) teléfonicos',
                        ))
            ->add('enabled', ChoiceType::class, array(
                'label' => 'Estado de la Cuenta',
                'choices'   => array(
                    'Cuenta Activa'   => true,
                    'Cuenta Inactiva' => false,
                )))
            ->add('roles', ChoiceType::class,array(
                    'label'   => 'Elija el Rol',
                    'choices' => $permissions,
                    'multiple' => true,
                    'attr' => array(
                        'multiple' => '',
                        'class' => 'ui dropdown')
                ))
        ;
    }
    
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => 'SICBundle\Entity\Usuario'
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}

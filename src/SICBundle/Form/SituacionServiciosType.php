<?php

namespace SICBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SituacionServiciosType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('aguasBlancas', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminAguasBlancas::class,
                'placeholder'   => 'Seleccione una',
                'required'  => false,
                'label' => 'Aguas Blancas',
                'attr'  =>  array(
                    'class' => 'ui dropdown')))

            ->add('aguasServidas', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminAguasServidas::class,
                'placeholder'   => 'Seleccione una',
                'required'  => false,
                'label' => 'Aguas Servidas',
                'attr'  =>  array(
                    'class' => 'ui dropdown')))

            ->add('gas', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminTipoGas::class,
                'placeholder'   => 'Seleccione una',
                'label' => 'Gas',
                'attr'  =>  array(
                    'class' => 'ui dropdown')))

            ->add('sistemaElectrico', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminSistemaElectrico::class,
                'placeholder'   => 'Seleccione una',
                'required'  => false,
                'label' => 'Sistema Eléctrico',
                'attr'  =>  array(
                    'class' => 'ui dropdown')))

            ->add('recoleccionBasura', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminRecoleccionBasura::class,
                'required'  => false,
                'placeholder'   => 'Seleccione una',
                'label' => 'Sistema de Recolección de Basura',
                'attr'  =>  array(
                    'multiple' => '',
                    'class' => 'fluid multiple search selection')))

            ->add('telefonia', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminTipoTelefonia::class,
                'placeholder'   => 'Seleccione todas las que apliquen',
                'multiple' => true,
                'required'  => false,
                'label' => 'Telefonía',
                'attr'  =>  array(
                    'multiple' => '',
                    'class' => 'fluid multiple search selection')))

            ->add('transporte', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminTipoTransporte::class,
                'placeholder'   => 'Seleccione todas las que apliquen',
                'multiple' => true,
                'required'  => false,
                'label' => 'Sistema de transporte',
                'attr'  =>  array(
                    'multiple' => '',
                    'class' => 'fluid multiple search selection')))

            ->add('mecanismoInformacion', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminMecanismoInformacion::class,
                'placeholder'   => 'Seleccione todas las que apliquen',
                'label' => 'Mecanismos de Información',
                'multiple' => true,
                'required'  => false,
                'attr'  =>  array(
                    'multiple' => '',
                    'class' => 'fluid multiple search selection')))

            ->add('serviciosComunales', EntityType::class, array(
                'placeholder'   => 'Seleccione todas las que apliquen',
                'class' => \SICBundle\Entity\AdminServiciosComunales::class,
                'label' => 'Servicios Comunales',
                'multiple' => true,
                'required'  => false,
                'attr'  =>  array(
                    'multiple' => '',
                    'class' => 'fluid multiple search selection')))

            ->add('lts_tanque', IntegerType::class,array(
                'label'     => '¿Tanque? (Lts)',
                'required'  => false))

            ->add('cant_pipotes', IntegerType::class,array(
                'label'     => 'Cantidad de Pipotes',
                'required'  => false))

            ->add('medidor', EntityType::class, array(
                'label'     => '¿Tiene medidor de Aguas Blancas?',
                'placeholder'   => 'Elija una',
                'class' => \SICBundle\Entity\AdminRespCerrada::class))

            ->add('empresaGas', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminEmpresaGas::class,
                'placeholder'   => 'Seleccione una',
                'required'  => false,
                'label' => 'Proveedor de Gas',
                'attr'  =>  array(
                    'multiple' => '',
                    'class' => 'ui dropdown')))

            ->add('cantBombonas', IntegerType::class,array(
                'label'     => 'Cantidad de Cilindros',
                'required'  => false))

            ->add('capacidadBombona', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminCapacidadBombona::class,
                'required'  => false,
                'placeholder'   => 'Seleccione una',
                'label' => 'Capacidad Bombona',
                'attr'  =>  array(
                    'class' => 'ui dropdown')))

            ->add('medidorElectrico', EntityType::class, array(
                'label'     => '¿Tiene medidor electrico?',
                'placeholder'   => 'Elija una',
                'class' => \SICBundle\Entity\AdminRespCerrada::class))

            ->add('bombillosAhorradores', EntityType::class, array(
                'label'     => '¿Tiene Bombillos Ahorradores?',
                'placeholder'   => 'Elija una',
                'class' => \SICBundle\Entity\AdminRespCerrada::class))

            ->add('precioBombona', TextType::class,array(
                'label'     => 'Precio del Cilindro',
                'required'  => false))

            ->add('duracionBombona', TextType::class,array(
                'label'     => 'Duración del Gas',
                'required'  => false,
                'attr'      => array(
                    'placeholder' => 'Ejemplo. 15 días')))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SICBundle\Entity\SituacionServicios'
        ));
    }
}

<?php

namespace SICBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormEvents;
use SICBundle\Entity\AdminVentaVivienda;

class SituacionEconomicaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->addEventListener(
            //     FormEvents::PRE_SUBMIT,
            //     array($this, 'onPreSubmit')
            // )
            
            ->add('dondeTrabaja', EntityType::class, array(
                'class'     => 'SICBundle:AdminUbicacionTrabajo',
                'label'     => '¿Dónde Trabaja?',
                'placeholder' => 'Selecciona una',
                'required' => false,
                'attr'  =>  array(
                    'class' => 'ui fluid dropdown')))

            /*->add('actividadComercial', EntityType::class, array(
                'class'     => 'SICBundle:AdminRespCerrada',
                'label'     => '¿Realiza algún tipo de actividad comercial dentro de la Vivienda?',
                'attr'  =>  array(
                    'class' => 'ui fluid dropdown')))*/

            ->add('actividadComercialenVivienda', EntityType::class, array(
                'class'     => 'SICBundle:AdminVentaVivienda',
                'label'     => '¿Realiza algún tipo de actividad comercial dentro de la Vivienda?',
                'placeholder' => 'Selecciona una',
                'required'  => false,
                'multiple' => true,
                'attr'  =>  array(
                    'multiple' => '',
                    'class' => 'fluid multiple search selection')))

            ->add('ingresoFamiliar', TextType::class,array(
                'attr'      => array(
                    'placeholder'   => 'Monto en Bs'),
                'required' => false,
                ))

            ->add('ingresoFamiliarEspecifico', EntityType::class, array(
                'class'     => 'SICBundle:AdminTipoIngresos',
                'required'  => false,
                'label'     => 'Ingreso estimado',
                'placeholder' => 'Selecciona una',
                'attr'  =>  array(
                    'class' => 'ui fluid dropdown')))

            /*->add('poseeVehiculo', EntityType::class, array(
                'class'     => 'SICBundle:AdminRespCerrada',
                'label'     => '¿Realiza algún tipo de actividad comercial dentro de la Vivienda?',
                'attr'  =>  array(
                    'class' => 'ui fluid dropdown')))*/
            
            ->add('placa', TextType::class,array(
                'label'     => 'Si posee vehículo(s), Ingrese la(s) placa(s)',
                'required' => false,
                'attr'        => array(
                    'placeholder'   => 'AAA000,BBB111,....')))
        ;
    }

    // public function onPreSubmit(\Symfony\Component\Form\FormEvent $event)
    // {
        
    // }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SICBundle\Entity\SituacionEconomica'
        ));
    }
}

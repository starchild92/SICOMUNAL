<?php

namespace SICBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ComiteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $opciones = array(
            "Unidad Ejecutiva" => "Unidad Ejecutiva",
            "Unidad Administrativa y Financiera Comunitaria" => "Unidad Administrativa y Financiera Comunitaria",
            "Unidad de Contraloría Social" => "Unidad de Contraloría Social",
            "Comisión Electoral Permanente" => "Comisión Electoral Permanente",
        );

        $builder
            ->add('tipoUnidad', ChoiceType::class,array(
                    'label'   => 'Unidades',
                    'choices' => $opciones,
                ))

            ->add('nombre', TextType::class, array(
                'required' => false,))
            // ->add('cantVoceros')
            ->add('voceros', CollectionType::class,array(
                        'required' => false,
                        'type' => 'SICBundle\Form\VoceroType',
                        'cascade_validation' => true,
                        'attr' => array('class' => 'voceros'),
                        'allow_add'=>'true',
                        'by_reference'=>'false',
                        'allow_delete' =>'true',
                        'data_class' => null,
                        'label' => 'Voceros',
                        ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SICBundle\Entity\Comite'
        ));
    }
}

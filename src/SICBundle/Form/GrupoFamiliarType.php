<?php

namespace SICBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class GrupoFamiliarType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('apellidos', TextType::class, array(
                'label'     => 'Apellidos del Grupo Familiar'))
            // ->add('cantidadMiembros') //Se calcula a medida que se agregan nuevos miembros
            // ->add('tiempoResidencia', TextType::class, array(
            //     'label'     => 'Tiempo de Residencia'))
            ->add('avenida', TextType::class, array(
                'label'     => 'Avenida'))
            ->add('calle', TextType::class, array(
                'label'     => 'Calle'))
            ->add('numeroCasa', TextType::class, array(
                'required' => false,
                'label'     => 'Número de Casa'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SICBundle\Entity\GrupoFamiliar'
        ));
    }
}

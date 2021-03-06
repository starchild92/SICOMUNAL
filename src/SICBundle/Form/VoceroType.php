<?php

namespace SICBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VoceroType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $opciones = array(
            'Principal' => "Principal",
            'Suplente' => "Suplente");

        $builder
            ->add('tipo', ChoiceType::class,array(
                    'label'   => 'Tipo de Vocero',
                    'choices' => $opciones,
                ))

            ->add('votosElecto')
            ->add('persona', TextType::class, array(
                'label' => 'Cédula Persona'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SICBundle\Entity\Vocero'
        ));
    }
}

<?php

namespace SICBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use SICBundle\Form\AdminPreguntasParticipacionComunitariaType;

class ParticipacionComunitariaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('existenOrganizaciones', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminOrgComunitaria::class,
                'label'     => '¿Existen organizaciones comunitarias? - ¿Cuál(es)?',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'nombre',
                'multiple'  => true,
                'required'  => false,
                'attr'      => array(
                    'multiple' => '',
                    'class' => 'fluid multiple search selection',
                    'required'  => false
            )))

            ->add('participaOrganizacion', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminRespCerrada::class,
                'label'     => '¿Participa usted en alguna de ellas?',
                'choice_label' => 'respuesta',
                'attr'  =>  array(
                    'class' => 'ui dropdown'))
            )

            ->add('participaMiembroOrganizacion', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminRespCerrada::class,
                'label'     => '¿Participa álgun miembro de la familia?',
                'choice_label' => 'respuesta',
                'attr'  =>  array(
                    'class' => 'ui dropdown'))
            )

            ->add('misionesComunidad', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminMisionesComunidad::class,
                'label'     => '¿Cuáles Misiones Se Están Implementando En La Comunidad?',
                'placeholder' => 'Selecciona una',
                'choice_label' => 'nombre',
                'multiple'  => true,
                'required'  => false,
                'attr'      => array(
                    'multiple' => '',
                    'class' => 'fluid multiple search selection',
                    'required'  => false
            )))
            
            ->add('preguntasParticipacionComunitaria', CollectionType::class,array(
                    'required' => false,
                    'entry_type' => AdminPreguntasParticipacionComunitariaType::class,
                    'attr' => array('class' => 'preguntas'),
                    'allow_add'=>'true',
                    'by_reference'=>'false',
                    'allow_delete' =>'true',
                    'data_class' => null,
                    'label'     => 'Preguntas de Participación Comunitaria'
                ))

            ->add('areaTabajoCC', EntityType::class, array(
                'class' => \SICBundle\Entity\AdminAreaTrabajoCC::class,
                'multiple' => true,
                'label'     => '¿De crearse un consejo comunal en su comunidad, en cual área de trabajo le gustaría participar?',
                'required'  => false,
                'attr'  =>  array(
                    'multiple' => '',
                    'class' => 'ui dropdown')))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SICBundle\Entity\ParticipacionComunitaria'
        ));
    }
}

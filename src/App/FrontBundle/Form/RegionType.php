<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('regionName')
            ->add('state', 'entity', array(
                'class' => 'AppFrontBundle:State',
                'property' => 'stateName',
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('submit', 'submit', array(
                'attr' => array('class' => 'btn btn-primary'),
            ));
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\FrontBundle\Entity\Region'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_frontbundle_region';
    }
}

<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LocationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('localName')
            ->add('pinCode')
            ->add('localServiceCharge')
            ->add('regionalServiceCharge')
            ->add('region', 'entity', array(
                'class' => 'AppFrontBundle:Region',
                'property' => 'regionName',
                'multiple' => false,
                'expanded' => false,
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\FrontBundle\Entity\Location'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_frontbundle_location';
    }
}

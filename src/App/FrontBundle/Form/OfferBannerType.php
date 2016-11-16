<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OfferBannerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('banner_name')
            ->add('banner_image')
            ->add('entity', 'entity', array(
                'class' => 'AppFrontBundle:Offer',
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
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
            'data_class' => 'App\FrontBundle\Entity\OfferBanner'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_frontbundle_offerbanner';
    }

}

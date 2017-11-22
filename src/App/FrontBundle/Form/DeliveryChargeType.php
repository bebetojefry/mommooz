<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DeliveryChargeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('priceFrom', 'text', array('attr' => array("pattern" => "[+-]?([0-9]*[.])?[0-9]+", 'data-pattern-error' => 'Invalid Price')))
            ->add('priceTo', 'text', array('attr' => array("pattern" => "[+-]?([0-9]*[.])?[0-9]+", 'data-pattern-error' => 'Invalid Price')))
            ->add('charge', 'text', array('attr' => array("pattern" => "[+-]?([0-9]*[.])?[0-9]+", 'data-pattern-error' => 'Invalid Charge')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\FrontBundle\Entity\DeliveryCharge'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_frontbundle_deliverycharge';
    }
}

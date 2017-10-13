<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PurchaseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', 'choice', array(
                'multiple' => false,
                'expanded' => false,
                'choices' => array(0 => 'Pending', 1 => 'Confirmed', 2 => 'Processing', 3=> "Out for delivered", 4 => 'Delivered', 5 => 'Cancelled'),                
            ))
            ->add('expectedOn', 'datetime', array('data' => new \DateTime('now')))
            ->add('deliveredOn', 'datetime', array('data' => new \DateTime('now')))
            ->add('deliveredBy', 'datetime', array('data' => new \DateTime('now')))
            ->add('cancelledOn', 'datetime', array('data' => new \DateTime('now')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\FrontBundle\Entity\Purchase'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_frontbundle_purchase';
    }
}

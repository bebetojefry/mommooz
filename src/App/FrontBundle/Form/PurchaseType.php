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
        $order = $builder->getData();
        $builder
            ->add('status', 'choice', array(
                'multiple' => false,
                'expanded' => false,
                'choices' => array(0 => 'Pending', 1 => 'Confirmed', 2 => 'Processing', 3=> "Out for delivered", 4 => 'Delivered', 5 => 'Cancelled'),                
            ));

            if($order->getStatus() == 3) {
                $builder->add('expectedOn');
            }

            if($order->getStatus() == 4) {
                $builder->add('deliveredOn');
                $builder->add('deliveredBy');
            }

            if($order->getStatus() == 5) {
                $builder->add('cancelledOn');
            }
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

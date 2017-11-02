<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PurchaseType extends AbstractType
{
    
    private $request;
    
    public function __constructor($request){
        $this->request = $request;
    } 
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $form_data = $this->request->get('app_frontbundle_purchase');
        $status = isset($form_data['status']) ? $form_data['status'] : $builder->getData()->getStatus();
        
        $builder
            ->add('status', 'choice', array(
                'multiple' => false,
                'expanded' => false,
                'choices' => array(0 => 'Pending', 1 => 'Confirmed', 2 => 'Processing', 3=> "Out for delivered", 4 => 'Delivered', 5 => 'Cancelled'),                
            ));

            if($status == 3) {
                $builder->add('expectedOn');
            }

            if($status == 4) {
                $builder->add('deliveredOn');
                $builder->add('deliveredBy');
            }

            if($status == 5) {
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

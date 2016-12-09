<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\FrontBundle\DataTransformer\RegionsToIdsTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use App\FrontBundle\Form\AddressType;

class UserType extends AbstractType
{
    private $om;
    
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $regionstoIdsTransformer = new RegionsToIdsTransformer($this->om);
        
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('gender', 'choice', array(
                'expanded' => true,
                'choices' => array(1 => 'Male', 2 => 'Female'),
                'data' => 1
            ))
            ->add('phone')
            ->add('email')
            ->add('status')
            ->add('addresses', 'collection', array(
                'type'         => new AddressType(),
                'allow_add'    => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))
            ->add(
                $builder->create('regions', 'text', array(
                    'required' => false,
                ))->addModelTransformer($regionstoIdsTransformer)
            )  
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\FrontBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_frontbundle_user';
    }
}

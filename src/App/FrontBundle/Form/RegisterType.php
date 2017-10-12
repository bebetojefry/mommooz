<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\FrontBundle\Form\AddressType;

class RegisterType extends AbstractType
{    
    private $router;
    
    public function __construct($router)
    {
        $this->router = $router;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {       
        $builder
            ->add('firstname', 'text', array('attr' => array('placeholder' => 'Firstname', "pattern" => "[A-Za-z0-9]+", 'data-pattern-error' => 'Only alphabets and numbers are accepted')))
            ->add('phone', 'text', array('attr' => array('placeholder' => 'Phone')))
            ->add('email', 'text', array('attr' => array('placeholder' => 'Email', "pattern" => "[a-z0-9]+@[a-z0-9]+\.[a-z]{2,4}", 'data-pattern-error' => 'Invalid email id format', 'data-remote' => $this->router->generate('consumer_email_validate', array('id' => $builder->getData()->getId())), 'data-remote-error' => 'Email id already exist')))
            ->add('password', 'password', array('attr' => array('placeholder' => 'Password', 'data-minlength' => 6), 'label' => false))
            ->add('addresses', 'collection', array(
                'type'         => new AddressType(),
                'allow_add'    => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))
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

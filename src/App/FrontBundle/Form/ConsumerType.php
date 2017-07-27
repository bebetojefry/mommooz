<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\FrontBundle\DataTransformer\RegionsToIdsTransformer;
use App\FrontBundle\DataTransformer\ImageToIdsTransformer;
use App\FrontBundle\Form\AddressType;

class ConsumerType extends AbstractType
{
    private $om;
    private $router;
    
    public function __construct(ObjectManager $om, $router)
    {
        $this->om = $om;
        $this->router = $router;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $regionstoIdsTransformer = new RegionsToIdsTransformer($this->om);
        $imageTransformer = new ImageToIdsTransformer($this->om);
        
        $builder
            ->add('firstname')
            ->add('lastname')
            ->add('gender', 'choice', array(
                'expanded' => true,
                'choices' => array(1 => 'Male', 2 => 'Female'),
                'data' => 1
            ))
            ->add('phone')
            ->add('email', 'email', array(
                'attr' => array(
                    'data-remote' => $this->router->generate('email_validate', array('id' => $builder->getData()->getId()))
                )
            ))
            ->add('status')
            ->add('addresses', 'collection', array(
                'type'         => new AddressType(),
                'allow_add'    => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))
            ->add(
                $builder->create('images', 'text', array(
                    'required' => false,
                ))->addModelTransformer($imageTransformer)
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

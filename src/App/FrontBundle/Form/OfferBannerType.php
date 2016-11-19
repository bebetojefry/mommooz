<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\FrontBundle\DataTransformer\ImageToIdsTransformer;
use Doctrine\Common\Persistence\ObjectManager;

class OfferBannerType extends AbstractType
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
        $imageTransformer = new ImageToIdsTransformer($this->om);
        $builder
            ->add('banner_name')
            ->add(
                $builder->create('images', 'text', array(
                    'required' => false,
                ))->addModelTransformer($imageTransformer)
            )
            ->add('entity', 'entity', array(
                'label' => 'Offer',
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

<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemVariantType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('variantType', 'entity', array(
                'class' => 'AppFrontBundle:VariantType',
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
            ))
            ->add('value')
            ->add('price')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\FrontBundle\Entity\ItemVariant'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_frontbundle_itemvariant';
    }
}

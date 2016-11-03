<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categoryName')
            ->add('parent', 'entity', array(
                'class' => 'AppFrontBundle:Category',
                'property' => 'categoryName',
                'multiple' => false,
                'expanded' => false,
            ))
            ->add('status')
            ->add('keywords')
            ->add('submit', 'submit', array(
                'attr' => array('class' => 'class="btn btn-primary"'),
            ));    
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\FrontBundle\Entity\Category'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_frontbundle_category';
    }
}

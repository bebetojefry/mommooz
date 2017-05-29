<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\FrontBundle\DataTransformer\KeywordsToIdsTransformer;
use App\FrontBundle\DataTransformer\RegionsToIdsTransformer;
use App\FrontBundle\DataTransformer\CategoriesToIdsTransformer;

class ProductType extends AbstractType
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
        $keywordTransformer = new KeywordsToIdsTransformer($this->om);
        $regionstoIdsTransformer = new RegionsToIdsTransformer($this->om);
        $categoriestoIdsTransformer = new CategoriesToIdsTransformer($this->om);
        
        $builder
            ->add('name')
            ->add('description')
            ->add('category', 'entity', array(
                'class' => 'AppFrontBundle:Category',
                'property' => 'categoryName',
                'multiple' => false,
                'expanded' => false,
            ))
            ->add(
                $builder->create('keywords', 'text', array(
                    'required' => false,
                ))->addModelTransformer($keywordTransformer)
            )
            ->add('deliverable', 'choice', array(
                'multiple' => false,
                'expanded' => false,
                'choices' => array(0 => 'None', 1 => 'All Regions', 2 => 'Specific Regions'),                
            ))
            ->add(
                $builder->create('regions', 'text', array(
                    'required' => false,
                ))->addModelTransformer($regionstoIdsTransformer)
            )
            ->add(
                $builder->create('categories', 'text', array(
                    'required' => false,
                ))->addModelTransformer($categoriestoIdsTransformer)
            )
            ->add('status')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\FrontBundle\Entity\Product'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_frontbundle_product';
    }
}

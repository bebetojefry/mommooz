<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\FrontBundle\DataTransformer\KeywordsToIdsTransformer;
use App\FrontBundle\DataTransformer\ProductImageToIdsTransformer;
use Doctrine\Common\Persistence\ObjectManager;

class ItemType extends AbstractType
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
        $imageTransformer = new ProductImageToIdsTransformer($this->om);
        $builder
            ->add('name')
            ->add('description')    
            ->add('product', 'entity', array(
                'class' => 'AppFrontBundle:Product',
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
            ))
            ->add('brand', 'entity', array(
                'class' => 'AppFrontBundle:Brand',
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
            ))
            ->add(
                $builder->create('keywords', 'text', array(
                    'required' => false,
                ))->addModelTransformer($keywordTransformer)
            )
            ->add(
                $builder->create('images', 'text', array(
                    'required' => false,
                ))->addModelTransformer($imageTransformer)
            )
            ->add('offers')
            ->add('status')
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
            'data_class' => 'App\FrontBundle\Entity\Item'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_frontbundle_item';
    }
}

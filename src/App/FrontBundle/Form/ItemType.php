<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\FrontBundle\DataTransformer\KeywordsToIdsTransformer;
use App\FrontBundle\DataTransformer\ImageToIdsTransformer;
use App\FrontBundle\DataTransformer\OfferToIdsTransformer;
use App\FrontBundle\DataTransformer\CategoriesToIdsTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use App\FrontBundle\Form\SpecificationType;
use App\FrontBundle\Form\ItemVariantType;

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
        $imageTransformer = new ImageToIdsTransformer($this->om);
        $offerTransformer = new OfferToIdsTransformer($this->om);
        $categoriestoIdsTransformer = new CategoriesToIdsTransformer($this->om);
        
        $builder
            ->add('name')
            ->add('description')
            ->add('price');
        
        if($builder->getData()->getProduct()){
            $builder->add('product', 'entity', array(
                'class' => 'AppFrontBundle:Product',
                'choices' => $builder->getData()->getProduct()->getCategory()->getAllProducts(),
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
            ));
        } else {
            $builder->add('product', 'entity', array(
                'class' => 'AppFrontBundle:Product',
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
            ));
        }
        
        $builder
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
            ->add('comm_type', 'choice', array(
                'expanded' => true,
                'choices' => array(1 => 'Fixed', 2 => 'Percentile'),
                'data' => 1
            ))
            ->add('comm_value')
            ->add('specifications', 'collection', array(
                'type'         => new SpecificationType(),
                'allow_add'    => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))
            ->add('variants', 'collection', array(
                'type'         => new ItemVariantType(),
                'allow_add'    => true,
                'by_reference' => false,
                'allow_delete' => true,
            ))
            ->add(
                $builder->create('offers', 'text', array(
                    'required' => false,
                ))->addModelTransformer($offerTransformer)
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

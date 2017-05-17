<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\FrontBundle\Entity\Item;
use Doctrine\ORM\EntityRepository;
use App\FrontBundle\DataTransformer\KeywordsToIdsTransformer;
use App\FrontBundle\DataTransformer\OfferToIdsTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use App\FrontBundle\Entity\Vendor;

class StockEntryType extends AbstractType
{
    private $item;
    private $om;
    private $vendor;
    
    public function __construct(ObjectManager $om, Vendor $vendor, Item $item = null)
    {
        $this->vendor = $vendor;
        $this->item = $item;
        $this->om = $om;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $category = $builder->getData()->getItem() ? $builder->getData()->getItem()->getProduct()->getCategory() : null;
        
        if($this->item instanceof Item){
            $keywordTransformer = new KeywordsToIdsTransformer($this->om);
            $offerTransformer = new OfferToIdsTransformer($this->om);
            
            $equation = '';
            if($this->item->getCommType() == 1){
                $equation = '[Price] + '.$this->item->getCommValue();
            } else if($this->item->getCommType() == 2) {
                $equation = '[Price] + ([price]*'.$this->item->getCommValue().'/100)';
            }
            
            $builder
            ->add('item', 'entity', array(
                'class' => 'AppFrontBundle:Item',
                'choices' => $this->vendor->getRealItems($category),
                'property' => 'nameWithBrand',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
                'attr' => array('readonly' => true)
            ))
            ->add('quantity')
            ->add('mrp')
            ->add('price')
            ->add('actualPrice', 'text', array(
                'label' => 'Actual Price ('.$equation.')',
                'attr' => array('readonly' => true)
            ));
              
            if($this->item->getVariants()->count() > 0){
                $builder->add('variant', 'entity', array(
                    'class' => 'AppFrontBundle:ItemVariant',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('iv')
                            ->orderBy('iv.id', 'ASC')
                            ->where('iv.item = :item')
                            ->setParameter('item', $this->item);
                    },
                    'property' => 'uniqueName',
                    'multiple' => false,
                    'expanded' => false,
                    'required' => true,
                ));
            }
            
            $builder            
            ->add('stock', 'entity', array(
                'class' => 'AppFrontBundle:Stock',
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
                'attr' => array('readonly' => true)
            ))
            ->add(
                $builder->create('offers', 'text', array(
                    'required' => false,
                ))->addModelTransformer($offerTransformer)
            )
            ->add(
                $builder->create('keywords', 'text', array(
                    'required' => false,
                ))->addModelTransformer($keywordTransformer)
            )
            ->add('status');
        } else {
            $builder->add('item', 'entity', array(
                'class' => 'AppFrontBundle:Item',
                'choices' => $this->vendor->getRealItems($category),
                'property' => 'nameWithBrand',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
            ));
        }
        
        $builder->add('state', 'hidden');
        $builder->add('commtype', 'hidden');
        $builder->add('commvalue', 'hidden');
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\FrontBundle\Entity\StockEntry'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_frontbundle_stockentry';
    }
}

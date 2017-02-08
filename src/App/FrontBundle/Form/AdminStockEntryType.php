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

class AdminStockEntryType extends AbstractType
{
    private $item;
    private $om;
    
    public function __construct(ObjectManager $om, Item $item = null)
    {
        $this->item = $item;
        $this->om = $om;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $offerTransformer = new OfferToIdsTransformer($this->om);

        $equation = '';
        if($this->item->getCommType() == 1){
            $equation = '[Price] + '.$this->item->getCommValue();
        } else if($this->item->getCommType() == 2) {
            $equation = '[Price] + ([price]*'.$this->item->getCommValue().'/100)';
        }

        $builder
        ->add('mrp')
        ->add('price')
        ->add('actualPrice', 'text', array(
            'label' => 'Actual Price ('.$equation.')',
            'attr' => array('readonly' => true)
        ))
        ->add(
            $builder->create('offers', 'text', array(
                'required' => false,
            ))->addModelTransformer($offerTransformer)
        )
        ->add('status');;
        
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

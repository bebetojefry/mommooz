<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\FrontBundle\Entity\Item;

class StockEntryType extends AbstractType
{
    private $item;

    public function __construct(Item $item = null)
    {
        $this->item = $item;
    }
    
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($this->item instanceof Item){
            $builder
            ->add('quantity')
            ->add('price')
            ->add('actualPrice')
            ->add('status')
            ->add('item', 'entity', array(
                'class' => 'AppFrontBundle:Item',
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
            ))
            ->add('stock', 'entity', array(
                'class' => 'AppFrontBundle:Stock',
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
            ))
            ->add('offers');
        } else {
            $builder->add('item', 'entity', array(
                'class' => 'AppFrontBundle:Item',
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
            ));
        } 
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

<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use App\FrontBundle\Entity\Item;
use Doctrine\ORM\EntityRepository;
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
            $equation = '';
            if($this->item->getCommType() == 1){
                $equation = '[Price] + '.$this->item->getCommValue();
            } else if($this->item->getCommType() == 2) {
                $equation = '[Price] + ([price]*'.$this->item->getCommValue().'/100)';
            }
            
            $builder
            ->add('item', 'entity', array(
                'class' => 'AppFrontBundle:Item',
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
                'attr' => array('readonly' => true)
            ))
            ->add('quantity')
            ->add('price')
            ->add('actualPrice', 'text', array(
                'label' => 'Actual Price ('.$equation.')',
                'attr' => array('readonly' => true)
            ))
            ->add('variant', 'entity', array(
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
            ))
            ->add('stock', 'entity', array(
                'class' => 'AppFrontBundle:Stock',
                'property' => 'name',
                'multiple' => false,
                'expanded' => false,
                'required' => true,
                'attr' => array('readonly' => true)
            ))
            ->add('offers')
            ->add('status');
        } else {
            $builder->add('item', 'entity', array(
                'class' => 'AppFrontBundle:Item',
                'property' => 'name',
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

<?php

namespace App\FrontBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\FrontBundle\DataTransformer\KeywordsToIdsTransformer;

class BrandType extends AbstractType
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
        $builder
            ->add('name')
            ->add('description')
            ->add(
                $builder->create('keywords', 'text', array(
                    'required' => false,
                ))->addModelTransformer($keywordTransformer)
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
            'data_class' => 'App\FrontBundle\Entity\Brand'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_frontbundle_brand';
    }
}

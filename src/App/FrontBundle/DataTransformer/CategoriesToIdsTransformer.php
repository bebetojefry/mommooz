<?php

namespace App\FrontBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class CategoriesToIdsTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($categories)
    {
        $categories = $categories->getValues();
        if(null === $categories){
            return "";
        }

        $idsArray = array();
        foreach($categories as $category){
            $idsArray[] = $category->getId();
        }
        $ids = implode(",", $idsArray);
        
        return $ids;
    }

    public function reverseTransform($ids)
    {
        $categories = new ArrayCollection();
        if('' === $ids || null === $ids){
            return $categories;
        }

        if(!is_string($ids)){
            throw new UnexpectedTypeException($ids, 'string');
        }
        $idsArray = explode(",", $ids);
        foreach($idsArray as $id){
            if(is_numeric($id)){
                $category = $this->manager->getRepository('AppFrontBundle:Category')->findOneById($id);
            }
            
            $categories->add($category);
        }
        
        return $categories;
    }
}
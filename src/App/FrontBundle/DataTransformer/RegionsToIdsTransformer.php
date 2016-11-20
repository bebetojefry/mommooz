<?php

namespace App\FrontBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class RegionsToIdsTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($regions)
    {
        $regions = $regions->getValues();
        if(null === $regions){
            return "";
        }

        $idsArray = array();
        foreach($regions as $region){
            $idsArray[] = $region->getId();
        }
        $ids = implode(",", $idsArray);
        
        return $ids;
    }

    public function reverseTransform($ids)
    {
        $regions = new ArrayCollection();
        if('' === $ids || null === $ids){
            return $regions;
        }

        if(!is_string($ids)){
            throw new UnexpectedTypeException($ids, 'string');
        }
        $idsArray = explode(",", $ids);
        foreach($idsArray as $id){
            if(is_numeric($id)){
                $region = $this->manager->getRepository('AppFrontBundle:Region')->findOneById($id);
            }
            
            $regions->add($region);
        }
        
        return $regions;
    }
}
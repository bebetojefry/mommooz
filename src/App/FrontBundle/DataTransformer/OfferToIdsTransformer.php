<?php

namespace App\FrontBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class OfferToIdsTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($offers)
    {
        $offers = $offers->getValues();
        if(null === $offers){
            return "";
        }

        $idsArray = array();
        foreach($offers as $offer){
            $idsArray[] = $offer->getId();
        }
        $ids = implode(",", $idsArray);
        
        return $ids;
    }

    public function reverseTransform($ids)
    {
        $offers = new ArrayCollection();
        if('' === $ids || null === $ids){
            return $offers;
        }

        if(!is_string($ids)){
            throw new UnexpectedTypeException($ids, 'string');
        }
        $idsArray = explode(",", $ids);
        foreach($idsArray as $id){
            if(is_numeric($id)){
                $offer = $this->manager->getRepository('AppFrontBundle:Offer')->findOneById($id);
            }
            
            $offers->add($offer);
        }
        
        return $offers;
    }
}
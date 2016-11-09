<?php

namespace App\FrontBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

class KeywordsToIdsTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($keywords)
    {
        $keywords = $keywords->getValues();
        if(null === $keywords){
            return "";
        }

        $idsArray = array();
        foreach($keywords as $keyword){
            $idsArray[] = $keyword->getId();
        }
        $ids = implode(",", $idsArray);
        
        return $ids;
    }

    public function reverseTransform($ids)
    {
        $keywords = new ArrayCollection();
        if('' === $ids || null === $ids){
            return $keywords;
        }

        if(!is_string($ids)){
            throw new UnexpectedTypeException($ids, 'string');
        }
        $idsArray = explode(",", $ids);
        $idsArray = array_filter ($idsArray, 'is_numeric');
        foreach($idsArray as $id){
            $keywords->add($this->manager->getRepository('AppFrontBundle:Keyword')->findOneById($id));
        }
        
        return $keywords;
    }
}
<?php

namespace App\FrontBundle\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use App\FrontBundle\Entity\Image;

class ImageToIdsTransformer implements DataTransformerInterface
{
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }
    
    public function transform($images)
    {
        $images = $images->getValues();
        if(null === $images){
            return "";
        }

        $idsArray = array();
        foreach($images as $image){
            $idsArray[] = $image->getId().'|'.$image->getImage();
        }

        $ids = implode("<>", $idsArray);
        
        return $ids;
    }

    public function reverseTransform($ids)
    {
        $images = new ArrayCollection();
        if('' === $ids || null === $ids){
            return $images;
        }

        if(!is_string($ids)){
            throw new UnexpectedTypeException($ids, 'string');
        }
        $idsArray = explode("<>", $ids);
       
        foreach($idsArray as $id){
            $val = explode('|', $id);
            if($val[0] == 0){
                $image = new Image();
                $image->setImage($val[1]);
                $this->manager->persist($image);
                $this->manager->flush();
            } else {
                $image = $this->manager->getRepository('AppFrontBundle:Image')->findOneById($val[0]);
            }
            $images->add($image);
        }
        
        return $images;
    }
}
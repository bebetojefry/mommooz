<?php

namespace App\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\FrontBundle\Handler\CmsAnnotationHandler;
use Doctrine\ORM\Mapping\ClassMetadata;
class DemoController extends FOSRestController
{
    /**
     * @Rest\Get("/demo")
     */
    public function demoAction()
    {
        $data = array();
        $classMetaData = $this->getDoctrine()->getManager()->getMetadataFactory()->getAllMetadata();
        foreach($classMetaData as $metadata){
            $className = $metadata->getName();
            // restrict only front bundle entity classes
            $classReflection = new \ReflectionClass($className);
            if(strpos($className, 'FrontBundle') !== false && !$classReflection->isAbstract()) {
                $EntityObj = new $className();
                if ($name = CmsAnnotationHandler::isCmsClass($EntityObj)) {
                    $data[$className]["name"] = $name;
                    foreach($metadata->getFieldNames() as $field){
                        if ($fieldName = CmsAnnotationHandler::isCmsField($EntityObj, $field)) {
                            $data[$className]["fields"][$field] = $fieldName;
                        }
                    }
                }
            }
        }

        $view = $this->view($data);
        return $this->handleView($view);
    }
}

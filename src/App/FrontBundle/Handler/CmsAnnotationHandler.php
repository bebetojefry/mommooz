<?php

namespace App\FrontBundle\Handler;

use Doctrine\Common\Annotations\AnnotationReader;

class CmsAnnotationHandler
{
    public static function isCmsClass($entityClass)
    {
        $reader = new AnnotationReader();
        $apiMetaAnnotation = $reader->getClassAnnotation(new \ReflectionClass(new $entityClass), 'App\\FrontBundle\\Annotation\\CmsClass');

        if($apiMetaAnnotation) {
            return $apiMetaAnnotation->displayName;
        }

        return false;
    }

    public static function isCmsField($entityClass, $property)
    {
        $reader = new AnnotationReader();
        $reflectionProp = new \ReflectionProperty($entityClass, $property);
        $cmsField = $reader->getPropertyAnnotation($reflectionProp, 'App\\FrontBundle\\Annotation\\CmsProperty');

        if($cmsField) {
            return $cmsField->displayName;
        }

        return null;
    }
}
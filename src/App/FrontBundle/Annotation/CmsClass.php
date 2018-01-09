<?php

namespace App\FrontBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class CmsClass extends Annotation
{
    /*
     * meaningfull name to be used for the front end
     */
    public $displayName;
}
<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Lsw\ApiCallerBundle\LswApiCallerBundle(),
            new Nzo\UrlEncryptorBundle\NzoUrlEncryptorBundle(),
            new Gregwar\ImageBundle\GregwarImageBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Funddy\Bundle\JsTranslationsBundle\FunddyJsTranslationsBundle(),
            new Sg\DatatablesBundle\SgDatatablesBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            new Endroid\Bundle\QrCodeBundle\EndroidQrCodeBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Ob\HighchartsBundle\ObHighchartsBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new App\FrontBundle\AppFrontBundle(),
            new App\WebBundle\AppWebBundle(),
            new App\ApiBundle\AppApiBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test', 'svr'))) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
            $bundles[] = new Hautelook\AliceBundle\HautelookAliceBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}

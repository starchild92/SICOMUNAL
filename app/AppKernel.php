<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function __construct($environment, $debug)
    {
        date_default_timezone_set( 'America/Caracas' );
        parent::__construct($environment, $debug);
    }

    public function registerBundles(): iterable
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Knp\Bundle\SnappyBundle\KnpSnappyBundle(),
            new SICBundle\SICBundle(),
            // new Slik\DompdfBundle\SlikDompdfBundle(), // bundle antiguo, usar dompdf/dompdf directamente
            // new Liuggio\ExcelBundle\LiuggioExcelBundle(), // bundle antiguo, usar phpoffice/phpspreadsheet directamente
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            // $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle(); // no compatible
            // $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle(); // no compatible
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}

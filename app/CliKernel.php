<?php

use Symfony\Component\Config\Loader\LoaderInterface;

class CliKernel extends AppKernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = array(
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
        );

        return array_merge(parent::registerBundles(), $bundles);
    }
    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        parent::registerContainerConfiguration($loader);

        $loader->load(__DIR__.'/config/migrations.yml');
    }
}

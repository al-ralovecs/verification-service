<?php

declare(strict_types=1);

namespace Application;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';
    private const CONFIG_DIRS = '{packages}';

    public function registerBundles(): iterable
    {
        $contents = require $this->getBundlesPath();
        foreach ($contents as $class => $envs) {
            if (true === ($envs[$this->environment] ?? $envs['all'] ?? false)) {
                yield new $class(); // @phpstan-ignore-line
            }
        }
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $confDir = $this->getProjectDir() . '/config';
        $container->import($confDir . '/' . self::CONFIG_DIRS . '/*' . self::CONFIG_EXTS, 'glob');
        $container->import($confDir . '/' . self::CONFIG_DIRS . '/' . $this->environment . '/*' . self::CONFIG_EXTS, 'glob');
        $container->import($confDir . '/{services}' . self::CONFIG_EXTS, 'glob');
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $confDir = $this->getProjectDir() . '/config';

        $routes->import($confDir . '/{routes}/*' . self::CONFIG_EXTS, 'glob');
        $routes->import($confDir . '/{routes}/' . $this->environment . '/**/*' . self::CONFIG_EXTS, 'glob');
        $routes->import($confDir . '/{routes}' . self::CONFIG_EXTS, 'glob');
    }
}

<?php
/*
 * This file is part of the Ecentria software.
 *
 * (c) 2017, Ecentria, Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecentria\Bundle\CrontabBundle\DependencyInjection;

use Ecentria\Bundle\CrontabBundle\EcentriaCrontabBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension as BaseExtension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Extension
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class Extension extends BaseExtension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $jobs = [];
        if (!empty($config['jobs'])) {
            $jobs = $config['jobs'];
        }

        $mailto = null;
        if (!empty($config['mailto'])) {
            $mailto = $config['mailto'];
        }

        $path = '';
        if (!empty($config['path'])) {
            $path = $config['path'];
        }

        $user = '';
        if (!empty($config['user'])) {
            $user = $config['user'];
        }

        $container->setParameter('ecentria.crontab.jobs', $jobs);
        $container->setParameter('ecentria.crontab.mailto', $mailto);
        $container->setParameter('ecentria.crontab.path', $path);
        $container->setParameter('ecentria.crontab.user', $user);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('config.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return EcentriaCrontabBundle::ALIAS;
    }
}

<?php
/*
 * This file is part of the ecentria group, inc. software.
 *
 * (c) 2016, ecentria group, inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecentria\Bundle\CrontabBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Job provider pass
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class JobProviderPass implements CompilerPassInterface
{
    const TAG = 'ecentria.crontab.job';
    const SERVICE = 'ecentria.crontab.compiler';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $serviceDefinition = $container->getDefinition(self::SERVICE);
        $taggedServiceIds = $container->findTaggedServiceIds(self::TAG);

        foreach ($taggedServiceIds as $id => $attributes) {
            $provider = $container->getDefinition($id);
            $serviceDefinition->addMethodCall('registerProvider', [$provider]);
        }

        $container->setDefinition(self::SERVICE, $serviceDefinition);
    }
}

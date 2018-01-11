<?php
/*
 * This file is part of the Ecentria software.
 *
 * (c) 2017, Ecentria, Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecentria\Bundle\CrontabBundle;

use Ecentria\Bundle\CrontabBundle\DependencyInjection\Compiler\JobProviderPass;
use Ecentria\Bundle\CrontabBundle\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Ecentria crontab bundle
 *
 * @author Roman Ruskov <roman.ruskov@gmail.com>
 */
class EcentriaCrontabBundle extends Bundle
{
    const ALIAS = 'ecentria_crontab';

    /**
     * Get container extension
     *
     * @return Extension
     */
    public function getContainerExtension()
    {
        return new Extension();
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new JobProviderPass(), PassConfig::TYPE_OPTIMIZE);
    }
}

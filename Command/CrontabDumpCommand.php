<?php
/*
 * This file is part of the ecentria group, inc. software.
 *
 * (c) 2016, ecentria group, inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecentria\Bundle\CrontabBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Crontab dump command
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class CrontabDumpCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('ecentria:crontab:dump');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('ecentria.crontab.executor')->dump();
    }
}

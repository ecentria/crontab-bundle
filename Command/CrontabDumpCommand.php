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

use Ecentria\Bundle\CrontabBundle\Services\CrontabExecutor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Crontab dump command
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class CrontabDumpCommand extends Command
{
    /**
     * Crontab executor
     *
     * @var CrontabExecutor
     */
    private $crontabExecutor;

    /**
     * Constructor
     *
     * @param string|null     $name
     * @param CrontabExecutor $crontabExecutor
     */
    public function __construct($name, CrontabExecutor $crontabExecutor)
    {
        parent::__construct($name);
        $this->crontabExecutor = $crontabExecutor;
    }

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
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return (int) !$this->crontabExecutor->dump();
    }
}

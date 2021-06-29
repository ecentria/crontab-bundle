<?php

/*
 * This file is part of the ecentria group, inc. software.
 *
 * (c) 2017, ecentria group, inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecentria\Bundle\CrontabBundle\Command;

use Ecentria\Bundle\CrontabBundle\Services\CrontabInstaller;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Crontab create command
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class CrontabInstallCommand extends Command
{
    /**
     * Crontab installer
     *
     * @var CrontabInstaller
     */
    private $crontabInstaller;

    /**
     * Constructor
     *
     * @param string|null      $name
     * @param CrontabInstaller $crontabInstaller
     */
    public function __construct($name, CrontabInstaller $crontabInstaller)
    {
        parent::__construct($name);
        $this->crontabInstaller = $crontabInstaller;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('ecentria:crontab:install');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->crontabInstaller->install()) {
            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}

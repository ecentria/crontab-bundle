<?php

/*
 * This file is part of the Ecentria software.
 *
 * (c) 2017, Ecentria, Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecentria\Bundle\CrontabBundle\Services;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;

/**
 * Crontab installer
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class CrontabInstaller
{
    /**
     * Executor
     *
     * @var CrontabExecutor
     */
    private $executor;

    /**
     * Path
     *
     * @var string
     */
    private $path;

    /**
     * User
     *
     * @var string
     */
    private $user;

    /**
     * Logger
     *
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Crontab executor constructor
     *
     * @param CrontabExecutor $executor
     * @param LoggerInterface $logger
     * @param string          $path
     * @param string          $user
     */
    public function __construct(CrontabExecutor $executor, LoggerInterface $logger, string $path, string $user)
    {
        $this->executor = $executor;
        $this->logger = $logger;
        $this->path = $path;
        $this->user = $user;
    }

    /**
     * Install
     *
     * @return int
     */
    public function install(): int
    {
        $changed = $this->executor->dump();

        if ($changed) {
            return $this->executeInstall();
        }

        return Command::SUCCESS;
    }

    /**
     * Execute install
     *
     * @return int
     */
    private function executeInstall(): int
    {
        $this->validateUser();

        $source = $this->path . DIRECTORY_SEPARATOR . CrontabExecutor::FILENAME;

        $commandToExecute = sprintf('crontab %s', $source);

        exec($commandToExecute, $output, $returnVar);

        if ($returnVar === 0) {
            $this->logger->info(
                'New crontab was successfully installed',
                [
                    'crontab' => file_get_contents($source)
                ]
            );
            return Command::SUCCESS;
        }

        $this->logger->error('New crontab was NOT installed');
        return Command::FAILURE;
    }

    /**
     * We should install crontab only under one user.
     * Someone can login on server and call this command,
     * in such situation we will get double crontab installed.
     *
     * @return void
     * @throws \Exception
     */
    private function validateUser()
    {
        if (get_current_user() != $this->user) {
            throw new \Exception(
                sprintf(
                    'Only user "%s" can install crontab',
                    $this->user
                )
            );
        }
    }
}

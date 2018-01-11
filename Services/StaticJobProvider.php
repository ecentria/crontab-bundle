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

use Ecentria\Bundle\CrontabBundle\Model\Job;

/**
 * Static job provider
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class StaticJobProvider implements JobProviderInterface
{
    const NAME = 'job.provider.static';

    /**
     * Hostname manager
     *
     * @var HostnameManager
     */
    private $hostnameManager;

    /**
     * Jobs
     *
     * @var array|Job[]
     */
    private $jobs = [];

    /**
     * Static job provider constructor
     *
     * @param HostnameManager $hostnameManager
     * @param array           $jobs
     */
    public function __construct(HostnameManager $hostnameManager, array $jobs)
    {
        $this->hostnameManager = $hostnameManager;

        foreach ($jobs as $hostname => $instances) {
            foreach ($instances as $instance) {
                $this->jobs[$hostname][] = new Job(
                    (string) $instance['description'],
                    (string) $instance['frequency'],
                    (string) $instance['command'],
                    !empty($instance['parameters']) ? $instance['parameters'] : null
                );
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function provide(): array
    {
        $hostname = $this->hostnameManager->getHostname();

        if (!empty($this->jobs[$hostname])) {
            return $this->jobs[$hostname];
        }

        foreach ($this->jobs as $configurationHostname => $configuration) {
            $matchResult = @preg_match('/' . $configurationHostname . '/i', $hostname);
            if (false === $matchResult) {
                $this->throwRegexMatchException($configurationHostname, $hostname);
            }
            if ($matchResult) {
                return $configuration;
            }
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * Throw regex match exception
     *
     * @param string $configurationHostname
     * @param string $hostname
     *
     * @throws \RuntimeException
     *
     * @return void
     */
    protected function throwRegexMatchException(string $configurationHostname, string $hostname)
    {
        $exceptionMessage = sprintf(
            'Failed to match regular expression: %s for host: %s',
            $configurationHostname,
            $hostname
        );

        throw new \RuntimeException($exceptionMessage);
    }
}

<?php

/*
 * This file is part of the ecentria software.
 *
 * (c) 2018, ecentria, Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecentria\Bundle\CrontabBundle\Tests\Services;

use Ecentria\Bundle\CrontabBundle\Model\Job;
use Ecentria\Bundle\CrontabBundle\Services\HostnameManager;
use Ecentria\Bundle\CrontabBundle\Services\StaticJobProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Static job provider test
 *
 * @property HostnameManager|Mock $hostnameManager
 * @property StaticJobProvider    $provider
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class StaticJobProviderTest extends WebTestCase
{
    const JOB_1_DESCRIPTION = 'description 1';
    const JOB_2_DESCRIPTION = 'description 2';
    const JOB_3_DESCRIPTION = 'description 3';

    const JOB_1_FREQUENCY = '* * * * *';
    const JOB_2_FREQUENCY = '*/10 * * * *';
    const JOB_3_FREQUENCY = '*/5 * * * *';

    const JOB_1_SCRIPT = 'script 1';
    const JOB_2_SCRIPT = 'script 2';
    const JOB_3_SCRIPT = 'script 3';

    const JOB_1_PARAMETER = 'parameter 3';
    const JOB_2_PARAMETER = 'parameter 3';
    const JOB_3_PARAMETER = 'parameter 3';

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->hostnameManager = $this->createMock(HostnameManager::class);

        $this->provider = new StaticJobProvider(
            $this->hostnameManager,
            [
                'backend.example.com'  => [
                    [
                        'description' => self::JOB_1_DESCRIPTION,
                        'frequency'   => self::JOB_1_FREQUENCY,
                        'command'     => self::JOB_1_SCRIPT,
                        'parameters'  => self::JOB_1_PARAMETER,
                    ]
                ],
                'node-\d+.example.com' => [
                    [
                        'description' => self::JOB_2_DESCRIPTION,
                        'frequency'   => self::JOB_2_FREQUENCY,
                        'command'     => self::JOB_2_SCRIPT,
                        'parameters'  => self::JOB_2_PARAMETER,
                    ],
                    [
                        'description' => self::JOB_3_DESCRIPTION,
                        'frequency'   => self::JOB_3_FREQUENCY,
                        'command'     => self::JOB_3_SCRIPT,
                        'parameters'  => self::JOB_3_PARAMETER,
                    ]
                ]
            ]
        );
    }

    /**
     * Test
     *
     * @param string $actualHostname
     * @param array  $expectedJobs
     *
     * @dataProvider getData()
     *
     * @return void
     */
    public function test(string $actualHostname, array $expectedJobs)
    {
        $this->hostnameManager->expects($this->once())
            ->method('getHostname')
            ->willReturn($actualHostname);

        $this->assertEquals(
            $expectedJobs,
            $this->provider->provide()
        );
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData(): array
    {
        return [
            [
                'empty.example.com',
                []
            ],
            [
                'backend.example.com',
                [
                    new Job(
                        self::JOB_1_DESCRIPTION,
                        self::JOB_1_FREQUENCY,
                        self::JOB_1_SCRIPT,
                        self::JOB_1_PARAMETER
                    )
                ]
            ],
            [
                'node-1.example.com',
                [
                    new Job(
                        self::JOB_2_DESCRIPTION,
                        self::JOB_2_FREQUENCY,
                        self::JOB_2_SCRIPT,
                        self::JOB_2_PARAMETER
                    ),
                    new Job(
                        self::JOB_3_DESCRIPTION,
                        self::JOB_3_FREQUENCY,
                        self::JOB_3_SCRIPT,
                        self::JOB_3_PARAMETER
                    )
                ]
            ],
            [
                'node-2.example.com',
                [
                    new Job(
                        self::JOB_2_DESCRIPTION,
                        self::JOB_2_FREQUENCY,
                        self::JOB_2_SCRIPT,
                        self::JOB_2_PARAMETER
                    ),
                    new Job(
                        self::JOB_3_DESCRIPTION,
                        self::JOB_3_FREQUENCY,
                        self::JOB_3_SCRIPT,
                        self::JOB_3_PARAMETER
                    )
                ]
            ],
        ];
    }
}

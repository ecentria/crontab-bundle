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

use Ecentria\Bundle\CrontabBundle\Services\HostnameManager;
use Ecentria\Bundle\CrontabBundle\Services\StaticJobProvider;
use PHPUnit\Framework\TestCase;

/**
 * Static job provider test
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class StaticJobProviderValidationTest extends TestCase
{
    /**
     * Test
     *
     * @param array  $configuration
     * @param string $message
     *
     * @return void
     * @throws \PHPUnit_Framework_Exception
     * @dataProvider getData
     */
    public function test(array $configuration, string $message)
    {
        $this->expectExceptionMessage($message);

        new StaticJobProvider(
            $this->createMock(HostnameManager::class),
            $configuration
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
                'configuration' => [
                    'backend.example.com' => [
                        [
                            'description' => 'foo'
                        ]
                    ]
                ],
                'message' => 'Wrong instance configuration array is given. Array should contain (description, frequency, command, parameters) keys.'
            ],
            [
                'configuration' => [
                    'backend.example.com' => [
                        [
                            'description' => 'foo',
                            'frequency'   => 'foo'
                        ]
                    ]
                ],
                'message' => 'Wrong instance configuration array is given. Array should contain (description, frequency, command, parameters) keys.'
            ],
            [
                'configuration' => [
                    'backend.example.com' => [
                        [
                            'description' => 'foo',
                            'frequency'   => 'foo',
                            'command'     => 'foo'
                        ]
                    ]
                ],
                'message' => 'Wrong instance configuration array is given. Array should contain (description, frequency, command, parameters) keys.'
            ],
            [
                'configuration' => [
                    0 => [
                        [
                            'description' => 'foo',
                            'frequency'   => 'foo',
                            'command'     => 'foo',
                            'parameters'  => 'foo'
                        ]
                    ]
                ],
                'message' => 'Wrong parameter type. Expected "hostname" to be string.'
            ]
        ];
    }
}

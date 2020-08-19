<?php

/*
 * This file is part of the ecentria group, inc. software.
 *
 * (c) 2016, ecentria group, inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecentria\Bundle\CrontabBundle\Tests\Services;

use Ecentria\Bundle\CrontabBundle\Model\Job;
use Ecentria\Bundle\CrontabBundle\Services\JobsCompiler;
use Ecentria\Bundle\CrontabBundle\Services\JobProviderInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Job compiler test
 *
 * @property Mock|ValidatorInterface $validator
 * @property JobsCompiler            $jobCompiler
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class JobCompilerTest extends WebTestCase
{
    const EMAIL = 'test@example.com';

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->validator = $this->getMockBuilder(ValidatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->jobCompiler = new JobsCompiler(
            $this->validator,
            self::EMAIL
        );
    }

    /**
     * Test invalid job should throw exception
     *
     * @expectedException \DomainException
     * @expectedExceptionMessage Cannot compile jobs. Errors: [Description cannot be empty], [Frequency cannot be empty], [Command cannot be empty]
     *
     * @return void
     */
    public function testInvalidJobShouldThrowException()
    {
        $provider = $this->getMockForAbstractClass(
            JobProviderInterface::class,
            ['getName', 'provide']
        );

        $provider->expects($this->once())
            ->method('getName')
            ->willReturn('test-1');

        $invalidJob = new Job(
            'description-1',
            '1 * * * *',
            '',
            'parameters-1'
        );

        $provider->expects($this->once())
            ->method('provide')
            ->willReturn(
                [
                    $invalidJob,
                ]
            );

        $this->jobCompiler->registerProvider($provider);

        $descriptionViolation = new ConstraintViolation(
            'Description cannot be empty',
            '',
            [],
            null,
            '',
            null
        );
        $frequencyViolation = new ConstraintViolation(
            'Frequency cannot be empty',
            '',
            [],
            null,
            '',
            null
        );
        $commandViolation = new ConstraintViolation(
            'Command cannot be empty',
            '',
            [],
            null,
            '',
            null
        );

        $violations = \SplFixedArray::fromArray(
            [
                $descriptionViolation,
                $frequencyViolation,
                $commandViolation,
            ]
        );

        $this->validator->expects($this->exactly(1))
            ->method('validate')
            ->willReturn($violations);

        $this->jobCompiler->render();
    }

    /**
     * Test render
     *
     * @return void
     */
    public function testRender()
    {
        $jobs = [
            new Job(
                'description-1',
                '1 * * * *',
                'command',
                'parameters-1'
            ),
            new Job(
                'description-2',
                '2 * * * *',
                'command',
                'parameters-2'
            ),
            new Job(
                'description-3',
                '3 * * * *',
                'command',
                'parameters-3'
            ),
            new Job(
                'description-4',
                '4 * * * *',
                'command',
                'parameters-4'
            )
        ];

        $provider1 = $this->getMockForAbstractClass(
            JobProviderInterface::class,
            ['getName', 'provide']
        );
        $provider2 = $this->getMockForAbstractClass(
            JobProviderInterface::class,
            ['getName', 'provide']
        );

        $provider1->expects($this->once())
            ->method('getName')
            ->willReturn('test-1');

        $provider2->expects($this->once())
            ->method('getName')
            ->willReturn('test-2');

        $provider1->expects($this->once())
            ->method('provide')
            ->willReturn(
                [
                    $jobs[0],
                    $jobs[1],
                    $jobs[2],
                ]
            );

        $provider2->expects($this->once())
            ->method('provide')
            ->willReturn(
                [
                    $jobs[3]
                ]
            );

        $this->jobCompiler->registerProvider($provider1);
        $this->jobCompiler->registerProvider($provider2);

        $this->validator->expects($this->exactly(4))
            ->method('validate')
            ->willReturn(new \SplFixedArray(0));

        $expectedResponse = $this->getExpectedResponse();

        // act
        $actualResponse = $this->jobCompiler->render();

        $this->assertSame(
            $expectedResponse,
            $actualResponse
        );
    }

    /**
     * Get expected response
     *
     * @return string
     */
    private function getExpectedResponse()
    {
        $string = '# This file has been generated dynamically' . PHP_EOL;
        $string .= '# All manual changes will be erased soon' . PHP_EOL;
        $string .= PHP_EOL;
        $string .= 'MAILTO="test@example.com"' . PHP_EOL;
        $string .= PHP_EOL;
        $string .= '# description-1' . PHP_EOL;
        $string .= '1 * * * * command parameters-1' . PHP_EOL;
        $string .= PHP_EOL;
        $string .= '# description-2' . PHP_EOL;
        $string .= '2 * * * * command parameters-2' . PHP_EOL;
        $string .= PHP_EOL;
        $string .= '# description-3' . PHP_EOL;
        $string .= '3 * * * * command parameters-3' . PHP_EOL;
        $string .= PHP_EOL;
        $string .= '# description-4' . PHP_EOL;
        $string .= '4 * * * * command parameters-4' . PHP_EOL;
        $string .= PHP_EOL;

        return $string;
    }
}

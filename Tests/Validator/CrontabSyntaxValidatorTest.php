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

use Ecentria\Bundle\CrontabBundle\Validator\Constraints\CrontabSyntax;
use Ecentria\Bundle\CrontabBundle\Validator\Constraints\CrontabSyntaxValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContext;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Crontab syntax validator test
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class CrontabSyntaxValidatorTest extends TestCase
{
    /**
     * Test invalid
     *
     * @param string $value
     *
     * @dataProvider getInvalidFrequency
     *
     * @return void
     */
    public function testInvalid($value)
    {
        /** @var Mock|ExecutionContextInterface $context */
        $context = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();

        $constraint = new CrontabSyntax();
        $validator = new CrontabSyntaxValidator();

        $validator->initialize($context);

        $context->expects($this->once())
            ->method('addViolation')
            ->with(
                sprintf(
                    'Job frequency value "%s" is incorrect',
                    $value
                )
            );

        $validator->validate(
            $value,
            $constraint
        );
    }

    /**
     * Get invalid frequency
     *
     * @return array
     */
    public function getInvalidFrequency(): array
    {
        return [
            [''],
            ['*'],
            ['* *'],
            ['* * * *'],
            ['test']
        ];
    }

    /**
     * Test invalid
     *
     * @param string $value
     *
     * @dataProvider getValidFrequency
     *
     * @return void
     */
    public function testValid($value)
    {
        /** @var Mock|ExecutionContextInterface $context */
        $context = $this->getMockBuilder(ExecutionContext::class)
            ->disableOriginalConstructor()
            ->getMock();

        $constraint = new CrontabSyntax();
        $validator = new CrontabSyntaxValidator();

        $validator->initialize($context);

        $context->expects($this->never())
            ->method('addViolation');

        $validator->validate(
            $value,
            $constraint
        );
    }

    /**
     * Get invalid frequency
     *
     * @return array
     */
    public function getValidFrequency()
    {
        return [
            ['* * * * *'],
            ['*/5 * * * *'],
            ['*/3 * * * *'],
            ['*/30 */4 * * *'],
            ['*/20 * * * 3,4,5,6,0']
        ];
    }
}

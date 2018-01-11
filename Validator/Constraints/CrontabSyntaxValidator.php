<?php
/*
 * This file is part of the ecentria group, inc. software.
 *
 * (c) 2016, ecentria group, inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecentria\Bundle\CrontabBundle\Validator\Constraints;

use Cron\CronExpression;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Crontab syntax validator
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class CrontabSyntaxValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!CronExpression::isValidExpression($value)) {
            $this->context->addViolation(
                sprintf(
                    'Job frequency value "%s" is incorrect',
                    $value
                )
            );
        }
    }
}

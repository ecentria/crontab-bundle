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

use Symfony\Component\Validator\Constraint;

/**
 * Crontab syntax
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 *
 * @Annotation
 * @Target({"PROPERTY", "ANNOTATION"})
 */
class CrontabSyntax extends Constraint
{
    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::PROPERTY_CONSTRAINT;
    }
}

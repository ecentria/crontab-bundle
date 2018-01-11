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
 * Job provider interface
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
interface JobProviderInterface
{
    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Provide
     *
     * @return array|Job[]
     */
    public function provide(): array;
}

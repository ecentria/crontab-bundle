<?php
/*
 * This file is part of the ecentria software.
 *
 * (c) 2018, ecentria, Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecentria\Bundle\CrontabBundle\Services;

/**
 * Hostname manager
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class HostnameManager
{
    /**
     * Get hostname
     *
     * @return string
     */
    public function getHostname(): string
    {
        return gethostname();
    }
}

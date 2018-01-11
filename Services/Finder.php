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

/**
 * Finder
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class Finder
{
    /**
     * Get content
     *
     * @param string $filename
     *
     * @return string
     */
    public function getContent($filename): string
    {
        if (file_exists($filename)) {
            return file_get_contents($filename);
        }

        return '';
    }
}

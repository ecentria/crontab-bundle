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

use Symfony\Component\Filesystem\Filesystem;

/**
 * Crontab executor
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class CrontabExecutor
{
    const FILENAME = 'jobs.crontab';

    /**
     * Compiler
     *
     * @var JobsCompiler
     */
    private $compiler;

    /**
     * Path
     *
     * @var string
     */
    private $path;

    /**
     * Filesystem
     *
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Finder
     *
     * @var Finder
     */
    private $finder;

    /**
     * Crontab executor constructor
     *
     * @param JobsCompiler    $compiler
     * @param string          $path
     * @param Filesystem|null $filesystem
     * @param Finder|null     $finder
     */
    public function __construct(
        JobsCompiler $compiler,
        $path,
        Filesystem $filesystem = null,
        Finder $finder = null
    ) {
        $this->compiler = $compiler;
        $this->path = $path;
        $this->filesystem = $filesystem ? : new Filesystem();
        $this->finder = $finder ? : new Finder();
    }

    /**
     * Execute
     *
     * @return bool
     */
    public function dump(): bool
    {
        $filename = $this->path . DIRECTORY_SEPARATOR . self::FILENAME;

        $renderedContent = $this->compiler->render();
        $actualContent = $this->finder->getContent($filename);

        if ($renderedContent === $actualContent) {
            return false;
        }

        $this->filesystem->dumpFile($filename, $renderedContent);

        return true;
    }
}

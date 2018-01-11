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

use Ecentria\Bundle\CrontabBundle\Services\CrontabExecutor;
use Ecentria\Bundle\CrontabBundle\Services\Finder;
use Ecentria\Bundle\CrontabBundle\Services\JobsCompiler;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use \PHPUnit_Framework_MockObject_MockObject as Mock;

/**
 * Crontab executor test
 *
 * @property Mock|JobsCompiler $jobCompiler
 * @property Mock|Filesystem   $filesystem
 * @property Mock|Finder       $finder
 * @property CrontabExecutor   $executor
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class CrontabExecutorTest extends TestCase
{
    const EMAIL = 'test@example.com';

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->jobCompiler = $this->getMockBuilder(JobsCompiler::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->filesystem = $this->getMockBuilder(Filesystem::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->finder = $this->getMockBuilder(Finder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->executor = new CrontabExecutor(
            $this->jobCompiler,
            'foo',
            $this->filesystem,
            $this->finder
        );
    }

    /**
     * Test render with changes
     *
     * @return void
     */
    public function testRenderWithChanges()
    {
        $filename = 'foo' . DIRECTORY_SEPARATOR . CrontabExecutor::FILENAME;

        $actualContent = 'foo';
        $renderedContent = 'bar';

        $this->jobCompiler->expects($this->once())
            ->method('render')
            ->willReturn($renderedContent);

        $this->finder->expects($this->once())
            ->method('getContent')
            ->with($filename)
            ->willReturn($actualContent);

        $this->filesystem->expects($this->once())
            ->method('dumpFile')
            ->with(
                $filename,
                $renderedContent
            );

        $this->assertTrue($this->executor->dump());
    }

    /**
     * Test render no changes
     *
     * @return void
     */
    public function testRenderNoChanges()
    {
        $filename = 'foo' . DIRECTORY_SEPARATOR . CrontabExecutor::FILENAME;

        $actualContent = 'bar';
        $renderedContent = 'bar';

        $this->jobCompiler->expects($this->once())
            ->method('render')
            ->willReturn($renderedContent);

        $this->finder->expects($this->once())
            ->method('getContent')
            ->with($filename)
            ->willReturn($actualContent);

        $this->filesystem->expects($this->never())
            ->method('dumpFile');

        $this->assertFalse($this->executor->dump());
    }
}

<?php
/*
 * This file is part of the Ecentria software.
 *
 * (c) 2017, Ecentria, Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ecentria\Bundle\CrontabBundle\Model;

use Ecentria\Bundle\CrontabBundle\Validator\Constraints\CrontabSyntax;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Job
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class Job
{
    /**
     * Description
     *
     * @var string
     *
     * @Assert\NotBlank(message="Description should not be blank")
     */
    private $description;

    /**
     * Frequency
     *
     * @var string
     *
     * @CrontabSyntax()
     */
    private $frequency;

    /**
     * Command
     *
     * @var string
     *
     * @Assert\NotBlank(message="Command should not be blank")
     */
    private $command;

    /**
     * Parameters
     *
     * @var string
     */
    private $parameters;

    /**
     * Job constructor
     *
     * @param string $description
     * @param string $frequency
     * @param string $command
     * @param string $parameters
     */
    public function __construct(
        string $description,
        string $frequency,
        string $command,
        string $parameters
    ) {
        $this->description = $description;
        $this->frequency = $frequency;
        $this->command = $command;
        $this->parameters = $parameters;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Job
     */
    public function setDescription(string $description): Job
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get frequency
     *
     * @return string
     */
    public function getFrequency(): string
    {
        return $this->frequency;
    }

    /**
     * Set frequency
     *
     * @param string $frequency
     *
     * @return Job
     */
    public function setFrequency(string $frequency): Job
    {
        $this->frequency = $frequency;
        return $this;
    }

    /**
     * Get command
     *
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * Set command
     *
     * @param string $command
     *
     * @return Job
     */
    public function setCommand(string $command): Job
    {
        $this->command = $command;
        return $this;
    }

    /**
     * Get parameters
     *
     * @return string
     */
    public function getParameters(): string
    {
        return $this->parameters;
    }

    /**
     * Set parameters
     *
     * @param string $parameters
     *
     * @return Job
     */
    public function setParameters(string $parameters): Job
    {
        $this->parameters = $parameters;
        return $this;
    }
}

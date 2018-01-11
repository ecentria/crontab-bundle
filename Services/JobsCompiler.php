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
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Jobs compiler
 *
 * @author Sergey Chernecov <sergey.chernecov@gmail.com>
 */
class JobsCompiler
{
    /**
     * Validator
     *
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * Email
     *
     * @var null|string
     */
    private $email = null;

    /**
     * Providers
     *
     * @var array|JobProviderInterface[]
     */
    private $providers = [];

    /**
     * Jobs compiler constructor
     *
     * @param ValidatorInterface $validator
     * @param string|null        $email
     */
    public function __construct(
        ValidatorInterface $validator,
        $email = null
    ) {
        $this->validator = $validator;
        $this->email = $email;
    }

    /**
     * Register provider
     *
     * @param JobProviderInterface $provider
     *
     * @return $this
     */
    public function registerProvider(JobProviderInterface $provider): JobsCompiler
    {
        $this->providers[$provider->getName()] = $provider;

        return $this;
    }

    /**
     * Render
     *
     * @return string
     * @throws \DomainException
     */
    public function render(): string
    {
        $jobs = [];
        foreach ($this->providers as $provider) {
            $jobs = array_merge(
                $jobs,
                $provider->provide()
            );
        }

        $errors = [];
        foreach ($jobs as $job) {
            $violations = $this->validator->validate($job);
            if ($violations->count()) {
                /** @var ConstraintViolation $violation */
                foreach ($violations as $violation) {
                    $errors[] = $violation->getMessage();
                }
            }
        }

        if ($errors) {
            throw new \DomainException(
                sprintf(
                    'Cannot compile jobs. Errors: [%s]',
                    implode('], [', $errors)
                )
            );
        }

        return $this->renderFileContent($jobs, $this->email);
    }

    /**
     * Render
     *
     * @param array|Job[] $jobs
     * @param string|null $email
     *
     * @return string
     */
    private function renderFileContent(array $jobs, string $email = null): string
    {
        $fileContent = '';

        $fileContent .= '# This file has been generated dynamically' . PHP_EOL;
        $fileContent .= '# All manual changes will be erased soon' . PHP_EOL;

        $fileContent .= PHP_EOL;

        if ($email) {
            $fileContent .= 'MAILTO="' . $email . '"' . PHP_EOL;
            $fileContent .= PHP_EOL;
        }

        foreach ($jobs as $job) {
            $fileContent .= '# ' . $job->getDescription() . PHP_EOL;
            $fileContent .= $job->getFrequency() . ' ' . $job->getCommand() . ' ' . $job->getParameters() . PHP_EOL;
            $fileContent .= PHP_EOL;
        }

        return $fileContent;
    }
}

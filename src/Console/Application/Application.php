<?php

/*
 * This file is part of the cli package.
 *
 * (c) Gustavo Falco <comfortablynumb84@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IronEdge\Component\Cli\Console\Application;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputOption;

/**
 * Console Application class.
 *
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 */
class Application extends BaseApplication
{
    /**
     * Field _applicationOptions.
     *
     * @var array
     */
    private $_applicationOptions;

    /**
     * Field _kernel.
     *
     * @var \IronEdge\Component\Kernel\Kernel
     */
    private $_kernel;


    /**
     * Application constructor.
     *
     * @param array $applicationOptions - Application Options.
     */
    public function __construct(array $applicationOptions = [])
    {
        $this->_applicationOptions = array_replace_recursive(
            [
                'applicationName'           => 'Default Application',
                'applicationVersion'        => '0.1'
            ],
            $applicationOptions
        );

        parent::__construct(
            $this->_applicationOptions['applicationName'],
            $this->_applicationOptions['applicationVersion']
        );

        if ($this->isKernelComponentInstalled()) {
            $this->setDispatcher($this->getKernel()->getEventDispatcher());
        }

        $commands = [];

        // @TODO: Add commands

        $this->addCommands($commands);

        // Add global options

        $this->getDefinition()
            ->addOption(
                new InputOption(
                    'env',
                    null,
                    InputOption::VALUE_REQUIRED,
                    'Environment to use when running this application. By default, we use the dev environment.'
                )
            );
    }

    /**
     * Returns the Kernel instance.
     *
     * @return \IronEdge\Component\Kernel\Kernel
     */
    public function getKernel()
    {
        if ($this->_kernel === null) {
            $this->assertKernelComponentIsInstalled();

            $this->_kernel = new \IronEdge\Component\Kernel\Kernel(
                [
                    'environment'           => 'dev'
                ]
            );
        }

        return $this->_kernel;
    }

    /**
     * Throws an exception if component ironedge/kernel is not installed.
     *
     * @return void
     */
    protected function assertKernelComponentIsInstalled()
    {
        if (!$this->isKernelComponentInstalled()) {
            throw new \RuntimeException(
                'You must install component "ironedge/kernel" to be able to access the Kernel instance from your '.
                'CLI command.'
            );
        }
    }

    /**
     * Determines if the ironedge/kernel component is installed.
     *
     * @return bool
     */
    protected function isKernelComponentInstalled()
    {
        return class_exists('\IronEdge\Component\Kernel\Kernel');
    }
}
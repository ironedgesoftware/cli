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

use IronEdge\Component\Cli\Exception\InvalidConfigException;
use Symfony\Component\Console\Application as BaseApplication;

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
     * Environment.
     *
     * @var string
     */
    private $_environment = 'dev';

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
     *
     * @throws InvalidConfigException
     */
    public function __construct(array $applicationOptions = [])
    {
        if ($this->isKernelComponentInstalled()) {
            $kernel = $this->getKernel();
            $appName = $kernel->getComponentConfigParam('ironedge/cli', 'application.name');
            $appVersion = $kernel->getComponentConfigParam('ironedge/cli', 'application.version');
        } else {
            $appName = 'Default Application';
            $appVersion = '0.1';
        }

        $this->_applicationOptions = array_replace_recursive(
            [
                'applicationName'           => $appName,
                'applicationVersion'        => $appVersion,
                'commands'                  => []
            ],
            $applicationOptions
        );

        parent::__construct(
            $this->_applicationOptions['applicationName'],
            $this->_applicationOptions['applicationVersion']
        );

        $this->addCommands($this->_applicationOptions['commands']);

        if ($this->isKernelComponentInstalled()) {
            $kernel = $this->getKernel();
            $this->setDispatcher($kernel->getEventDispatcher());

            $commands = $kernel->getComponentConfigParam('ironedge/cli', 'commands', []);

            foreach ($commands as $commandData) {
                $command = null;

                if (isset($commandData['disabled']) && $commandData['disabled']) {
                    continue;
                }

                if (isset($commandData['serviceId'])) {
                    if (!is_string($commandData['serviceId'])) {
                        throw InvalidConfigException::create(
                            'serviceId',
                            'a string. This parameter is the service ID of the command.'
                        );
                    }

                    $command = $kernel->getContainerService($commandData['serviceId']);
                } else if (isset($commandData['class'])) {
                    if (!is_string($commandData['class'])) {
                        throw InvalidConfigException::create(
                            'class',
                            'a string. This parameter is the class of the command.'
                        );
                    }

                    $commandClass = $commandData['class'];

                    $command = new $commandClass();
                }

                if (!$command) {
                    throw InvalidConfigException::create(
                        null,
                        null,
                        'You must enter, for each command, a parameter "class" or "serviceId".'
                    );
                }

                $this->add($command);
            }
        }
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
                    'environment'           => $this->getEnvironment()
                ]
            );
        }

        return $this->_kernel;
    }

    /**
     * Getter method for field _environment.
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->_environment;
    }

    /**
     * Setter method for field environment.
     *
     * @param string $environment - environment.
     *
     * @return $this
     */
    public function setEnvironment($environment)
    {
        $this->_environment = $environment;

        return $this;
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
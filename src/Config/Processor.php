<?php

/*
 * This file is part of the frenzy-framework package.
 *
 * (c) Gustavo Falco <comfortablynumb84@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IronEdge\Component\Cli\Config;

use IronEdge\Component\Cli\Exception\InvalidConfigException;
use IronEdge\Component\Config\ConfigInterface;
use IronEdge\Component\Kernel\Config\ProcessorInterface;
use IronEdge\Component\Kernel\Kernel;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/*
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 */
class Processor implements ProcessorInterface
{
    public function onComponentConfigRegistration(
        Kernel $kernel,
        ConfigInterface $config,
        $sourceComponentName,
        $targetComponentName,
        array $registeredConfig
    ) {
        // Makes no sense to register commands on a SAPI different than the CLI

        if (php_sapi_name() !== 'cli') {
            return;
        }

        if (isset($registeredConfig['commands'])) {
            if (!is_array($registeredConfig['commands'])) {
                throw InvalidConfigException::create('commands', 'It must be an array of commands configurations.');
            }

            $config->merge('components.ironedge/cli.commands', $registeredConfig['commands'], []);
        }
    }

    public function onBeforeCache(Kernel $kernel, ConfigInterface $config)
    {

    }

    public function onAfterCache(Kernel $kernel, ConfigInterface $config)
    {

    }

    public function onBeforeContainerCompile(
        Kernel $kernel,
        ConfigInterface $config,
        ContainerBuilder $containerBuilder
    ) {

    }


}
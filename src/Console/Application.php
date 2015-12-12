<?php

/*
 * This file is part of Cliched.
 *
 * (c) Gustavo Falco <comfortablynumb84@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IronEdge\Component\Cliched\Console;

use Symfony\Component\Console\Application as BaseApplication;

/**
 * Console Application class.
 *
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 */
class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct('cliched', '0.1');
    }

}
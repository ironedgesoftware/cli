<?php

/*
 * This file is part of the cli package.
 *
 * (c) Gustavo Falco <comfortablynumb84@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IronEdge\Component\Cli\Exception;


/*
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 */
class InvalidConfigException extends BaseException
{
    public static function create($name, $type, $msg = null)
    {
        return new self(
            $msg ? $msg : 'Invalid cli config "'.$name.'". It must be '.$type
        );
    }
}
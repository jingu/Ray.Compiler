<?php
/**
 * This file is part of the Ray.Compiler package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\Compiler;

use PhpParser\Node;

final class IpQualifier
{
    /**
     * @var \ReflectionParameter
     */
    public $param;

    /**
     * @var mixed
     */
    public $qualifier;

    /**
     * @var Node
     */
    private $node;

    public function __construct(\ReflectionParameter $param, $qualifier)
    {
        $this->param = $param;
        $this->qualifier = $qualifier;
    }

    public function __toString()
    {
        return \serialize($this->qualifier);
    }
}

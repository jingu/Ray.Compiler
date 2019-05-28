<?php
/**
 * This file is part of the Ray.Compiler package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\Compiler;

use Koriym\Printo\Printo;
use Ray\Di\Container;
use Ray\Di\DependencyInterface;
use Ray\Di\Name;

final class GraphDumper
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var string
     */
    private $scriptDir;

    public function __construct(Container $container, string $scriptDir)
    {
        $this->container = $container;
        $this->scriptDir = $scriptDir;
    }

    public function __invoke()
    {
        $container = $this->container->getContainer();
        foreach ($container as $dependencyIndex => $dependency) {
            $isNorInjector = $dependencyIndex !== 'Ray\Di\InjectorInterface-' . Name::ANY;
            if ($dependency instanceof DependencyInterface && $isNorInjector) {
                $this->write($dependencyIndex);
            }
        }
    }

    /**
     * Write html
     */
    private function write(string $dependencyIndex)
    {
        if ($dependencyIndex === 'Ray\Aop\MethodInvocation-') {
            return;
        }
        list($interface, $name) = \explode('-', $dependencyIndex);
        $instance = (new ScriptInjector($this->scriptDir))->getInstance($interface, $name);
        $graph = (string) (new Printo($instance))
            ->setRange(Printo::RANGE_ALL)
            ->setLinkDistance(130)
            ->setCharge(-500);
        $graphDir = $this->scriptDir . '/graph/';
        if (! \file_exists($graphDir)) {
            \mkdir($graphDir);
        }
        $file = $graphDir . \str_replace(['\\', '/'], '_', $dependencyIndex) . '.html';
        \file_put_contents($file, $graph);
    }
}

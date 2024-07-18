<?php

namespace Tests\Util\RunTests;

trait RunTestsTrait
{
    public function runTests(): void
    {
        $reflection = new \ReflectionClass(static::class);

        echo $reflection->getName() . PHP_EOL;

        if (method_exists($this, 'testShouldReturnInitialCollection')) {
            $this->testShouldReturnInitialCollection();
            echo "\t✅  testShouldReturnInitialCollection" . PHP_EOL;
        }

        foreach ($reflection->getMethods() as $method) {
            if ($method->name === 'testShouldReturnInitialCollection') {
                continue;
            }

            if (str_starts_with($method->name, 'testShould')) {
                $method->invoke($this);

                echo "\t✅  {$method->name}" . PHP_EOL;
            }
        }
    }
}

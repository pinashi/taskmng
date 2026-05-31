<?php 

declare(strict_types=1);

namespace App;

Class Container {
    private array $bindings = [];

    public function bind(string $class, callable $resolver): void {
        $this->bindings[$class] = $resolver;
    }

    public function make(string $class): object {
        if (isset($this->bindings[$class])) {
        return ($this->bindings[$class])($this);
        }

        return new $class();
    }
}
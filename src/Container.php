<?php 

declare(strict_types=1);

namespace App;

/**
 * Simple dependency injection container.
 * Manages class bindings and resolves dependencies.
 */
Class Container {
    /**
     * @var array $bindings Registered class bindings
     */
    private array $bindings = [];

    /**
     * Register a class binding with a resolver function.
     *
     * @param string $class Fully qualified class name
     * @param callable $resolver Function that returns the class instance
     * @return void
     */
    public function bind(string $class, callable $resolver): void {
        $this->bindings[$class] = $resolver;
    }

    /**
     * Resolve and return an instance of the given class.
     * Uses registered binding if available, otherwise instantiates directly.
     *
     * @param string $class Fully qualified class name
     * @return object Resolved class instance
     * @throws \Exception If no binding found and class cannot be instantiated
     */
    public function make(string $class): object {
        if (isset($this->bindings[$class])) {
            return ($this->bindings[$class])($this);
        }

        return new $class();
    }
}
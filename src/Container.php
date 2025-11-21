<?php

namespace Booth;

use Psr\Container\ContainerInterface;

final class Container implements ContainerInterface
{
    private array $definitions = [];

    public function register(string $id, object|callable $definition): self
    {
        $this->definitions[$id] = $definition;

        return $this;
    }

    public function singleton(string $id, object|callable $definition): self
    {
        $this->definitions[$id] = function () use ($id, $definition) {
            $instance = $definition();

            $this->definitions[$id] = $instance;

            return $instance;
        };

        return $this;
    }

    public function forget(string $id): self
    {
        unset($this->definitions[$id]);

        return $this;
    }

    public function get(string $id)
    {
        if (! $this->has($id)) {
            throw new NotFoundException($id);
        }

        $definition = $this->definitions[$id];

        return is_callable($definition) ? $definition() : $definition;
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->definitions);
    }
}
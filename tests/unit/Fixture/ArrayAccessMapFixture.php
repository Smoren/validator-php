<?php

namespace Smoren\Validator\Tests\Unit\Fixture;

use Traversable;

/**
 * @implements \ArrayAccess<mixed>
 * @implements \Countable
 */
class ArrayAccessMapFixture implements \ArrayAccess, \Countable, \IteratorAggregate
{
    /**
     * @var array<mixed>
     */
    protected array $data;

    /**
     * @param array<mixed> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function offsetExists($offset): bool
    {
        return \array_key_exists($offset, $this->data);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }

    public function count(): int
    {
        return \count($this->data);
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->data);
    }
}

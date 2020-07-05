<?php

declare(strict_types=1);

namespace gdianov\opium\tests\classes;

/**
 * Class T
 * @package gdianov\opium\tests
 */
final class T
{
    /**
     * @var int
     */
    private $foo;

    /**
     * @var
     */
    public $bar;

    /**
     * SomeT constructor.
     * @param int $foo
     */
    public function __construct(int $foo)
    {
        $this->foo = $foo;
    }
}
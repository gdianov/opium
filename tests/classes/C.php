<?php


namespace gdianov\opium\tests\classes;

/**
 * Class C
 * @package gdianov\opium\tests
 */
final class C
{
    /**
     * @var T
     */
    private $t;

    /**
     * C constructor.
     * @param T $t
     */
    public function __construct(T $t)
    {
        $this->t = $t;
    }

    /**
     * @return T
     */
    public function getT(): T
    {
        return $this->t;
    }
}
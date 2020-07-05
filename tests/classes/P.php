<?php


namespace gdianov\opium\tests\classes;


final class P
{
    /**
     * @var C
     */
    public $c;

    /**
     * @var string
     */
    private $str;

    /**
     * P constructor.
     * @param string $str
     */
    public function __construct(string $str)
    {
        $this->str = $str;
    }

    /**
     * @return string
     */
    public function getStr(): string
    {
        return $this->str;
    }
}
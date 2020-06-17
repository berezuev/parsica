<?php declare(strict_types=1);

namespace Tests\Mathias\ParserCombinator\Parser;

use Mathias\ParserCombinator\PHPUnit\ParserAssertions;
use PHPUnit\Framework\TestCase;
use function Mathias\ParserCombinator\char;
use function Mathias\ParserCombinator\float;
use function Mathias\ParserCombinator\sequence;

final class FunctorTest extends TestCase
{
    use ParserAssertions;

    /** @test */
    public function map()
    {
        $parser =
            char('a')->followedBy(char('b'))
                ->map('strtoupper');

        $expected = "B";

        $this->assertParse($expected, $parser, "abca");
    }

    /** @test */
    public function construct()
    {
        $parser = sequence(char('a'), char('b'))
            ->construct(__NAMESPACE__ . '\\MyType1');

        $expected = new MyType1("b");

        $this->assertParse($expected, $parser, "abc");
    }

    /** @test */
    public function simple_eur()
    {
        $parser = sequence(
            char('€'),
            float()->map('floatval')->construct(SimpleEur::class)
        );
        $this->assertParse(new SimpleEur(1.25), $parser, "€1.25");

    }
}

class MyType1
{
    private $val;

    public function __construct($val)
    {
        $this->val = $val;
    }
}


final class SimpleEur
{
    private float $val;

    public function __construct(float $val)
    {
        $this->val = $val;
    }

}

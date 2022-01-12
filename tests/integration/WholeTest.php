<?php

namespace Tests\Kily\Tools\Upd;

use Kily\Tools\Upd\Generator;
use Kily\Tools\Upd\Validator;
use Kily\Tools\Upd\Parser;
use Kily\Tools\Upd\Upd;
use PHPUnit\Framework\TestCase;

/**
 * Class WholeTest
 *
 * @covers \Kily\Tools\Upd\Generator
 */
class WholeTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_parse_generate_validate(): void
    {
        $arr = Parser::parseFile(__DIR__.'/../Assets/good_upd_with_km.xml');
        $xml = Generator::generateString($arr);
        $ret = Validator::validateString($xml, Upd::VER_5_01);
        $this->assertTrue($ret);
    }
}

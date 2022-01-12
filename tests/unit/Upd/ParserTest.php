<?php

namespace Tests\Kily\Tools\Upd;

use Kily\Tools\Upd\Parser;
use Kily\Tools\Upd\Upd;
use Kily\Tools\Upd\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

/**
 * Class ParserTest
 *
 * @covers \Kily\Tools\Upd\Parser
 */
class ParserTest extends TestCase
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

    /**
     * @covers Kily\Tools\Upd\Parser::parseFile
     */

    public function test_file_parser_works(): void
    {
        $this->assertIsArray(Parser::parseFile(__DIR__.'/../../Assets/good_upd.xml'));
    }

    /**
     * @covers Kily\Tools\Upd\Parser::parseFile
     */

    public function test_file_parser_throws(): void
    {
        $this->expectException(ValidationException::class);
        Parser::parseFile("__NONEXISTENT__");
    }

    /**
     * @covers Kily\Tools\Upd\Parser::parseString
     */

    public function test_string_parser_works(): void
    {
        $this->assertIsArray(Parser::parseString(file_get_contents(__DIR__.'/../../Assets/good_upd.xml')));
    }

    /**
     * @covers Kily\Tools\Upd\Parser::parseString
     */

    public function test_string_parser_throws(): void
    {
        $this->expectException(ValidationException::class);
        Parser::parseString("");
    }
}

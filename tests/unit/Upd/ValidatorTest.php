<?php

namespace Tests\Kily\Tools\Upd;

use Kily\Tools\Upd\Validator;
use Kily\Tools\Upd\Upd;
use Kily\Tools\Upd\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

/**
 * Class ValidatorTest
 *
 * @covers \Kily\Tools\Upd\Validator
 */
class ValidatorTest extends TestCase
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
     * @covers Kily\Tools\Upd\Validator::validateFile
     */

    public function test_file_validator_works(): void
    {
        $this->assertTrue(Validator::validateFile(__DIR__.'/../../Assets/good_upd.xml', Upd::VER_5_01));
        $this->assertTrue(Validator::validateFile(__DIR__.'/../../Assets/good_upd.xml', Upd::VER_5_01_03));
        $this->assertFalse(Validator::validateFile(__DIR__.'/../../Assets/good_upd.xml', Upd::VER_5_01_02));
        $this->assertFalse(Validator::validateFile(__DIR__.'/../../Assets/bad_upd.xml', Upd::VER_5_01));
    }

    /**
     * @covers Kily\Tools\Upd\Validator::validateFile
     */

    public function test_file_validator_throws(): void
    {
        $this->expectException(ValidationException::class);
        Validator::validateFile("__NONEXISTENT__", Upd::VER_5_01);
    }

    /**
     * @covers Kily\Tools\Upd\Validator::validateString
     */

    public function test_string_validator_works(): void
    {
        $this->assertTrue(Validator::validateString(file_get_contents(__DIR__.'/../../Assets/good_upd.xml'), Upd::VER_5_01));
        $this->assertTrue(Validator::validateString(file_get_contents(__DIR__.'/../../Assets/good_upd.xml'), Upd::VER_5_01_03));
        $this->assertFalse(Validator::validateString(file_get_contents(__DIR__.'/../../Assets/good_upd.xml'), Upd::VER_5_01_02));
        $this->assertFalse(Validator::validateString(file_get_contents(__DIR__.'/../../Assets/bad_upd.xml'), Upd::VER_5_01));
    }

    /**
     * @covers Kily\Tools\Upd\Validator::validateString
     */

    public function test_string_validator_throws(): void
    {
        $this->expectException(ValidationException::class);
        Validator::validateString("", Upd::VER_5_01);
    }
}

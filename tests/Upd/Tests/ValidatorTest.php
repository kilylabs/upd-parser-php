<?php

namespace Tests\Unit\Kily\Tools\Upd;

use Kily\Tools\Upd\Validator;
use Kily\Tools\Upd\Upd;
use Kily\Tools\Upd\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

/**
 * Class GostTest.
 *
 * @covers \Kily\Payment\QR\Gost
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

    public function testValidateFile(): void
    {
        $this->assertTrue(Validator::validateFile(__DIR__.'/../../Assets/good_upd.xml',Upd::VER_5_01));
        $this->assertTrue(Validator::validateFile(__DIR__.'/../../Assets/good_upd.xml',Upd::VER_5_01_03));
        $this->assertFalse(Validator::validateFile(__DIR__.'/../../Assets/good_upd.xml',Upd::VER_5_01_02));
        $this->assertFalse(Validator::validateFile(__DIR__.'/../../Assets/bad_upd.xml',Upd::VER_5_01));
    }

    /**
     * @covers Kily\Tools\Upd\Validator::validateFile
     */

    public function testValidateFile1(): void
    {
        $this->expectException(ValidationException::class);
        Validator::validateFile("__NONEXISTENT__",Upd::VER_5_01);
    }

    /**
     * @covers Kily\Tools\Upd\Validator::validateString
     */

    public function testValidateString(): void
    {
        $this->assertTrue(Validator::validateString(file_get_contents(__DIR__.'/../../Assets/good_upd.xml'),Upd::VER_5_01));
        $this->assertTrue(Validator::validateString(file_get_contents(__DIR__.'/../../Assets/good_upd.xml'),Upd::VER_5_01_03));
        $this->assertFalse(Validator::validateString(file_get_contents(__DIR__.'/../../Assets/good_upd.xml'),Upd::VER_5_01_02));
        $this->assertFalse(Validator::validateString(file_get_contents(__DIR__.'/../../Assets/bad_upd.xml'),Upd::VER_5_01));
    }

    /**
     * @covers Kily\Tools\Upd\Validator::validateString
     */

    public function testValidateString1(): void
    {
        $this->expectException(ValidationException::class);
        Validator::validateString("",Upd::VER_5_01);
    }
}

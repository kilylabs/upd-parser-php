<?php

namespace Tests\Kily\Tools\Upd;

use Kily\Tools\Upd\Generator;
use Kily\Tools\Upd\Upd;
use Kily\Tools\Upd\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

/**
 * Class GeneratorTest
 *
 * @covers \Kily\Tools\Upd\Generator
 */
class GeneratorTest extends TestCase
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
     * @covers Kily\Tools\Upd\Generator::generateFile
     */

    public function test_file_generator_works(): void
    {
        $temp = tempnam(sys_get_temp_dir(), 'phpunit_');
        $arr = require(__DIR__.'/../../Assets/good_upd_parsed_array.php');
        $this->assertTrue((bool)Generator::generateFile($arr, $temp));
    }

    /**
     * @covers Kily\Tools\Upd\Generator::generateString
     */

    public function test_string_generator_works(): void
    {
        $arr = require(__DIR__.'/../../Assets/good_upd_parsed_array.php');
        $this->assertIsString(Generator::generateString($arr));
    }
}

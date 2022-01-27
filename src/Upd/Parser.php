<?php

declare(strict_types=1);

namespace Kily\Tools\Upd;

use Kily\Tools\Upd\Traits\Instansable;
use Kily\Tools\Upd\Exception\ValidationException;

class Parser
{
    use Instansable;

    public static function parseString(string $source, bool $validate = true, string $ver = Upd::VER_5_01)
    {
        if (!trim($source)) {
            throw new ValidationException('It seems input string is empty...');
        }
        return static::getInstance()->parse($source, $validate, $ver);
    }

    public static function parseFile(string $file, bool $validate = true, string $ver = Upd::VER_5_01)
    {
        if (!trim($file)) {
            throw new ValidationException("It seems file {$file} does not exist...");
        }
        if (file_exists($file) && is_readable($file)) {
            return static::getInstance()->parse(file_get_contents($file), $validate, $ver);
        } else {
            throw new ValidationException("It seems file {$file} does not exist or is not readable...");
        }
    }

    public function parse(string $source, bool $validate = true, string $ver = Upd::VER_5_01)
    {
        if ($validate && !Validator::validateString($source, $ver)) {
            throw new ValidationException("It seems it isn't valid UPD xml format");
        }
        $obj = simplexml_load_string($source, null, LIBXML_NOCDATA);
        $arr = json_decode(json_encode($obj), true);
        return $arr;
    }
}

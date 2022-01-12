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
        $this->fixArray($arr);
        return $arr;
    }

    protected function fixArray(array &$array)
    {
        $array_walk_recursive_array = function (array &$array, callable $callback) use (&$array_walk_recursive_array) {
            foreach ($array as $k => &$v) {
                if ($k == '@attributes') {
                    $array['_attributes'] = $v;
                    unset($array[$k]);
                }
                if (is_array($v)) {
                    $array_walk_recursive_array($v, $callback);
                } else {
                    $callback($v, $k, $array);
                }
            }
        };

        $array_walk_recursive_array($array, function ($v, $k, $array) {
        });
    }
}

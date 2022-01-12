<?php

declare(strict_types=1);

namespace Kily\Tools\Upd;

use Kily\Tools\Upd\Traits\Instansable;
use Spatie\ArrayToXml\ArrayToXml;

class Generator
{
    use Instansable;

    public static function generateFile(array $arr, string $file)
    {
        return static::getInstance()->generate($arr, $file);
    }

    public static function generateString(array $arr)
    {
        return static::getInstance()->generate($arr);
    }

    public function generate(array $arr, string $file = null)
    {
        $str = ArrayToXml::convert($arr, 'Файл', true, 'utf-8', '1.0', ['formatOutput' => true]);
        return $file ? file_put_contents($file, $str) : $str;
    }
}

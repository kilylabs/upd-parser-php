<?php

declare(strict_types=1);

namespace Kily\Tools\Upd;

use DOMDocument;
use Kily\Tools\Upd\Upd;
use Kily\Tools\Upd\Exception\ValidationException;
use Kily\Tools\Upd\Traits\Instansable;

class Validator
{
    use Instansable;

    protected const VERSIONMAP = [
        Upd::VER_5_01_02=>'ON_NSCHFDOPPOK_1_997_02_05_01_02.xsd',
        Upd::VER_5_01_03=>'ON_NSCHFDOPPR_1_997_01_05_01_03.xsd',
        Upd::VER_5_01=>'ON_NSCHFDOPPR_1_997_01_05_01_03.xsd',
    ];

    public static function validateString(string $source, string $ver): bool
    {
        if (!trim($source)) {
            throw new ValidationException('It seems input string is empty...');
        }
        return static::getInstance()->validate($source,$ver);
    }

    public static function validateFile(string $file, string $ver): bool
    {
        if (!trim($file)) {
            throw new ValidationException("It seems file {$file} does not exist...");
        }
        if (file_exists($file) && is_readable($file)) {
            return static::getInstance()->validate($file, $ver, true);
        } else {
            throw new ValidationException("It seems file {$file} does not exist or is not readable...");
        }
    }

    public function validate(string $source, string $ver, bool $is_file = false): bool
    {
        if (!$this->validateVersion($ver)) {
            throw new ValidationException('Allowed versions are: '.implode('|', array_keys(static::VERSIONMAP)));
        }
        $xsd_path = implode(DIRECTORY_SEPARATOR, [__DIR__,'assets',static::VERSIONMAP[$ver]]);
        libxml_use_internal_errors(true);
        $xml = new DOMDocument();
        if ($is_file) {
            $xml->load($source);
        } else {
            $xml->loadXML($source);
        }
        $ret = $xml->schemaValidate($xsd_path);
        return $ret;
    }

    protected function validateVersion(string $ver): bool
    {
        return in_array($ver, array_keys(static::VERSIONMAP));
    }

    public function getLastValidationErrors(): array
    {
        $errors = libxml_get_errors();
        $strs = [];
        foreach ($errors as $error) {
            $str = '';
            switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $str = "Warning {$error->code}: ";
                break;
            case LIBXML_ERR_ERROR:
                $str = "Error {$error->code}: ";
                break;
            case LIBXML_ERR_FATAL:
                $str = "Fatal Error {$error->code}: ";
                break;
            }
            $str .= trim($error->message);
            if ($error->file) {
                $str .= " in {$error->file}";
            }
            $str .= " on line {$error->line}";
            $strs[] = $str;
        }
        libxml_clear_errors();
        return $strs;
    }
}

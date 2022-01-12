# Парсер, валидатор и генератор УПД в формате XML
Парсер и генератор документов в формате [УПД](https://www.nalog.gov.ru/rn77/related_activities/el_doc/el_bus_entities/8335278/) версии 5.01.02+  для PHP 7.4+.

Установка
------------

Рекомендуемый способ установки через
[Composer](http://getcomposer.org):

```
$ composer require kilylabs/upd-parser-php
```

Использование
-----
#### Пример работы:
```php
<?php

require __DIR__.'/vendor/autoload.php';

use Kily\Tools\Upd\Validator;
use Kily\Tools\Upd\Parser;
use Kily\Tools\Upd\Generator;
use Kily\Tools\Upd\Upd;

// Превратим XML в массив
$arr = Parser::parseFile(__DIR__.'/examples/good_upd.xml');
var_export($arr);
/*
array (
  'СвУчДокОбор' => 
  array (
    'СвОЭДОтпр' => 
    array (
      '_attributes' => 
      array (
        'НаимОрг' => 'АО "ПФ "СКБ Контур"',
        'ИННЮЛ' => '6663003127',
        'ИдЭДО' => '2BM',
      ),
    ),
    '_attributes' => 
    array (
      'ИдОтпр' => '2BM-771365132548--2016072812271194308390000000',
      'ИдПол' => '2BM-7717801381-771701001-201701230806066030723',
    ),
    ...
*/

// Сгенерируем на основании массива новый XML
$arr['СвУчДокОбор']['_attributes']['ИдПол'] = '2BM-7717801381-771701001-000000000000000';
$xml = Generator::generateString($arr);
var_dump($xml);
/*
string(5159) "<?xml version="1.0" encoding="utf-8"?>
<Файл ИдФайл="RL-NB-146830-MARK" ВерсФорм="5.01" ВерсПрог="Diadoc 1.0">
  <СвУчДокОбор ИдОтпр="2BM-771365132548--2016072812271194308390000000" ИдПол="2BM-7717801381-771701001-000000000000000">
    <СвОЭДОтпр НаимОрг="АО &quot;ПФ &quot;СКБ Контур&quot;" ИННЮЛ="6663003127" ИдЭДО="2BM"/>
    ...
*/

// Проверим получившийся XML на валидность
$ret = Validator::validateString($xml,Upd::VER_5_01);
var_dump($ret);
```
#### Пример валидации:
```php
<?php
require __DIR__.'/vendor/autoload.php';

use Kily\Tools\Upd\Validator;
use Kily\Tools\Upd\Upd;

echo 'Производим валидацию файла по последней версии 5.01 (5.01.03): ';
if (!Validator::validateFile(__DIR__.'/examples/good_upd.xml', Upd::VER_5_01)) {
    echo "ERROR ";
    print_r(Validator::getLastValidationErrors());
} else {
    echo "OK\n";
}

echo 'Производим валидацию файла по версии 5.01.02 (выдаст ошибку): ';
if (!Validator::validateFile(__DIR__.'/examples/good_upd.xml', Upd::VER_5_01_02)) {
    echo "ERROR ";
    print_r(Validator::getLastValidationErrors());
} else {
    echo "OK\n";
}

echo 'Производим валидацию файла по версии 5.01.03: ';
if (!Validator::validateFile(__DIR__.'/examples/good_upd.xml', Upd::VER_5_01_03)) {
    echo "ERROR ";
    print_r(Validator::getLastValidationErrors());
} else {
    echo "OK\n";
}

echo 'Производим валидацию файла с кодами маркировки Честный Знак: ';
if (!Validator::validateFile(__DIR__.'/examples/good_upd_with_km.xml', Upd::VER_5_01)) {
    echo "ERROR ";
    print_r(Validator::getLastValidationErrors());
} else {
    echo "OK\n";
}

echo 'Производим валидацию XML из строки: ';
$xml = file_get_contents(__DIR__.'/examples/good_upd_with_km.xml');
if (!Validator::validateString($xml, Upd::VER_5_01)) {
    echo "ERROR ";
    print_r(Validator::getLastValidationErrors());
} else {
    echo "OK\n";
}
```
#### Пример парсинга:
```php
<?php
require __DIR__.'/vendor/autoload.php';

use Kily\Tools\Upd\Parser;
use Kily\Tools\Upd\Exception\ValidationException;
use Kily\Tools\Upd\Upd;

// Парсим файл
$arr = Parser::parseFile(__DIR__.'/examples/good_upd.xml');
var_export($arr);
/*
array (
  'СвУчДокОбор' => 
  array (
    'СвОЭДОтпр' => 
    array (
      '_attributes' => 
      array (
        'НаимОрг' => 'АО "ПФ "СКБ Контур"',
        'ИННЮЛ' => '6663003127',
        'ИдЭДО' => '2BM',
      ),
    ),
    '_attributes' => 
    array (
      'ИдОтпр' => '2BM-771365132548--2016072812271194308390000000',
      'ИдПол' => '2BM-7717801381-771701001-201701230806066030723',
    ),
    ...
*/

// Парсим файл без валидации
$arr = Parser::parseFile(__DIR__.'/examples/good_upd.xml', false);
var_export($arr);

// Парсим файл с валидацией по определенной версии
try {
    $arr = Parser::parseFile(__DIR__.'/examples/good_upd.xml', true, Upd::VER_5_01_02);
    var_export($arr);
} catch(ValidationException $e) {
    echo $e->getMessage();
}

// Парсим строку
$xml = file_get_contents(__DIR__.'/examples/good_upd.xml');
$arr = Parser::parseString($xml);
```
#### Пример генератора:
```php
<?php
require __DIR__.'/vendor/autoload.php';

use Kily\Tools\Upd\Parser;
use Kily\Tools\Upd\Generator;
use Kily\Tools\Upd\Upd;

// Распарсим XML нормального файла
$arr = Parser::parseFile(__DIR__.'/examples/good_upd.xml', false);
/*
array (
  'СвУчДокОбор' => 
  array (
    'СвОЭДОтпр' => 
    array (
      '_attributes' => 
      array (
        'НаимОрг' => 'АО "ПФ "СКБ Контур"',
        'ИННЮЛ' => '6663003127',
        'ИдЭДО' => '2BM',
      ),
    ),
    '_attributes' => 
    array (
      'ИдОтпр' => '2BM-771365132548--2016072812271194308390000000',
      'ИдПол' => '2BM-7717801381-771701001-201701230806066030723',
    ),
    ...
*/

// Изменим один из атрибутов
$arr['СвУчДокОбор']['_attributes']['ИдПол'] = '2BM-7717801381-771701001-000000000000000';

// Сгенерируем новый XML в строку
$xml = Generator::generateString($arr);
var_dump($xml);
/*
string(5159) "<?xml version="1.0" encoding="utf-8"?>
<Файл ИдФайл="RL-NB-146830-MARK" ВерсФорм="5.01" ВерсПрог="Diadoc 1.0">
  <СвУчДокОбор ИдОтпр="2BM-771365132548--2016072812271194308390000000" ИдПол="2BM-7717801381-771701001-000000000000000">
    <СвОЭДОтпр НаимОрг="АО &quot;ПФ &quot;СКБ Контур&quot;" ИННЮЛ="6663003127" ИдЭДО="2BM"/>
    ...
*/

// Сгенерируем новый XML в файл
$file = tempnam(sys_get_temp_dir(),'upd_');
$xml = Generator::generateFile($arr,$file);


```

TODO
-----
- При парсинге и генерации не использовать массивы (#1)
- Сделать нормальную документацию


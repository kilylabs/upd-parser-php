<?php

declare(strict_types=1);

namespace Kily\Tools\Upd\Traits;

trait Instansable {

    protected static $_item;

    public static function getInstance() {
        if(!static::$_item) {
            static::$_item = new static;
        }
        return static::$_item;
    }

}

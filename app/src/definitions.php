<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

use function DI\create;

return [
    'config' => create(Config::class),
    'db' => create(DB::class)
];
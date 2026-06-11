<?php

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/Router.php';

$router = new Router();
$router->dispatch();

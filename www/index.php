<?php


ini_set('display_errors', 1);

require __DIR__ . '/core/app.php';

$app = new App();

$app->autoload();
$app->config();
$app->session();
$app->routing();